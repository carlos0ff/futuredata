<?php

namespace App\Services;

use App\Models\BotSession;
use App\Models\Cliente;
use App\Models\Mensagem;
use App\Models\Ordem;

/**
 * Motor do chatbot de atendimento — compartilhado entre WhatsApp e portal.
 *
 * Implementa uma máquina de estados simples. O caller (WhatsappBotService ou
 * PortalBotService) fornece o texto recebido e recebe de volta o texto de resposta.
 * A persistência de estado fica em BotSession.
 *
 * Estados:
 *   idle            → exibe menu principal
 *   menu            → aguardando opção numérica
 *   waiting_os      → cliente tem múltiplas OS, aguardando escolha
 *   waiting_cpf     → número desconhecido, aguardando CPF ou código da OS
 *   human_requested → cliente pediu equipe humana
 *
 * Opções do menu principal:
 *   1 — Ver detalhes da OS
 *   2 — Falar com equipe
 *   3 — Responder orçamento (só se status_orcamento = pendente)
 *   0 — Encerrar
 */
class BotEngine
{
    /**
     * Processa o texto recebido e retorna a resposta.
     * Salva a resposta como mensagem tipo "tecnico" se $saveReply = true.
     *
     * @return string  Texto da resposta do bot.
     */
    public function handle(BotSession $session, string $text, bool $saveReply = true): string
    {
        if ($session->isExpired()) {
            $session->reset();
        }

        $session->update(['last_activity' => now()]);

        $reply = match ($session->state) {
            'waiting_cpf'    => $this->handleWaitingCpf($session, $text),
            'waiting_os'     => $this->handleWaitingOs($session, $text),
            default          => $this->handleMenu($session, $text),
        };

        if ($saveReply && $session->ordem_id) {
            Mensagem::create([
                'ordem_id' => $session->ordem_id,
                'user_id'  => null,
                'tipo'     => 'tecnico',
                'conteudo' => $reply,
            ]);
        }

        return $reply;
    }

    // ── Handlers por estado ──────────────────────────────────────────────────

    /** Estado menu/idle — responde às opções numeradas. */
    private function handleMenu(BotSession $session, string $text): string
    {
        $opt = $this->normalize($text);

        // "0" e palavras de encerramento fecham imediatamente — mesmo sem OS vinculada
        if ($opt === '0' || in_array($opt, ['encerrar', 'tchau', 'sair', 'fim', 'bye'])) {
            $cliente = $session->cliente_id ? Cliente::find($session->cliente_id) : null;
            $session->reset();
            if ($cliente) {
                $nome = explode(' ', $cliente->nome)[0];
                return "Obrigado, *{$nome}*! 😊\n\nQualquer dúvida, é só chamar. Até logo! 👋\n\n_Future Data — sua eletrônica em boas mãos._";
            }
            return "Atendimento encerrado. Até logo! 👋\n\n_Future Data — sua eletrônica em boas mãos._";
        }

        if (! $session->cliente_id) {
            $session->transition('waiting_cpf');
            return $this->promptCpf();
        }

        $cliente = Cliente::find($session->cliente_id);

        if (! $session->ordem_id) {
            return $this->resolveOrdem($session, $cliente);
        }

        $ordem = Ordem::with(['equipamento', 'cliente'])->find($session->ordem_id);

        if (! $ordem) {
            $session->reset();
            return $this->promptCpf();
        }

        // Detecta código de OS digitado no menu — troca para aquela OS (somente do cliente atual)
        $upperText = strtoupper(trim($text));
        if (preg_match('/^OS\d+$/i', $upperText)) {
            $outraOrdem = Ordem::with('equipamento')
                ->where('cliente_id', $session->cliente_id)
                ->where(fn ($q) => $q->where('codigo_publico', $upperText)->orWhere('numero', $upperText))
                ->first();

            if ($outraOrdem) {
                $session->transition('menu');
                $session->update(['ordem_id' => $outraOrdem->id]);
                return $this->menuPrincipal($cliente, $outraOrdem);
            }

            return "🔒 OS *{$upperText}* não encontrada ou não pertence à sua conta.\n\n"
                . $this->menuPrincipal($cliente, $ordem);
        }

        return match (true) {
            $opt === '1'                                             => $this->replyDetalhes($session, $ordem),
            $opt === '2'                                             => $this->replyEquipe($session, $cliente),
            $opt === '3' && $ordem->status_orcamento === 'pendente' => $this->replyOrcamento($session, $ordem),
            $opt === 'trocar' || $opt === 'outra'                   => $this->resolveOrdem($session, $cliente, force: true),
            default                                                   => $this->menuPrincipal($cliente, $ordem),
        };
    }

