<?php

namespace App\Services;

use App\Models\BotSession;
use App\Models\Cliente;
use App\Models\Ordem;

/**
 * Bot de atendimento WhatsApp — usa Gemini IA quando configurado,
 * ou cai no BotEngine (menu fixo) como fallback.
 *
 * Fluxo com Gemini:
 *   1. Recupera/cria BotSession para o número.
 *   2. Monta system prompt com dados do cliente e OS.
 *   3. Gemini gera resposta contextualizada.
 *   4. WhatsappService envia via Evolution API.
 *
 * Fluxo sem Gemini (fallback):
 *   1-4 igual, mas BotEngine gera resposta por menu fixo.
 */
class WhatsappBotService
{
    public function __construct(
        private WhatsappService $whatsapp,
        private BotEngine       $engine,
        private GeminiService   $gemini,
    ) {}

    public function handle(string $phone, string $text, ?Cliente $cliente = null): void
    {
        if (! config('whatsapp.bot_enabled', true)) {
            \Log::info('Bot: desativado');
            return;
        }

        $session = BotSession::forPhone($phone);

        if ($cliente && ! $session->cliente_id) {
            $session->update(['cliente_id' => $cliente->id]);
            $session->refresh();
        }

        \Log::info('Bot: gemini configurado?', ['gemini' => $this->gemini->isConfigured()]);

        $reply = $this->gemini->isConfigured()
            ? $this->replyWithGemini($session, $text, $cliente)
            : $this->engine->handle($session, $text, saveReply: true);

        \Log::info('Bot: reply gerado', ['reply' => substr($reply, 0, 80)]);

        $sent = $this->whatsapp->send($phone, $reply);
        \Log::info('Bot: mensagem enviada?', ['sent' => $sent]);
    }

    private function replyWithGemini(BotSession $session, string $text, ?Cliente $cliente): string
    {
        $systemPrompt = $this->buildSystemPrompt($session, $cliente);

        $reply = $this->gemini->chat($text, $systemPrompt);

        // Fallback para o menu fixo se Gemini falhar
        if (! $reply) {
            return $this->engine->handle($session, $text, saveReply: true);
        }

        $session->update(['last_activity' => now()]);

        return $reply;
    }

    private function buildSystemPrompt(BotSession $session, ?Cliente $cliente): string
    {
        $prompt  = "Você é o assistente virtual da *Future Data*, empresa de assistência técnica de eletrônicos.\n";
        $prompt .= "Responda sempre em português, de forma curta, amigável e direta. Use emojis com moderação.\n";
        $prompt .= "Nunca invente informações sobre OS ou valores que não estejam no contexto abaixo.\n\n";

        if (! $cliente) {
            $prompt .= "O cliente ainda não foi identificado. Peça educadamente o CPF ou o código da OS (ex: OS00001) para prosseguir.";
            return $prompt;
        }

        $nome    = $cliente->nome;
        $prompt .= "Cliente identificado: *{$nome}*\n";
        if ($cliente->telefone) $prompt .= "Telefone: {$cliente->telefone}\n";

        $ordem = null;
        if ($session->ordem_id) {
            $ordem = Ordem::with('equipamento')->find($session->ordem_id);
        }

        if (! $ordem) {
            $ordem = Ordem::with('equipamento')
                ->where('cliente_id', $cliente->id)
                ->whereNotIn('status', ['finalizado', 'cancelado'])
                ->latest()
                ->first()
                ?? Ordem::with('equipamento')
                    ->where('cliente_id', $cliente->id)
                    ->latest()
                    ->first();
        }

        if ($ordem) {
            $statusLabel = Ordem::STATUS[$ordem->status]['label'] ?? $ordem->status;
            $device      = $ordem->equipamento
                ? trim("{$ordem->equipamento->marca} {$ordem->equipamento->modelo}")
                : 'Equipamento';

            $prompt .= "\nOS atual: *{$ordem->numero}*\n";
            $prompt .= "Equipamento: {$device}\n";
            $prompt .= "Status: {$statusLabel}\n";
            $prompt .= "Entrada: {$ordem->created_at->format('d/m/Y')}\n";

            if ($ordem->previsao_entrega) {
                $prompt .= "Previsão de entrega: {$ordem->previsao_entrega->format('d/m/Y')}\n";
            }
            if ($ordem->diagnostico) {
                $prompt .= "Diagnóstico: {$ordem->diagnostico}\n";
            }
            if ($ordem->total > 0) {
                $prompt .= "Valor total: R$ " . number_format($ordem->total, 2, ',', '.') . "\n";
            }
            if ($ordem->status_orcamento === 'pendente') {
                $prompt .= "⚠️ Há um orçamento pendente de aprovação.\n";
            }
        } else {
            $prompt .= "Nenhuma OS encontrada para este cliente.\n";
        }

        $prompt .= "\nPode ajudar o cliente com informações sobre a OS, status, previsão e orçamento. ";
        $prompt .= "Para ações como aprovar orçamento, oriente o cliente a acessar o portal ou ligar para a loja.";

        return $prompt;
    }
}
