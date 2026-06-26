<?php

namespace App\Services;

use App\Models\BotSession;
use App\Models\Cliente;
use App\Models\Ordem;

/**
 * Bot de atendimento WhatsApp — usa Gemini IA quando configurado,
 * ou cai no BotEngine (menu fixo) como fallback.
 */
class WhatsappBotService
{
    public function __construct(
        private WhatsappService $whatsapp,
        private BotEngine       $engine,
        private GeminiService   $gemini,
    ) {}

    /**
     * Verifica e processa resposta de orçamento (SIM/NÃO) independentemente do horário.
     * Retorna true se era uma resposta de orçamento e foi tratada.
     */
    public function tryHandleOrcamento(string $phone, string $text, ?Cliente $cliente = null): bool
    {
        $session = BotSession::forPhone($phone);

        if ($cliente && $session->cliente_id !== $cliente->id) {
            $session->update(['cliente_id' => $cliente->id, 'ordem_id' => null]);
            $session->refresh();
        }

        if (! ($session->cliente_id || $cliente)) return false;

        return $this->handleOrcamentoResposta($session, $text, $cliente);
    }

    /**
     * Detecta palavras-chave "orçamento" e "laudo técnico" e responde com dados da OS.
     * Funciona 24h, independentemente do horário comercial.
     * Retorna true se a palavra-chave foi detectada (mesmo sem OS, para não cair no check de horário).
     */
    public function tryHandleKeyword(string $phone, string $text, ?Cliente $cliente = null): bool
    {
        $input = mb_strtolower(trim($text), 'UTF-8');

        $isOrcamento = str_contains($input, 'orçamento')
                    || str_contains($input, 'orcamento')
                    || str_contains($input, 'valor')
                    || str_contains($input, 'preço')
                    || str_contains($input, 'preco')
                    || str_contains($input, 'custo');

        $isLaudo = str_contains($input, 'laudo')
                || str_contains($input, 'diagnóstico') // diagnóstico
                || str_contains($input, 'diagnostico')
                || str_contains($input, 'defeito')
                || str_contains($input, 'problema');

        if (! $isOrcamento && ! $isLaudo) return false;

        $keyword = $isOrcamento ? 'orçamento' : 'laudo técnico';

        // Keyword detectada mas cliente não identificado — pede identificação e salva intenção
        if (! $cliente) {
            $session = BotSession::forPhone($phone);
            $session->transition($session->state, [
                'keyword_pendente' => $isOrcamento ? 'orcamento' : 'laudo',
            ]);
            $this->whatsapp->send($phone,
                "Para consultar o *{$keyword}*, preciso te identificar primeiro. 🔍\n\n" .
                "Por favor, informe seu *CPF* ou o *código da OS* (ex: OS00001). 😊"
            );
            return true;
        }

        $ordem = Ordem::with('equipamento')
            ->where('cliente_id', $cliente->id)
            ->whereNotIn('status', ['cancelado'])
            ->latest()
            ->first();

        if (! $ordem) {
            $nome = explode(' ', trim($cliente->nome))[0];
            $this->whatsapp->send($phone,
                "Olá, *{$nome}*! Não encontrei nenhuma OS ativa para sua conta. 🔎\n\n" .
                "Entre em contato conosco para mais informações:\n" .
                "_Future Data — (81) 9482-1792_"
            );
            return true;
        }

        if ($isOrcamento) {
            $this->whatsapp->send($phone, $this->buildOrcamentoMessage($ordem));
        } else {
            $this->whatsapp->send($phone, $this->buildLaudoMessage($ordem));
        }

        return true;
    }