    /** Estado waiting_cpf — cliente desconhecido, aguardando CPF ou código OS. */
    private function handleWaitingCpf(BotSession $session, string $text): string
    {
        $opt = $this->normalize($text);

        if ($opt === '0' || in_array($opt, ['encerrar', 'tchau', 'sair', 'fim', 'bye', 'cancelar'])) {
            $session->reset();
            return "Atendimento encerrado. Até logo! 👋\n\n_Future Data — sua eletrônica em boas mãos._";
        }

        $input = preg_replace('/\D/', '', $text);

        // Tenta por CPF/CNPJ
        if (strlen($input) >= 11) {
            $cliente = Cliente::where('cpf_cnpj', 'like', "%{$input}")->first();
            if ($cliente) {
                return $this->clienteEncontrado($session, $cliente);
            }
        }

        // Tenta por código da OS (ex: OS00001 ou só os dígitos)
        $codigo = strtoupper(trim($text));
        if (! str_starts_with($codigo, 'OS')) {
            $codigo = 'OS' . str_pad($input, 5, '0', STR_PAD_LEFT);
        }

        $ordemQuery = Ordem::with('cliente')
            ->where(fn ($q) => $q->where('codigo_publico', $codigo)->orWhere('numero', $codigo));

        // Segurança: se o cliente já foi identificado, a OS deve pertencer a ele
        if ($session->cliente_id) {
            $ordemQuery->where('cliente_id', $session->cliente_id);
        }

        $ordem = $ordemQuery->first();

        if ($ordem) {
            // Valida que a OS pertence ao cliente correto (dupla checagem)
            if ($session->cliente_id && $ordem->cliente_id !== $session->cliente_id) {
                $attempts = ($session->context['attempts'] ?? 0) + 1;
                $session->transition('waiting_cpf', ['attempts' => $attempts]);
                return "🔒 Essa OS não pertence à sua conta.\n\nPor favor, informe seu *CPF* ou o código de uma OS sua.";
            }
            return $this->clienteEncontrado($session, $ordem->cliente, $ordem);
        }

        $attempts = ($session->context['attempts'] ?? 0) + 1;
        $session->transition('waiting_cpf', ['attempts' => $attempts]);

        if ($attempts >= 3) {
            $session->reset();
            return "Não consegui identificar você com as informações fornecidas. 😕\n\n"
                . "Por favor, ligue para nosso atendimento ou visite nossa loja.\n\n"
                . "_Future Data — sua eletrônica em boas mãos._";
        }

        return "Hmm, não encontrei nenhum registro com esse dado. 🔍\n\n"
            . "Tente novamente com:\n"
            . "• Seu *CPF* (só números)\n"
            . "• O *código da OS* (ex: OS00001)\n\n"
            . "Tentativa {$attempts}/3";
    }

    /** Estado waiting_os — cliente com múltiplas OS, aguardando escolha. */
    private function handleWaitingOs(BotSession $session, string $text): string
    {
        $opt    = $this->normalize($text);
        $lista  = $session->context['os_list'] ?? [];
        $cliente = Cliente::find($session->cliente_id);

        // Permite encerrar mesmo durante a seleção de OS
        if ($opt === '0' || in_array($opt, ['encerrar', 'tchau', 'sair', 'fim', 'bye', 'cancelar'])) {
            return $cliente
                ? $this->replyEncerrar($session, $cliente)
                : ($session->reset() ?: "Atendimento encerrado. Até logo! 👋");
        }

        $num = (int) $opt;

        if ($num >= 1 && $num <= count($lista)) {
            $ordemId = $lista[$num - 1];
            $ordem   = Ordem::with(['equipamento', 'cliente'])->find($ordemId);

            // Segurança: garante que a OS pertence ao cliente da sessão
            if (! $ordem || $ordem->cliente_id !== $session->cliente_id) {
                $session->reset();
                return "Sessão inválida. Por favor, inicie novamente.\n\n" . $this->promptCpf();
            }

            $session->transition('menu', ['selected_os' => $ordemId]);
            $session->update(['ordem_id' => $ordemId]);

            return $this->menuPrincipal($cliente, $ordem);
        }

        return "Por favor, escolha um número entre *1* e *" . count($lista) . "*, ou *0* para encerrar.\n\n"
            . $this->buildOsList($lista);
    }

    // ── Respostas do menu ────────────────────────────────────────────────────

    private function replyDetalhes(BotSession $session, Ordem $ordem): string
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

        $hasOrc = $ordem->status_orcamento === 'pendente';
        $msg .= "\n────────────────────\n";
        $msg .= "2️⃣  Falar com equipe\n";
        if ($hasOrc) $msg .= "3️⃣  Responder orçamento\n";
        $msg .= "0️⃣  Encerrar";

        $session->transition('menu');

