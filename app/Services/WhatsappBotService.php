<?php

namespace App\Services;

use App\Models\BotSession;
use App\Models\Cliente;

/**
 * Bot de atendimento WhatsApp — delega ao BotEngine e envia a resposta.
 *
 * Fluxo:
 *   1. Recupera ou cria a sessão (BotSession) para o número.
 *   2. Se o cliente é desconhecido (phone sem cliente_id), solicita CPF/código OS.
 *   3. BotEngine processa e retorna o texto de resposta.
 *   4. WhatsappService envia a resposta via Evolution API.
 */
class WhatsappBotService
{
    public function __construct(
        private WhatsappService $whatsapp,
        private BotEngine       $engine,
    ) {}

    /**
     * Processa a mensagem recebida e envia resposta automática.
     *
     * @param string       $phone   Número normalizado (ex: "5511999999999")
     * @param string       $text    Texto digitado pelo cliente
     * @param Cliente|null $cliente Cliente identificado (null se número desconhecido)
     */
    public function handle(string $phone, string $text, ?Cliente $cliente = null): void
    {
        if (! config('whatsapp.bot_enabled', true)) {
            return;
        }

        $session = BotSession::forPhone($phone);

        if ($cliente && ! $session->cliente_id) {
            $session->update(['cliente_id' => $cliente->id]);
            $session->refresh();
        }

        $reply = $this->engine->handle($session, $text, saveReply: true);

        $this->whatsapp->send($phone, $reply);
    }
}
