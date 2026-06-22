<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Mensagem;
use App\Models\Ordem;

/**
 * Chatbot de atendimento para o portal do cliente.
 *
 * Usa IA (Claude) para responder às mensagens do cliente com base no
 * contexto da OS. Silencia se houver atendimento humano recente.
 */
class PortalBotService
{
    public function __construct(private AiChatService $ai) {}

    /**
     * Processa a mensagem do cliente e salva a resposta do bot como mensagem de técnico.
     * Retorna null se o bot está desativado ou há atendimento humano ativo.
     */
    public function handle(int $clienteId, int $ordemId, string $text): ?string
    {
        if (! config('whatsapp.bot_enabled', true)) {
            return null;
        }

        // Não responde se a equipe respondeu nos últimos 10 minutos
        $recentHuman = Mensagem::where('ordem_id', $ordemId)
            ->where('tipo', 'tecnico')
            ->whereNotNull('user_id')
            ->where('created_at', '>=', now()->subMinutes(10))
            ->exists();

        if ($recentHuman) {
            return null;
        }

        $ordem   = Ordem::with(['equipamento', 'mensagens'])->find($ordemId);
        $cliente = Cliente::find($clienteId);

        if (! $ordem || ! $cliente) {
            return null;
        }

        $reply = $this->ai->reply($ordem, $cliente->nome);

        Mensagem::create([
            'ordem_id' => $ordemId,
            'user_id'  => null,
            'tipo'     => 'tecnico',
            'conteudo' => $reply,
        ]);

        return $reply;
    }
}