        return $msg;
    }

    private function replyEquipe(BotSession $session, Cliente $cliente): string
    {
        $nome = explode(' ', $cliente->nome)[0];
        $session->transition('human_requested');

        return "👨‍🔧 Certo, *{$nome}*! Nossa equipe foi notificada e entrará em contato em breve.\n\n"
             . "⏰ *Horário de atendimento humano:*\n"
             . "Seg–Sex: 9h às 19h\n"
             . "Sábado: 9h às 14h\n\n"
             . "Enquanto aguarda:\n"
             . "1️⃣  Ver minha OS\n"
             . "0️⃣  Encerrar";
    }

    private function replyOrcamento(BotSession $session, Ordem $ordem): string
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

        $msg .= "Para *aprovar* ou *recusar*, acesse o portal:\n";
        $msg .= url("/portal/{$ordem->token}");

        $session->transition('menu');

        return $msg;
    }

    private function replyEncerrar(BotSession $session, Cliente $cliente): string
    {
        $nome = explode(' ', $cliente->nome)[0];
        $session->reset();

        return "Obrigado, *{$nome}*! 😊\n\n"
             . "Qualquer dúvida, é só chamar. Até logo! 👋\n\n"
             . "_Future Data — sua eletrônica em boas mãos._";
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /** Identifica o cliente, resolve a OS e avança para o menu principal. */
    private function clienteEncontrado(BotSession $session, ?Cliente $cliente, ?Ordem $ordem = null): string
    {
        if (! $cliente) {
            $session->transition('waiting_cpf', ['attempts' => 1]);
            return $this->promptCpf();
        }

        $session->update(['cliente_id' => $cliente->id]);
        $session->refresh();

        return $this->resolveOrdem($session, $cliente, $ordem);
    }

    /** Seleciona ou lista as OS do cliente. */
    private function resolveOrdem(BotSession $session, Cliente $cliente, ?Ordem $ordemForced = null, bool $force = false): string
    {
        if ($ordemForced) {
            $session->transition('menu');
            $session->update(['ordem_id' => $ordemForced->id]);
            return $this->menuPrincipal($cliente, $ordemForced->load(['equipamento']));
        }

        $ordens = Ordem::with('equipamento')
            ->where('cliente_id', $cliente->id)
            ->whereNotIn('status', ['cancelado'])
            ->latest()
            ->take(5)
            ->get();

        if ($ordens->isEmpty()) {
            $ordens = Ordem::with('equipamento')
                ->where('cliente_id', $cliente->id)
                ->latest()
                ->take(3)
                ->get();
        }

        if ($ordens->isEmpty()) {
            $nome = explode(' ', $cliente->nome)[0];
            return "Olá, *{$nome}*! Não encontrei nenhuma OS cadastrada para você. 🤔\n\n"
                . "Se precisar abrir um chamado, entre em contato conosco:\n"
                . "📍 Nossa loja ou pelo portal do cliente.\n\n"
                . "_Future Data — sua eletrônica em boas mãos._";
        }

        if ($ordens->count() === 1) {
            $ordem = $ordens->first();
            $session->transition('menu');
            $session->update(['ordem_id' => $ordem->id]);
            return $this->menuPrincipal($cliente, $ordem);
        }

        // Múltiplas OS — pede para escolher
        $lista = $ordens->pluck('id')->toArray();
        $session->transition('waiting_os', ['os_list' => $lista]);

        $nome = explode(' ', $cliente->nome)[0];
        $msg  = "Olá, *{$nome}*! Encontrei {$ordens->count()} ordens de serviço.\n\n";
        $msg .= "Sobre qual você quer informações?\n\n";
        $msg .= $this->buildOsList($lista, $ordens);

        return $msg;
    }

    /** Monta o menu principal para um cliente e OS identificados. */
    private function menuPrincipal(Cliente $cliente, Ordem $ordem): string
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
            $msg .= "3️⃣  Responder orçamento\n";
        }

        $msg .= "0️⃣  Encerrar\n\n";
        $msg .= "_Digite o número da opção._";

        return $msg;
    }

    private function promptCpf(): string
    {
        return "Olá! 👋 Sou o assistente da *Future Data*.\n\n"
             . "Para te atender, preciso te identificar.\n\n"
             . "Por favor, informe:\n"
             . "• Seu *CPF* (só números)\n"
             . "• Ou o *código da OS* (ex: OS00001)";
    }

    private function buildOsList(array $lista, $ordens = null): string
    {
        if (! $ordens) {
            $ordens = Ordem::with('equipamento')->whereIn('id', $lista)->get()->keyBy('id');
        } else {
            $ordens = $ordens->keyBy('id');
        }

        $msg = '';
        foreach ($lista as $i => $id) {
            $o      = $ordens[$id] ?? null;
            $num    = $i + 1;
            $label  = $o ? "*{$o->numero}*" : "#$id";
            $device = $o?->equipamento ? trim("{$o->equipamento->marca} {$o->equipamento->modelo}") : '';
            $status = $o ? (Ordem::STATUS[$o->status]['label'] ?? $o->status) : '';
            $msg   .= "{$num}️⃣  {$label} — {$device} ({$status})\n";
        }

        return $msg;
    }

    private function normalize(string $text): string
    {
        return trim(preg_replace('/\s+/', '', mb_strtolower($text)));
    }
}
