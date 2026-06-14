<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Mensagem;
use App\Models\Ordem;
use App\Services\WhatsappBotService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * Recebe webhooks do WhatsApp (Evolution API) e processa mensagens recebidas.
 *
 * Fluxo de uma mensagem recebida:
 *   1. Valida o secret via header `apikey` (opcional, recomendado em produção).
 *   2. Filtra apenas eventos `messages.upsert` que NÃO sejam do próprio número
 *      e NÃO sejam de grupos (@g.us).
 *   3. Extrai o número do JID ("5511999999999@s.whatsapp.net" → "5511999999999").
 *   4. Busca o cliente por sufixo de telefone (últimos 9/10/11 dígitos).
 *   5. Associa à OS mais recente e ativa do cliente.
 *   6. Salva a mensagem com tipo="cliente".
 *   7. Aciona o WhatsappBotService para resposta automática.
 *
 * CSRF excluído em bootstrap/app.php:
 *   $middleware->validateCsrfTokens(except: ['webhook/whatsapp'])
 *
 * Rota:
 * - POST /webhook/whatsapp → receive()
 */
class WhatsappController extends Controller
{
    public function __construct(private WhatsappBotService $bot) {}

    /**
     * Ponto de entrada do webhook — retorna sempre 200 para o provedor.
     */
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

        return response('', 200);
    }

    /**
     * Processa o payload da Evolution API.
     * Ignora: mensagens próprias (fromMe), grupos (@g.us), eventos não-texto.
     */
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

        // "5511999999999@s.whatsapp.net" → "5511999999999"
        $phone = preg_replace('/\D/', '', explode('@', $jid)[0]);

        $this->saveMessage($phone, $text);
    }

    /**
     * Localiza o cliente pelo telefone (match por sufixo), associa à OS ativa,
     * salva a mensagem e aciona o bot para resposta automática.
     *
     * A busca por sufixo tolera diferentes formatos armazenados no banco
     * ("(11) 99999-9999", "11999999999", "+5511999999999", etc.).
     */
    private function saveMessage(string $phone, string $text): void
    {
        $s9  = substr($phone, -9);
        $s10 = substr($phone, -10);
        $s11 = substr($phone, -11);

        $cliente = Cliente::where(function ($q) use ($s9, $s10, $s11) {
            $q->where('telefone', 'like', "%{$s9}")
              ->orWhere('telefone', 'like', "%{$s10}")
              ->orWhere('telefone', 'like', "%{$s11}");
        })->first();

        if (! $cliente) {
            Log::info('WhatsApp webhook: cliente não encontrado', ['phone' => $phone]);
            return;
        }

        // Prioriza OS ativa; fallback para a OS mais recente
        $ordem = Ordem::with(['equipamento'])
            ->where('cliente_id', $cliente->id)
            ->whereNotIn('status', ['finalizado', 'cancelado'])
            ->latest()
            ->first()
            ?? Ordem::with(['equipamento'])
                ->where('cliente_id', $cliente->id)
                ->latest()
                ->first();

        if (! $ordem) {
            Log::info('WhatsApp webhook: nenhuma OS para cliente', ['cliente_id' => $cliente->id]);
            return;
        }

        Mensagem::create([
            'ordem_id' => $ordem->id,
            'user_id'  => null,
            'tipo'     => 'cliente',
            'conteudo' => $text,
        ]);

        $this->bot->handle($phone, $text, $cliente, $ordem);
    }
}
