<?php

namespace App\Services;

use App\Models\BotSession;
use App\Models\Cliente;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Serviço de envio de mensagens WhatsApp.
 *
 * Suporta provedor: Evolution API (open-source, auto-hospedado).
 * Configuração via config/whatsapp.php e variáveis de ambiente:
 *   WHATSAPP_PROVIDER=evolution
 *   WHATSAPP_EVOLUTION_URL=https://evo.seudominio.com
 *   WHATSAPP_EVOLUTION_KEY=sua-api-key
 *   WHATSAPP_EVOLUTION_INSTANCE=futuredata
 *
 * Se a chave não estiver configurada, `send()` retorna false silenciosamente
 * (modo sem WhatsApp — não quebra o fluxo da aplicação).
 */
class WhatsappService
{
    /**
     * Envia uma mensagem de texto para o número informado.
     *
     * @param  string $phone  Número em qualquer formato (ex.: "(11) 99999-9999", "11999999999")
     * @param  string $text   Texto da mensagem (suporta formatação WhatsApp: *negrito*, _itálico_)
     * @return bool           True se enviado com sucesso, false caso contrário
     */
    public function send(string $phone, string $text): bool
    {
        if (empty(config('whatsapp.' . config('whatsapp.provider') . '.key'))) {
            return false;
        }

        try {
            return match (config('whatsapp.provider')) {
                'evolution' => $this->sendEvolution($phone, $text),
                default     => false,
            };
        } catch (\Throwable $e) {
            Log::error('WhatsApp: falha ao enviar', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Envia mensagem para um cliente, preferindo o JID LID armazenado na sessão.
     * Quando o cliente enviou mensagens antes, o LID está salvo em BotSession.phone.
     * Envios via LID entregam com sucesso; via @s.whatsapp.net retornam ERROR.
     */
    public function sendToCliente(Cliente $cliente, string $text): bool
    {
        $lidJid = BotSession::where('channel', 'whatsapp')
            ->where('cliente_id', $cliente->id)
            ->where('phone', 'like', '%@lid')
            ->latest('last_activity')
            ->value('phone');

        return $this->send($lidJid ?? $cliente->telefone, $text);
    }

    /**
     * Normaliza o número para o formato internacional (DDI 55 + número).
     * Remove caracteres não numéricos e adiciona "55" se necessário.
     *
     * Exemplos:
     *   "(11) 99999-9999" → "5511999999999"
     *   "11999999999"     → "5511999999999"
     *   "5511999999999"   → "5511999999999" (já normalizado)
     */
    public function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (strlen($digits) <= 11) {
            $digits = '55' . $digits;
        }

        return $digits;
    }

    /**
     * Envia via Evolution API usando o endpoint sendText.
     * Aceita número puro ("5581999999999") ou JID completo ("xxxx@lid", "xxxx@s.whatsapp.net").
     * JIDs @lid são passados diretamente — entrega via Linked Device ID funciona de forma confiável.
     */
    private function sendEvolution(string $phone, string $text): bool
    {
        $cfg = config('whatsapp.evolution');

        // JID completo (contém @): usa diretamente sem normalizar
        $number = str_contains($phone, '@') ? $phone : $this->normalizePhone($phone);

        $response = Http::withHeaders(['apikey' => $cfg['key']])
            ->post("{$cfg['url']}/message/sendText/{$cfg['instance']}", [
                'number' => $number,
                'text'   => $text,
            ]);

        if (! $response->successful()) {
            Log::warning('WhatsApp Evolution: resposta inesperada', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        }

        return $response->successful();
    }
}
