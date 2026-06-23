<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Mensagem;
use App\Models\Ordem;
use App\Services\WhatsappBotService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    public function __construct(private WhatsappBotService $bot) {}

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
        $cliente = $this->findClienteByPhone($phone);
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

        // Se n8n está configurado, ele cuida da resposta automática via IA
        if (! config('services.n8n.webhook_url')) {
            $this->bot->handle($phone, $text, $cliente);
        }
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
     * Busca cliente por sufixo de telefone para tolerar diferentes formatos
     * armazenados no banco ("(11) 99999-9999", "11999999999", etc.).
     */
    private function findClienteByPhone(string $phone): ?Cliente
    {
        $s9  = substr($phone, -9);
        $s10 = substr($phone, -10);
        $s11 = substr($phone, -11);

        return Cliente::where(function ($q) use ($s9, $s10, $s11) {
            $q->where('telefone', 'like', "%{$s9}")
              ->orWhere('telefone', 'like', "%{$s10}")
              ->orWhere('telefone', 'like', "%{$s11}");
        })->first();
    }
}
