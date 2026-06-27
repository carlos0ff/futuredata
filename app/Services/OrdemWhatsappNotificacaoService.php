<?php

namespace App\Services;

use App\Models\Mensagem;
use App\Models\Ordem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Notificações automáticas de WhatsApp para o cliente sobre o andamento da OS.
 *
 * Disparadores:
 *   1. OS criada                        → notificarEntrada()
 *   2. Status da OS alterado            → notificarStatusAlterado()
 *   3. Orçamento disponível (pendente)  → notificarOrcamentoPendente()
 *   4. Confirmação de aprovação         → notificarOrcamentoAprovado()
 *
 * Todas as mensagens enviadas são registradas no histórico de mensagens da OS.
 * Deduplicação via cache (5 min) por evento + ordem para evitar disparos duplicados.
 */
class OrdemWhatsappNotificacaoService
{
    /** Emoji e resumo exibido ao cliente para cada status da OS. */
    private const STATUS_INFO = [
        'entrada'            => ['emoji' => '📥', 'resumo' => 'O equipamento foi recebido e a OS foi aberta pela nossa equipe.'],
        'analise'            => ['emoji' => '🔍', 'resumo' => 'Nosso técnico está analisando o equipamento para identificar o problema.'],
        'execucao'           => ['emoji' => '🔧', 'resumo' => 'O reparo está em andamento. Nossa equipe já iniciou o serviço.'],
        'aguardando_cliente' => ['emoji' => '⏳', 'resumo' => 'Aguardamos seu retorno ou autorização para prosseguir com o serviço.'],
        'em_teste'           => ['emoji' => '🧪', 'resumo' => 'O equipamento está nos testes finais para garantir que tudo está funcionando.'],
        'finalizado'         => ['emoji' => '✅', 'resumo' => 'O serviço foi concluído! Entre em contato para combinar a retirada do equipamento.'],
        'cancelado'          => ['emoji' => '❌', 'resumo' => 'A OS foi cancelada. Entre em contato conosco para mais informações.'],
    ];

    public function __construct(private WhatsappService $whatsapp) {}

    /**
     * Mensagem de boas-vindas enviada quando a OS é aberta.
     * Informa o número da OS, link do portal e como acessar.
     */
    public function notificarEntrada(Ordem $ordem): void
    {
        $ordem->loadMissing(['cliente', 'equipamento']);
        $cliente = $ordem->cliente;

        if (! $cliente?->telefone) return;
        if ($this->jaEnviadoRecentemente($ordem, 'entrada')) return;

        $nome  = $this->primeiroNome($cliente->nome);
        $link  = route('portal.token', $ordem->token);

        $msg  = "👋 Olá, *{$nome}*!\n\n";
        $msg .= "📥 *Equipamento recebido com sucesso!*\n";
        $msg .= "────────────────────\n";
        $msg .= "📄 OS: *{$ordem->codigo_publico}*\n";
        $msg .= "📱 Equipamento: *{$this->deviceName($ordem)}*\n";
        $msg .= "📍 Status: *Entrada registrada*\n";
        $msg .= "────────────────────\n\n";
        $msg .= "Acompanhe o andamento pelo *Portal do Cliente*:\n";
        $msg .= "🔗 {$link}\n\n";
        $msg .= "Para acessar, use o *código da OS* (_" . $ordem->codigo_publico . "_) ";
        $msg .= "ou o *token de acesso* (_" . $ordem->token . "_).\n\n";
        $msg .= "_Future Data — assistência técnica de eletrônicos_ 🛠️";

        $this->enviar($ordem, $cliente, $msg, 'entrada');
    }

    /**
     * Notificação enviada ao cliente a cada mudança de status da OS.
     * Não é enviada para a mudança inicial de "entrada" (já coberta por notificarEntrada).
     */
    public function notificarStatusAlterado(Ordem $ordem, string $statusNovo): void
    {
        // Entrada já é coberta por notificarEntrada()
        if ($statusNovo === 'entrada') return;

        $ordem->loadMissing(['cliente', 'equipamento']);
        $cliente = $ordem->cliente;

        if (! $cliente?->telefone) return;
        if ($this->jaEnviadoRecentemente($ordem, "status:{$statusNovo}")) return;

        $info  = self::STATUS_INFO[$statusNovo] ?? ['emoji' => '📋', 'resumo' => 'O status da sua OS foi atualizado.'];
        $label = Ordem::STATUS[$statusNovo]['label'] ?? $statusNovo;
        $link  = route('portal.token', $ordem->token);
        $agora = now()->setTimezone('America/Sao_Paulo')->format('d/m/Y \à\s H:i');

        $msg  = "{$info['emoji']} *Atualização — {$ordem->codigo_publico}*\n";
        $msg .= "────────────────────\n";
        $msg .= "👤 *{$this->primeiroNome($cliente->nome)}* | {$this->deviceName($ordem)}\n";
        $msg .= "📊 Novo status: *{$label}*\n";
        $msg .= "🕐 {$agora}\n";
        $msg .= "────────────────────\n\n";
        $msg .= "_{$info['resumo']}_\n\n";
        $msg .= "Acompanhe pelo portal:\n🔗 {$link}";

        $this->enviar($ordem, $cliente, $msg, "status:{$statusNovo}");
    }

