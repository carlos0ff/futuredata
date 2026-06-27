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
use Illuminate\Support\Facades\Cache;
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

        // Ignora mensagens enviadas pelo próprio bot ou de grupos
        if ($fromMe || str_contains($jid, '@g.us')) return;

        // Deduplicação: evita processar o mesmo message ID duas vezes (webhook duplicado)
        $msgId = $key['id'] ?? null;
        if ($msgId) {
            $cacheKey = 'whatsapp_msg_processed:' . $msgId;
            if (Cache::has($cacheKey)) {
                Log::debug('WhatsApp webhook: mensagem duplicada ignorada', ['msg_id' => $msgId]);
                return;
            }
            Cache::put($cacheKey, true, now()->addMinutes(10));
        }

        $msgData = $data['data']['message'] ?? [];
        $text    = $msgData['conversation']
                ?? $msgData['extendedTextMessage']['text']
                ?? null;

        if (! $text) return;

        // WhatsApp usa @lid (Linked Device ID) em vez do número real para novos contatos.
        // Mensagens endereçadas com @lid entregam com sucesso; via @s.whatsapp.net retornam ERROR.
        // Para @lid: usa JID completo como replyTo + número real (remoteJidAlt) para busca no banco.
        // Para @s.whatsapp.net: extrai o número limpo como antes (comportamento original).
        $isLid    = str_ends_with($jid, '@lid');
        $altJid   = $key['remoteJidAlt'] ?? null;
        $pushName = $data['data']['pushName'] ?? null;

        if ($isLid) {
            // Endereço de resposta = JID completo do LID (≤ 20 chars — cabe em BotSession.phone)
            $replyTo     = $jid;
            $lookupPhone = $altJid
                ? preg_replace('/\D/', '', explode('@', $altJid)[0])
                : preg_replace('/\D/', '', explode('@', $jid)[0]);
        } else {
            // Comportamento original: usa só os dígitos do número
            $replyTo     = preg_replace('/\D/', '', explode('@', $jid)[0]);
            $lookupPhone = $replyTo;
        }

        // Delay de 3 segundos antes de responder (evita resposta imediata e possíveis loops)
        sleep(3);

        $this->processMessage($replyTo, $text, $pushName, $lookupPhone);
    }

    /**
     * Localiza o cliente, salva a mensagem (se houver OS) e aciona o bot.
     * O bot trata clientes desconhecidos pedindo CPF/código OS.
     *
     * @param string      $replyTo     JID completo para resposta (pode ser @lid ou @s.whatsapp.net)
     * @param string|null $lookupPhone Número real para busca no banco (sem @domínio)
     */
    private function processMessage(string $replyTo, string $text, ?string $pushName = null, ?string $lookupPhone = null): void
    {
        if (! config('whatsapp.bot_enabled', true)) {
            return;
        }

        // Número usado para busca no banco (real) vs JID para resposta (LID ou phone)
        $phone = $lookupPhone ?? preg_replace('/\D/', '', explode('@', $replyTo)[0]);

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
                try {
                    Mensagem::create([
                        'ordem_id' => $ordem->id,
                        'user_id'  => null,
                        'tipo'     => 'cliente',
                        'conteudo' => $text,
                    ]);
                } catch (\Throwable $e) {
                    Log::warning('WhatsApp: falha ao salvar mensagem na OS', [
                        'ordem_id' => $ordem->id,
                        'error'    => $e->getMessage(),
                    ]);
                }
            }
        } else {
            Log::info('WhatsApp webhook: cliente não encontrado', ['phone' => $phone]);
        }

        // Captura identificação de recrutador (24h — responde independente do horário)
        if ($this->handleRhIdentificacao($replyTo, $text, $pushName)) {
            return;
        }

        // Aprovação/recusa de orçamento, palavras-chave e identificação (CPF/OS) funcionam 24h
        try {
            if ($this->bot->tryHandleOrcamento($replyTo, $text, $cliente)) {
                return;
            }
        } catch (\Throwable $e) {
            Log::warning('WhatsApp: falha no check de orçamento (coluna ausente?)', [
                'error' => $e->getMessage(),
            ]);
        }

        try {
            if ($this->bot->tryHandleKeyword($replyTo, $text, $cliente)) {
                return;
            }
        } catch (\Throwable $e) {
            Log::warning('WhatsApp: falha no check de keyword', ['error' => $e->getMessage()]);
        }

        // CPF (11 dígitos) ou código OS (ex: OS202600008) sempre processados pelo bot
        if ($this->looksLikeIdentification($text)) {
            if (! $cliente) {
                // CPF/OS não encontrado no banco — avisa o cliente
                $this->whatsapp->send($replyTo,
                    "😕 Não encontrei nenhum cadastro com esse CPF ou código de OS.\n\n" .
                    "Verifique os dados e tente novamente, ou entre em contato conosco:\n" .
                    "_Future Data — (81) 9482-1792_"
                );
                return;
            }
            try {
                $this->bot->handle($replyTo, $text, $cliente);
            } catch (\Throwable $e) {
                Log::error('WhatsApp bot error (identificação)', ['error' => $e->getMessage()]);
            }
            return;
        }

        if (! $this->isBusinessHours()) {
            $this->maybeSendOutOfHoursMessage($replyTo, $text, $pushName);
            return;
        }

        try {
            $this->bot->handle($replyTo, $text, $cliente);
        } catch (\Throwable $e) {
            Log::error('WhatsApp bot error', ['error' => $e->getMessage()]);
            // Fallback garantido: responde mesmo se o bot falhar
            $this->whatsapp->send($replyTo,
                "Olá! 👋 Sou o assistente da *Future Data*.\n\n" .
                "Para te atender, preciso te identificar.\n\n" .
                "Por favor, informe seu *CPF* ou o *código da OS* (ex: OS00001)."
            );
        }
    }

    /**
     * Envia a mensagem de fora do horário apenas UMA VEZ por período fechado.
     * Contatos de RH/recrutamento sempre recebem a mensagem profissional, ignorando o bloqueio.
     */
    private function maybeSendOutOfHoursMessage(string $phone, string $text = '', ?string $pushName = null): void
    {
        // Contato de RH/recrutamento — responde sempre com mensagem profissional
        if ($this->looksLikeRecruitment($text)) {
            $session = BotSession::forPhone($phone);
            $empresa = $this->fetchBizName($phone);
            $nome    = $pushName;

            // Se já temos nome e empresa (WhatsApp Business), confirma direto sem pedir
            if ($nome && $empresa) {
                $this->whatsapp->send($phone,
                    "📨 Eita, chegou recrutador na área! 👀\n\n" .
                    "✅ Anotado! *{$nome}* da *{$empresa}* — recado registrado com prioridade. Carlos responde assim que possível. 🚀"
                );
                return;
            }

            // Não tem empresa identificada — pede nome e empresa
            $session->transition($session->state, ['rh_aguardando_identificacao' => true]);
            $this->whatsapp->send($phone, $this->rhMessage());
            return;
        }

        $session  = BotSession::forPhone($phone);
        $lastSent = $session->context['fora_horario_enviado_em'] ?? null;

        if ($lastSent && ! $this->hasBusinessHoursPassedSince(Carbon::parse($lastSent))) {
            // Já enviou a mensagem fora do horário neste período — silencia as demais
            return;
        }

        $this->whatsapp->send($phone, $this->outOfHoursMessage());

        $session->transition($session->state, [
            'fora_horario_enviado_em' => now()->setTimezone('America/Sao_Paulo')->toIso8601String(),
        ]);
    }

    /**
     * Captura o nome e empresa do recrutador quando o bot está aguardando identificação.
     * Retorna true se processou a resposta, false se não havia nada pendente.
     */
    private function handleRhIdentificacao(string $phone, string $text, ?string $pushName = null): bool
    {
        $session = BotSession::forPhone($phone);

        if (! ($session->context['rh_aguardando_identificacao'] ?? false)) {
            return false;
        }

        $session->transition($session->state, ['rh_aguardando_identificacao' => false]);

        // Usa o nome do perfil WhatsApp se disponível, senão usa o texto enviado
        $nome    = $pushName ?: $text;
        $empresa = $this->fetchBizName($phone);

        $confirmacao = $empresa
            ? "✅ Anotado! *{$nome}* da *{$empresa}* — recado registrado com prioridade. Carlos responde assim que possível. 🚀"
            : "✅ Anotado! *{$nome}* — recado registrado com prioridade. Carlos responde assim que possível. 🚀";

        $this->whatsapp->send($phone, $confirmacao);

        return true;
    }

    /**
     * Busca o nome da empresa do contato via Evolution API (WhatsApp Business).
     * Retorna null se não for conta Business ou se a API falhar.
     */
    private function fetchBizName(string $phone): ?string
    {
        $cfg = config('whatsapp.evolution');

        if (empty($cfg['url']) || empty($cfg['key'])) return null;

        try {
            $res = Http::timeout(5)
                ->withHeaders(['apikey' => $cfg['key']])
                ->post("{$cfg['url']}/chat/fetchProfile/{$cfg['instance']}", [
                    'number' => $phone,
                ]);

            if (! $res->successful()) return null;

            $body = $res->json();

            return $body['isBusiness'] ?? false
                ? ($body['bizName'] ?? $body['verifiedName'] ?? null)
                : null;
        } catch (\Throwable) {
            return null;
        }
    }

    /** Detecta mensagens de recrutadores / RH. */
    private function looksLikeRecruitment(string $text): bool
    {
        $input = mb_strtolower($text, 'UTF-8');

        $keywords = ['vaga', 'currículo', 'curriculo', 'recrutamento', 'recrutador',
                     ' rh ', 'seleção', 'selecao', 'entrevista', 'oportunidade',
                     'processo seletivo', 'candidatura', 'candidatei'];

        foreach ($keywords as $kw) {
            if (str_contains($input, $kw)) return true;
        }

        return false;
    }

    /** Mensagem para contatos de RH/recrutamento. */
    private function rhMessage(): string
    {
        return "📨 Eita, chegou recrutador na área! 👀\n\n" .
               "Se você é do RH e veio falar sobre uma vaga pra qual me candidatei, pode mandar tudo que eu trato com prioridade máxima! 🚀\n\n" .
               "Me informa seu *nome* e o *nome da empresa* pra eu deixar tudo certinho anotado. 😄";
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
        return true; // TESTE: sempre em horário comercial
    }

    /** Mensagem de fora do horário — alterna aleatoriamente entre as opções. */
    private function outOfHoursMessage(): string
    {
        $ownerName = config('whatsapp.evolution.owner_name', 'CARL0$');

        $message = "Opa! Eu sou o *Caduco*, assistente virtual dele. No momento, ele está temporariamente indisponível.\n\n" . "Sua mensagem foi registrada com sucesso e será respondida assim que possível.";

        return $message;
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
     * Tolera CPF formatado (123.456.789-00) ou só dígitos (12345678900).
     */
    private function findClienteByText(string $text): ?Cliente
    {
        try {
            $digits = preg_replace('/\D/', '', $text);
            $upper  = strtoupper(trim($text));

            // CPF (11 dígitos) ou CNPJ (14 dígitos)
            if (strlen($digits) === 11 || strlen($digits) === 14) {
                // Busca exata (sem formatação) ou formatada com pontos e traço
                $formatted = strlen($digits) === 11 ? substr($digits, 0, 3) . '.' . substr($digits, 3, 3) . '.' . substr($digits, 6, 3) . '-' . substr($digits, 9, 2)
                    : substr($digits, 0, 2) . '.' . substr($digits, 2, 3) . '.' . substr($digits, 5, 3) . '/' . substr($digits, 8, 4) . '-' . substr($digits, 12, 2);

                return Cliente::where('cpf_cnpj', $digits)->orWhere('cpf_cnpj', $formatted)->first();
            }

            // Número de OS no formato do sistema: OS202600008
            if (preg_match('/^OS\d+$/', $upper)) {
                $ordem = Ordem::where('numero', $upper)->first();
                if ($ordem) {
                    return Cliente::find($ordem->cliente_id);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('findClienteByText error', ['text' => $text, 'error' => $e->getMessage()]);
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
