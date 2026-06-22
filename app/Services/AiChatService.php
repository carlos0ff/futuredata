<?php

namespace App\Services;

use Anthropic\Client;
use App\Models\Mensagem;
use App\Models\Ordem;

/**
 * Integração com Claude AI para o chatbot do portal.
 *
 * Usa o histórico de mensagens da OS como contexto e responde como
 * assistente virtual da Future Data.
 */
class AiChatService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(apiKey: config('services.anthropic.key', ''));
    }

    /**
     * Gera uma resposta de IA para a conversa atual da OS.
     * A mensagem mais recente do cliente já deve estar salva no banco antes de chamar.
     */
    public function reply(Ordem $ordem, string $clienteNome): string
    {
        $system   = $this->buildSystemPrompt($ordem, $clienteNome);
        $messages = $this->buildHistory($ordem);

        if (empty($messages)) {
            return 'Olá! Como posso te ajudar?';
        }

        try {
            $response = $this->client->messages->create(
                model: 'claude-haiku-4-5',
                maxTokens: 512,
                system: $system,
                messages: $messages,
            );

            foreach ($response->content as $block) {
                if ($block->type === 'text') {
                    return trim($block->text);
                }
            }
        } catch (\Throwable $e) {
            \Log::error('AiChatService error: ' . $e->getMessage());
        }

        return 'Desculpe, tive um problema ao processar sua mensagem. Clique em "Desejo falar com o técnico" para atendimento humano.';
    }

    private function buildSystemPrompt(Ordem $ordem, string $clienteNome): string
    {
        $equip    = $ordem->equipamento;
        $equipDesc = $equip
            ? trim("{$equip->tipo} {$equip->marca} {$equip->modelo}")
            : 'não especificado';

        $diagnostico = $ordem->diagnostico ?? 'ainda em análise pela equipe';
        $orcamento   = $this->orcamentoStatus($ordem);

        return <<<PROMPT
Você é o assistente virtual da **Future Data Assistência Técnica**, chamado "Assistente FD".
Responda sempre em português brasileiro, de forma cordial, objetiva e profissional.
Máximo de 3 parágrafos por resposta. Use quebras de linha para facilitar a leitura.

## Contexto da OS
- Número da OS: {$ordem->numero}
- Status atual: {$ordem->status_label}
- Equipamento: {$equipDesc}
- Diagnóstico: {$diagnostico}
- Orçamento: {$orcamento}
- Cliente: {$clienteNome}

## Regras
1. Use APENAS as informações do contexto acima. Não invente dados.
2. Se o cliente pedir para falar com um técnico, responda que você vai encaminhar e peça para ele clicar no botão **"Desejo falar com o técnico"** que aparece no chat.
3. Se não souber responder, sugira falar com um técnico.
4. Não compartilhe detalhes internos sobre preços ou prazos que não estejam no contexto.
5. Seja empático — o cliente está aguardando o conserto do equipamento dele.
PROMPT;
    }

    private function orcamentoStatus(Ordem $ordem): string
    {
        return match ($ordem->status_orcamento) {
            'pendente'  => 'aguardando aprovação do cliente',
            'aprovado'  => 'aprovado pelo cliente',
            'recusado'  => 'recusado pelo cliente',
            default     => 'sem orçamento pendente',
        };
    }

    /**
     * Monta o histórico de mensagens no formato esperado pela API da Anthropic.
     * Garante alternância user/assistant e que começa com user.
     */
    private function buildHistory(Ordem $ordem): array
    {
        $raw = Mensagem::where('ordem_id', $ordem->id)
            ->orderBy('created_at')
            ->get()
            ->map(fn ($m) => [
                'role'    => $m->tipo === 'cliente' ? 'user' : 'assistant',
                'content' => $m->conteudo,
            ])
            ->toArray();

        // Mescla mensagens consecutivas do mesmo papel (API exige alternância)
        $merged = [];
        foreach ($raw as $msg) {
            $last = end($merged);
            if ($last && $last['role'] === $msg['role']) {
                $merged[count($merged) - 1]['content'] .= "\n\n" . $msg['content'];
            } else {
                $merged[] = $msg;
            }
        }

        // Deve começar com 'user'
        while (!empty($merged) && $merged[0]['role'] !== 'user') {
            array_shift($merged);
        }

        return $merged;
    }
}
