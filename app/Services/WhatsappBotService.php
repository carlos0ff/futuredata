<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Mensagem;
use App\Models\Ordem;

/**
 * Bot de atendimento automático via WhatsApp.
 *
 * Responde mensagens dos clientes com um menu de opções baseado no texto recebido.
 * Cada resposta é salva como mensagem de técnico (tipo="tecnico") para aparecer
 * no chat da plataforma interna.
 *
 * Menu de opções:
 *   1 — Ver detalhes da OS (status, equipamento, diagnóstico, valor)
 *   2 — Solicitar atendimento humano
 *   3 — Encerrar conversa
 *   4 — Responder orçamento pendente (só exibido quando status_orcamento="pendente")
 *
 * Qualquer outra mensagem exibe o menu de boas-vindas com o status atual da OS.
 *
 * Dependência: WhatsappService — injeta automaticamente via DI do Laravel.
 */
class WhatsappBotService
{
    public function __construct(private WhatsappService $whatsapp) {}

    /**
     * Processa o texto recebido do cliente e envia a resposta adequada.
     *
     * @param string  $phone   Número do cliente (formato internacional)
     * @param string  $text    Texto digitado pelo cliente
     * @param Cliente $cliente Modelo do cliente identificado pelo número
     * @param Ordem   $ordem   OS ativa (ou mais recente) do cliente
     */
    public function handle(string $phone, string $text, Cliente $cliente, Ordem $ordem): void
    {
        $opt = trim(preg_replace('/\s+/', '', mb_strtolower($text)));

        $reply = match (true) {
            $opt === '1'                                                => $this->respostaStatus($ordem),
            $opt === '2'                                                => $this->respostaEquipe($cliente),
            $opt === '3'                                                => $this->respostaEncerrar($cliente),
            $opt === '4' && $ordem->status_orcamento === 'pendente'    => $this->respostaOrcamento($ordem),
            default                                                     => $this->menuBoas($cliente, $ordem),
        };

        $this->whatsapp->send($phone, $reply);

        Mensagem::create([
            'ordem_id' => $ordem->id,
            'user_id'  => null,
            'tipo'     => 'tecnico',
            'conteudo' => $reply,
        ]);
    }

    // ── Respostas do menu ─────────────────────────────────────────────────────

    /**
     * Menu principal com boas-vindas e status atual da OS.
     * Exibe a opção 4 apenas se houver orçamento pendente.
     */
    private function menuBoas(Cliente $cliente, Ordem $ordem): string
    {
        $nome        = explode(' ', $cliente->nome)[0];
        $statusLabel = Ordem::STATUS[$ordem->status]['label'] ?? $ordem->status;
        $orcPendente = $ordem->status_orcamento === 'pendente';

        $msg  = "Olá, *{$nome}*! 👋 Sou o assistente da *Future Data*.\n\n";
        $msg .= "Sua OS *{$ordem->numero}* está: *{$statusLabel}*\n\n";
        $msg .= "Como posso te ajudar?\n\n";
        $msg .= "1️⃣  Ver detalhes da minha OS\n";
        $msg .= "2️⃣  Falar com nossa equipe\n";

        if ($orcPendente) {
            $msg .= "4️⃣  Responder orçamento\n";
        }

        $msg .= "3️⃣  Encerrar\n\n";
        $msg .= "_Digite o número da opção._";

        return $msg;
    }

    /**
     * Opção 1 — Detalhe completo da OS (equipamento, status, diagnóstico, valor, previsão).
     */
    private function respostaStatus(Ordem $ordem): string
    {
        $statusLabel = Ordem::STATUS[$ordem->status]['label'] ?? $ordem->status;
        $device      = $ordem->equipamento
            ? trim("{$ordem->equipamento->marca} {$ordem->equipamento->modelo}")
            : 'Dispositivo';

        $msg  = "📋 *Detalhes da sua OS*\n";
        $msg .= "────────────────────\n";
        $msg .= "🔢 Número: *{$ordem->numero}*\n";
        $msg .= "📱 Equipamento: *{$device}*\n";
        $msg .= "🔧 Status: *{$statusLabel}*\n";
        $msg .= "📅 Entrada: *{$ordem->created_at->format('d/m/Y')}*\n";

        if ($ordem->previsao_entrega) {
            $msg .= "🗓️ Previsão: *{$ordem->previsao_entrega->format('d/m/Y')}*\n";
        }

        if ($ordem->diagnostico) {
            $msg .= "\n🔬 *Diagnóstico:*\n{$ordem->diagnostico}\n";
        }

        if ($ordem->total > 0) {
            $total = 'R$ ' . number_format($ordem->total, 2, ',', '.');
            $msg .= "\n💰 Valor: *{$total}*\n";
        }

        $msg .= "\n────────────────────\n";
        $msg .= "2️⃣  Falar com equipe\n";
        $msg .= "3️⃣  Encerrar";

        return $msg;
    }

    /**
     * Opção 2 — Avisa que a equipe humana foi notificada com horário de atendimento.
     */
    private function respostaEquipe(Cliente $cliente): string
    {
        $nome = explode(' ', $cliente->nome)[0];

        return "👨‍🔧 Certo, *{$nome}*! Nossa equipe foi notificada e entrará em contato em breve.\n\n"
             . "⏰ Atendimento humano:\n"
             . "Seg–Sex: 9h às 19h\n"
             . "Sábado: 9h às 14h\n\n"
             . "Enquanto isso:\n"
             . "1️⃣  Ver minha OS\n"
             . "3️⃣  Encerrar";
    }

    /**
     * Opção 3 — Mensagem de despedida.
     */
    private function respostaEncerrar(Cliente $cliente): string
    {
        $nome = explode(' ', $cliente->nome)[0];

        return "Obrigado, *{$nome}*! 😊\n\n"
             . "Qualquer dúvida, é só chamar. Até logo! 👋\n\n"
             . "_Future Data — sua eletrônica em boas mãos._";
    }

    /**
     * Opção 4 — Exibe o orçamento detalhado com link para aprovação/recusa no portal.
     * Só disponível quando status_orcamento="pendente".
     */
    private function respostaOrcamento(Ordem $ordem): string
    {
        $total   = 'R$ ' . number_format($ordem->total, 2, ',', '.');
        $servico = $ordem->valor_servico > 0 ? 'R$ ' . number_format($ordem->valor_servico, 2, ',', '.') : null;
        $pecas   = $ordem->valor_pecas   > 0 ? 'R$ ' . number_format($ordem->valor_pecas,   2, ',', '.') : null;

        $msg  = "💰 *Orçamento para aprovação*\n";
        $msg .= "────────────────────\n";

        if ($servico) $msg .= "🔧 Mão de obra: *{$servico}*\n";
        if ($pecas)   $msg .= "🔩 Peças: *{$pecas}*\n";

        if ($ordem->desconto > 0) {
            $desc = 'R$ ' . number_format($ordem->desconto, 2, ',', '.');
            $msg .= "🎁 Desconto: *-{$desc}*\n";
        }

        $msg .= "────────────────────\n";
        $msg .= "💵 *Total: {$total}*\n\n";

        if ($ordem->diagnostico) {
            $msg .= "🔬 *Diagnóstico:*\n{$ordem->diagnostico}\n\n";
        }

        $msg .= "Para aprovar ou recusar, acesse o portal:\n";
        $msg .= url("/portal/{$ordem->token}");

        return $msg;
    }
}
