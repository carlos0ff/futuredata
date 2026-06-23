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
            return;
        }

        $session   = BotSession::forPhone($phone);
        $isNewChat = $session->wasRecentlyCreated || $this->isNewConversationWindow($session);

        if ($cliente && ! $session->cliente_id) {
            $session->update(['cliente_id' => $cliente->id]);
            $session->refresh();
        }

        // Saudação de boas-vindas para novo chat ou retorno após 24h (igual ao WA Business)
        if ($isNewChat) {
            $this->whatsapp->send($phone, $this->greetingMessage($cliente));
        }

        // Verifica se é resposta de autorização de orçamento
        // Usa session->cliente_id ou o $cliente passado diretamente (fallback para telefones normalizados)
        if (($session->cliente_id || $cliente) && $this->handleOrcamentoResposta($session, $text, $cliente)) {
            return;
        }

        $reply = $this->gemini->isConfigured()
            ? $this->replyWithGemini($session, $text, $cliente)
            : $this->engine->handle($session, $text, saveReply: true);

        $this->whatsapp->send($phone, $reply);
    }

    /** True se o cliente não manda mensagem há mais de 24h (nova janela de conversa). */
    private function isNewConversationWindow(BotSession $session): bool
    {
        return $session->last_activity && $session->last_activity->lt(now()->subHours(24));
    }

    /** Mensagem de boas-vindas enviada na abertura de um novo chat. */
    private function greetingMessage(?Cliente $cliente): string
    {
        if ($cliente) {
            $nome = explode(' ', trim($cliente->nome))[0]; // primeiro nome
            return "👋 Olá, *{$nome}*! Bem-vindo de volta à *Future Data*.\n\n" .
                   "Nosso assistente já está aqui para te ajudar. 😊";
        }

        return "👋 Olá! Seja bem-vindo à *Future Data* — assistência técnica de eletrônicos!\n\n" .
               "Nosso assistente virtual está aqui para te ajudar. 😊";
    }

    /** Detecta SIM/NÃO para orçamento pendente e atualiza a OS. */
    private function handleOrcamentoResposta(BotSession $session, string $text, ?Cliente $cliente = null): bool
    {
        $clienteId = $session->cliente_id ?? $cliente?->id;

        if (! $clienteId) return false;

        $ordem = Ordem::where('cliente_id', $clienteId)
            ->where('status_orcamento', 'pendente')
            ->latest()
            ->first();

        if (! $ordem) return false;

        $input = mb_strtolower(trim(preg_replace('/\s+/', '', $text)));
        $sim   = in_array($input, ['sim', 's', 'autorizo', 'autorizar', 'aprovar', 'aprovado', 'ok', 'pode']);
        $nao   = in_array($input, ['nao', 'não', 'n', 'recuso', 'recusar', 'recusado', 'negar', 'negado']);

        if (! $sim && ! $nao) return false;

        $ordem->update(['status_orcamento' => $sim ? 'aprovado' : 'recusado']);

        $reply = $sim
            ? "✅ *Serviço autorizado!*\n\nPerfeito! Nossa equipe já foi notificada e dará andamento ao conserto.\nEntraremos em contato quando estiver pronto. 😊"
            : "❌ *Serviço recusado.*\n\nEntendemos! Sua OS foi marcada como recusada.\nEntre em contato conosco para combinar a devolução do equipamento.\n\n_Future Data — (81) 9482-1792_";

        $this->whatsapp->send($session->phone, $reply);

        return true;
    }

    private function replyWithGemini(BotSession $session, string $text, ?Cliente $cliente): string
    {
        $statusJaEnviado = $session->context['status_enviado'] ?? false;
        $systemPrompt    = $this->buildSystemPrompt($session, $cliente, $statusJaEnviado);

        $reply = $this->gemini->chat($text, $systemPrompt);

        // Fallback para o menu fixo se Gemini falhar
        if (! $reply) {
            return $this->engine->handle($session, $text, saveReply: true);
        }

        // Marca que o status foi enviado ao menos uma vez
        if (! $statusJaEnviado) {
            $session->transition($session->state, ['status_enviado' => true]);
        }

        $session->update(['last_activity' => now()]);

        return $reply;
    }

    private function buildSystemPrompt(BotSession $session, ?Cliente $cliente, bool $statusJaEnviado = false): string
    {
        $prompt  = "Você é o assistente virtual da *Future Data*, empresa de assistência técnica de eletrônicos.\n";
        $prompt .= "Responda sempre em português, de forma curta, amigável e direta. Use emojis com moderação.\n";
        $prompt .= "Nunca invente informações sobre OS ou valores que não estejam no contexto abaixo.\n";

        if ($statusJaEnviado) {
            $prompt .= "IMPORTANTE: O status da OS já foi informado anteriormente nesta conversa. ";
            $prompt .= "NÃO repita o status completo novamente a menos que o cliente peça explicitamente. ";
            $prompt .= "Responda de forma curta e direta à dúvida atual.\n";
        }

        $prompt .= "\n";

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