    private function buildOrcamentoMessage(Ordem $ordem): string
    {
        $device = $ordem->equipamento
            ? trim("{$ordem->equipamento->marca} {$ordem->equipamento->modelo}")
            : 'Equipamento';

        $msg = "💰 *Orçamento — OS {$ordem->numero}*\n";
        $msg .= "📱 {$device}\n\n";

        if ($ordem->total > 0) {
            $msg .= "💵 *Valor:* R$ " . number_format($ordem->total, 2, ',', '.') . "\n";
        } else {
            $msg .= "💵 *Valor:* A definir\n";
        }

        $statusOrc = match ($ordem->status_orcamento) {
            'pendente'  => '⏳ Aguardando sua aprovação',
            'aprovado'  => '✅ Aprovado',
            'recusado'  => '❌ Recusado',
            default     => '—',
        };
        $msg .= "📋 *Status:* {$statusOrc}\n";

        if ($ordem->status_orcamento === 'pendente') {
            $msg .= "\nPara aprovar, responda *SIM*.\nPara recusar, responda *NÃO*.";
        }

        return $msg;
    }

    private function buildLaudoMessage(Ordem $ordem): string
    {
        $device = $ordem->equipamento
            ? trim("{$ordem->equipamento->marca} {$ordem->equipamento->modelo}")
            : 'Equipamento';

        $msg = "🔧 *Laudo Técnico — OS {$ordem->numero}*\n";
        $msg .= "📱 {$device}\n\n";

        if ($ordem->diagnostico) {
            $msg .= "📝 *Diagnóstico:*\n{$ordem->diagnostico}\n";
        } else {
            $msg .= "📝 *Diagnóstico:* Em análise\n";
        }

        $statusLabel = Ordem::STATUS[$ordem->status]['label'] ?? $ordem->status;
        $msg .= "\n📊 *Status atual:* {$statusLabel}";

        if ($ordem->previsao_entrega) {
            $msg .= "\n📅 *Previsão de entrega:* " . $ordem->previsao_entrega->format('d/m/Y');
        }

        return $msg;
    }

    public function handle(string $phone, string $text, ?Cliente $cliente = null): void
    {
        if (! config('whatsapp.bot_enabled', true)) {
            return;
        }

        $session   = BotSession::forPhone($phone);
        $isNewChat = $session->wasRecentlyCreated || $this->isNewConversationWindow($session);

        if ($cliente && $session->cliente_id !== $cliente->id) {
            // Cliente identificado pelo telefone é diferente do que está na sessão —
            // atualiza e limpa a OS vinculada para evitar mostrar dados de outro cliente
            $session->update(['cliente_id' => $cliente->id, 'ordem_id' => null]);
            $session->refresh();
        }

        // Saudação de boas-vindas para novo chat ou retorno após 24h (igual ao WA Business)
        if ($isNewChat) {
            $this->whatsapp->send($phone, $this->greetingMessage($cliente));
        }

        // Se o cliente acabou de se identificar e havia keyword pendente (orçamento/laudo), despacha direto
        $keywordPendente = $session->context['keyword_pendente'] ?? null;
        if ($keywordPendente && $cliente) {
            $session->transition($session->state, ['keyword_pendente' => null]);
            $ordem = Ordem::with('equipamento')
                ->where('cliente_id', $cliente->id)
                ->whereNotIn('status', ['cancelado'])
                ->latest()->first();
            if ($ordem) {
                $msg = ($keywordPendente === 'orcamento')
                    ? $this->buildOrcamentoMessage($ordem)
                    : $this->buildLaudoMessage($ordem);
                $this->whatsapp->send($phone, $msg);
                return;
            }
        }

        // Verifica se é resposta de autorização de orçamento
        if (($session->cliente_id || $cliente) && $this->handleOrcamentoResposta($session, $text, $cliente)) {
            return;
        }

        // Comandos de menu (0–3), palavras de encerramento e códigos OS sempre vão
        // pelo engine — o Gemini não conhece o contexto do menu estruturado.
        $cmd = trim(preg_replace('/\s+/', ' ', mb_strtolower($text, 'UTF-8')));
        $isMenuCommand = in_array($cmd, ['0', '1', '2', '3', 'encerrar', 'tchau', 'sair', 'fim', 'bye', 'cancelar', 'trocar', 'outra'])
            || preg_match('/^os\d+$/i', trim($text));

        $reply = ($isMenuCommand || ! $this->gemini->isConfigured())
            ? $this->engine->handle($session, $text, saveReply: true)
            : $this->replyWithGemini($session, $text, $cliente);

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

        if (! $reply) {
            return $this->engine->handle($session, $text, saveReply: true);
        }

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