    /**
     * Notificação de orçamento pronto, com valor e instruções de aprovação.
     */
    public function notificarOrcamentoPendente(Ordem $ordem): void
    {
        $ordem->loadMissing(['cliente', 'equipamento']);
        $cliente = $ordem->cliente;

        if (! $cliente?->telefone) return;
        if ($this->jaEnviadoRecentemente($ordem, 'orcamento_pendente')) return;

        $link  = route('portal.token', $ordem->token);
        $total = 'R$ ' . number_format($ordem->total, 2, ',', '.');

        $msg  = "💰 *Orçamento disponível — {$ordem->codigo_publico}*\n";
        $msg .= "────────────────────\n";
        $msg .= "👤 *{$this->primeiroNome($cliente->nome)}*, seu orçamento está pronto!\n";
        $msg .= "📱 Equipamento: *{$this->deviceName($ordem)}*\n";
        if ($ordem->diagnostico) {
            $msg .= "🔬 Diagnóstico: {$ordem->diagnostico}\n";
        }
        $msg .= "────────────────────\n";
        if ((float) $ordem->valor_servico > 0) {
            $msg .= "🔧 Mão de obra: *R$ " . number_format((float) $ordem->valor_servico, 2, ',', '.') . "*\n";
        }
        if ((float) $ordem->valor_pecas > 0) {
            $msg .= "🔩 Peças: *R$ " . number_format((float) $ordem->valor_pecas, 2, ',', '.') . "*\n";
        }
        $msg .= "💵 *Total: {$total}*\n";
        $msg .= "────────────────────\n\n";
        $msg .= "Para *AUTORIZAR* o serviço, responda:\n";
        $msg .= "*Aprovar* | *Sim* | *Autorizo* | *Pode fazer*\n\n";
        $msg .= "Para *RECUSAR*, responda:\n";
        $msg .= "*Não* | *Recuso* | *Cancelar*\n\n";
        $msg .= "Ou decida pelo portal:\n🔗 {$link}";

        $this->enviar($ordem, $cliente, $msg, 'orcamento_pendente');
    }

    /**
     * Confirmação enviada ao cliente após aprovação do orçamento.
     * Mensagem definida pela especificação do sistema.
     */
    public function notificarOrcamentoAprovado(Ordem $ordem): void
    {
        $ordem->loadMissing(['cliente']);
        $cliente = $ordem->cliente;

        if (! $cliente?->telefone) return;

        $link = route('portal.token', $ordem->token);

        $msg  = "✅ *Orçamento aprovado com sucesso!*\n\n";
        $msg .= "O reparo do equipamento será iniciado e você continuará ";
        $msg .= "recebendo atualizações pelo WhatsApp e pelo Portal do Cliente.\n\n";
        $msg .= "🔗 {$link}";

        // Confirmação de aprovação não tem deduplicação — deve sempre ser enviada
        if ($this->whatsapp->sendToCliente($cliente, $msg)) {
            $this->registrarMensagem($ordem, $msg);
        }
    }

    // ── Helpers privados ─────────────────────────────────────────────────────

    /** Envia e registra a mensagem; marca evento no cache anti-duplicata. */
    private function enviar(Ordem $ordem, \App\Models\Cliente $cliente, string $msg, string $evento): void
    {
        if ($this->whatsapp->sendToCliente($cliente, $msg)) {
            $this->registrarMensagem($ordem, $msg);
            $this->marcarEnviado($ordem, $evento);
        }
    }

    /** Salva a mensagem automática no histórico de mensagens da OS. */
    private function registrarMensagem(Ordem $ordem, string $conteudo): void
    {
        try {
            Mensagem::create([
                'ordem_id' => $ordem->id,
                'user_id'  => null,
                'tipo'     => 'tecnico',
                'conteudo' => "[WhatsApp automático]\n{$conteudo}",
                'lida_em'  => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('OrdemWhatsappNotificacao: falha ao registrar mensagem', [
                'ordem_id' => $ordem->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    /** True se a notificação deste evento já foi enviada nos últimos 5 minutos. */
    private function jaEnviadoRecentemente(Ordem $ordem, string $evento): bool
    {
        return Cache::has("wa_notif:{$ordem->id}:{$evento}");
    }

    /** Marca o evento como enviado no cache por 5 minutos. */
    private function marcarEnviado(Ordem $ordem, string $evento): void
    {
        Cache::put("wa_notif:{$ordem->id}:{$evento}", true, now()->addMinutes(5));
    }

    /** Primeiro nome do cliente. */
    private function primeiroNome(string $nome): string
    {
        return explode(' ', trim($nome))[0];
    }

    /** "Marca Modelo" ou tipo do equipamento como fallback. */
    private function deviceName(Ordem $ordem): string
    {
        if (! $ordem->equipamento) return 'Equipamento';
        return trim("{$ordem->equipamento->marca} {$ordem->equipamento->modelo}")
            ?: $ordem->equipamento->tipo;
    }
}
