<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\BotSession;
use App\Models\Cliente;
use App\Models\Mensagem;
use App\Models\Ordem;
use App\Services\WhatsappBotService;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Recebe webhooks do WhatsApp (Evolution API) e processa mensagens recebidas.
 *
 * Fluxo de uma mensagem recebida:
 *   1. Valida o secret via header `apikey` (opcional).
 *   2. Filtra apenas eventos `messages.upsert` que não sejam do próprio número
 *      e não sejam de grupos (@g.us).
 *   3. Extrai o número do JID ("5511999999999@s.whatsapp.net" → "5511999999999").
 *   4. Tenta localizar cliente pelo telefone (busca por sufixo).
 *   5. Se cliente encontrado: associa à OS mais recente ativa e salva mensagem.
 *   6. Se cliente não encontrado: aciona o bot mesmo assim (bot pede CPF/código OS).
 *   7. Aciona WhatsappBotService para resposta automática.
 *
 * CSRF excluído em bootstrap/app.php.
 *
 * Rota: POST /webhook/whatsapp → receive()
 */
class WhatsappController extends Controller
{
    public function __construct(
        private WhatsappBotService $bot,
        private WhatsappService    $whatsapp,
    ) {}

    public function receive(Request $request): Response
    {
        $secret = config('whatsapp.webhook_secret');
        if ($secret && $request->header('apikey') !== $secret) {
            return response('Unauthorized', 401);
        }

        try {
            match (config('whatsapp.provider')) {
                'evolution' => $this->processEvolution($request),
                default     => null,
            };
        } catch (\Throwable $e) {
            Log::error('WhatsApp webhook error', ['error' => $e->getMessage()]);
        }

        // Repassa para o n8n (AI agent) de forma assíncrona, sem bloquear a resposta
        $this->forwardToN8n($request);

        return response('', 200);
    }

    private function processEvolution(Request $request): void
    {
        $data  = $request->all();
        $event = $data['event'] ?? '';

        if ($event !== 'messages.upsert') return;

        $key    = $data['data']['key'] ?? [];
        $fromMe = $key['fromMe'] ?? true;
        $jid    = $key['remoteJid'] ?? '';

        if ($fromMe || str_contains($jid, '@g.us')) return;

        $msgData = $data['data']['message'] ?? [];
        $text    = $msgData['conversation']
                ?? $msgData['extendedTextMessage']['text']
                ?? null;

        if (! $text) return;

        $phone = preg_replace('/\D/', '', explode('@', $jid)[0]);

        $this->processMessage($phone, $text);
    }

    /**
     * Localiza o cliente, salva a mensagem (se houver OS) e aciona o bot.
     * O bot trata clientes desconhecidos pedindo CPF/código OS.
     */
    private function processMessage(string $phone, string $text): void
    {
        $cliente = $this->findClienteByPhone($phone)
                ?? $this->findClienteByText($text);
        $ordem   = null;

        if ($cliente) {
            $ordem = Ordem::with('equipamento')
                ->where('cliente_id', $cliente->id)
                ->whereNotIn('status', ['finalizado', 'cancelado'])
                ->latest()
                ->first()
                ?? Ordem::with('equipamento')
                    ->where('cliente_id', $cliente->id)
                    ->latest()
                    ->first();

            if ($ordem) {
                Mensagem::create([
                    'ordem_id' => $ordem->id,
                    'user_id'  => null,
                    'tipo'     => 'cliente',
                    'conteudo' => $text,
                ]);
            }
        } else {
            Log::info('WhatsApp webhook: cliente não encontrado', ['phone' => $phone]);
        }

        // Aprovação/recusa de orçamento, palavras-chave e identificação (CPF/OS) funcionam 24h
        if ($this->bot->tryHandleOrcamento($phone, $text, $cliente)) {
            return;
        }

        if ($this->bot->tryHandleKeyword($phone, $text, $cliente)) {
            return;
        }

        // CPF (11 dígitos) ou código OS (ex: OS00001) sempre processados pelo bot
        if ($this->looksLikeIdentification($text)) {
            try {
                $this->bot->handle($phone, $text, $cliente);
            } catch (\Throwable $e) {
                Log::error('WhatsApp bot error (identificação)', ['error' => $e->getMessage()]);
            }
            return;
        }

        if (! $this->isBusinessHours()) {
            $this->maybeSendOutOfHoursMessage($phone);
            return;
        }

        try {
            $this->bot->handle($phone, $text, $cliente);
        } catch (\Throwable $e) {
            Log::error('WhatsApp bot error', ['error' => $e->getMessage()]);
            // Fallback garantido: responde mesmo se o bot falhar
            $this->whatsapp->send($phone,
                "Olá! 👋 Sou o assistente da *Future Data*.\n\n" .
                "Para te atender, preciso te identificar.\n\n" .
                "Por favor, informe seu *CPF* ou o *código da OS* (ex: OS00001)."
            );
        }
    }

    /**
     * Envia a mensagem de fora do horário apenas UMA VEZ por período fechado.
     * Rastreia em BotSession quando foi enviada; só reenvia se houve expediente desde então.
     */
    private function maybeSendOutOfHoursMessage(string $phone): void
    {
        $session  = BotSession::forPhone($phone);
        $lastSent = $session->context['fora_horario_enviado_em'] ?? null;

        if ($lastSent && ! $this->hasBusinessHoursPassedSince(Carbon::parse($lastSent))) {
            return; // já avisamos neste período fechado, não repetir
        }

        $this->whatsapp->send($phone, $this->outOfHoursMessage());

        $session->transition($session->state, [
            'fora_horario_enviado_em' => now()->setTimezone('America/Sao_Paulo')->toIso8601String(),
        ]);
    }

    /** Verifica se houve pelo menos uma hora de expediente entre $from e agora. */
    private function hasBusinessHoursPassedSince(Carbon $from): bool
    {
        $now     = now()->setTimezone('America/Sao_Paulo');
        $current = $from->copy()->setTimezone('America/Sao_Paulo')->addHour()->startOfHour();

        while ($current->lte($now)) {
            if ($this->isBusinessHoursAt($current)) return true;
            $current->addHour();
        }

        return false;
    }

    /** Verifica se um instante específico está dentro do horário de atendimento. */
    private function isBusinessHoursAt(Carbon $at): bool
    {
        $weekday = $at->dayOfWeek; // 0=Dom, 6=Sáb
        $hour    = $at->hour;

        if ($weekday === 0) return false; // Domingo fechado

        $fechamento = ($weekday === 6) ? 14 : 18; // Sábado fecha às 14h

        return $hour >= 8 && $hour < $fechamento;
    }

    /** Verifica se está dentro do horário de atendimento agora. */
    private function isBusinessHours(): bool
    {
        return $this->isBusinessHoursAt(now()->setTimezone('America/Sao_Paulo'));
    }

    /** Mensagem de fora do horário — meme de madrugada ou aviso padrão. */
    private function outOfHoursMessage(): string
    {
        $hour = now()->setTimezone('America/Sao_Paulo')->hour;

        // Madrugada: 0h às 8h
        if ($hour < 8) {
            return "😴 *O PAI TÁ DORMINDO.*\n\n" .
                   "Mano, que horas são essas?! 💀\n" .
                   "Tô no modo avião, no modo soneca, no modo *nem sonho que trabalho agora.*\n\n" .
                   "⏰ Volta das *8h às 18h* (Seg–Sex) ou *8h às 14h* (Sáb) que aí o pai acorda e te atende!\n\n" .
                   "_Future Data — sua eletrônica em boas mãos (de dia)._ 🛠️";
        }

        // Após expediente ou fim de semana
        return "😴 *Eita... pegou a gente no modo economia de energia!* 😂\n\n" .
               "A equipe da Future Data já foi descansar. \n\n" .
               "⏰ *Horário de atendimento:*\n" .
               "📅 Segunda a sexta: *08h às 18h*\n" .
               "📅 Sábado: *08h às 14h*\n\n" .
               "Deixa sua mensagem aí, assim que o expediente começar, a gente responde. Valeu pela paciência! 🚀";
    }

    /** Repassa o payload bruto para o n8n (agente IA), se configurado. */
    private function forwardToN8n(Request $request): void
    {
        $n8nUrl = config('services.n8n.webhook_url');
        if (! $n8nUrl) return;

        try {
            Http::timeout(5)->post($n8nUrl, $request->all());
        } catch (\Throwable) {
            // silencioso — o n8n é opcional
        }
    }

    /**
     * Detecta CPF (11 dígitos) ou código de OS (OS + números ou só números curtos).
     * Esses padrões são processados 24h independentemente do horário.
     */
    private function looksLikeIdentification(string $text): bool
    {
        $clean = preg_replace('/\D/', '', $text);

        if (strlen($clean) === 11) return true; // CPF

        if (preg_match('/^os\d+$/i', trim($text))) return true; // OS00001

        return false;
    }

    /**
     * Busca cliente pelo CPF ou número de OS enviados no texto.
     * Permite identificação mesmo quando o telefone não está cadastrado.
     */
    private function findClienteByText(string $text): ?Cliente
    {
        $clean   = preg_replace('/\D/', '', $text);
        $trimmed = trim($text);

        // Busca por CPF/CNPJ (11 ou 14 dígitos)
        if (strlen($clean) === 11 || strlen($clean) === 14) {
            $cliente = Cliente::where('cpf_cnpj', $clean)
                ->orWhere('cpf_cnpj', preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $clean))
                ->first();
            if ($cliente) return $cliente;
        }

        // Busca por número de OS (ex: OS202500001 ou só o número)
        if (preg_match('/^os\d+$/i', $trimmed)) {
            $ordem = Ordem::where('numero', strtoupper($trimmed))->first();
            return $ordem ? Cliente::find($ordem->cliente_id) : null;
        }

        // Busca por número sequencial da OS (ex: "1", "42")
        if (ctype_digit($clean) && strlen($clean) <= 6) {
            $ordem = Ordem::whereRaw("numero LIKE ?", ["%{$clean}"])->first();
            return $ordem ? Cliente::find($ordem->cliente_id) : null;
        }

        return null;
    }

    /**
     * Busca cliente por sufixo de telefone para tolerar diferentes formatos
     * armazenados no banco ("(11) 99999-9999", "11999999999", etc.).
     *
     * A Evolution API remove o 9 de números BR de 13 dígitos (55+DDD+9+8d → 55+DDD+8d).
     * Ex.: 5581994821792 → 558194821792. Reinsere o 9 para ampliar a busca.
     */
    private function findClienteByPhone(string $phone): ?Cliente
    {
        $phones = [$phone];

        // Reconstrói a versão com o 9 retirado pela Evolution (55 + DDD de 2 dígitos + 9 + 8 dígitos)
        if (strlen($phone) === 12 && str_starts_with($phone, '55')) {
            $phones[] = substr($phone, 0, 4) . '9' . substr($phone, 4); // 558194821792 → 5581994821792
        }

        $suffixes = [];
        foreach ($phones as $p) {
            $suffixes[] = substr($p, -9);
            $suffixes[] = substr($p, -10);
            $suffixes[] = substr($p, -11);
        }
        $suffixes = array_unique($suffixes);

        return Cliente::where(function ($q) use ($suffixes) {
            foreach ($suffixes as $suffix) {
                $q->orWhere('telefone', 'like', "%{$suffix}");
            }
        })->first();
    }
}
