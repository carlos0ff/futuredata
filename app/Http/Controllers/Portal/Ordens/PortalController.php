<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal\Ordens;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Ordem;
use App\Models\OrdemArquivo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Portal público do cliente — acompanhamento de OS sem cadastro.
 *
 * Autenticação do portal:
 *   - Não usa o sistema de auth do Laravel (sem cookies de sessão de usuário).
 *   - Armazena o ID do cliente na sessão com a chave `portal_cliente_id`.
 *   - O cliente acessa via código/token da OS (enviado por WhatsApp).
 *
 * Formas de acesso:
 *   1. Login manual: cliente digita o código da OS em /portal/entrar
 *   2. Link direto:  /r/{token} autentica automaticamente e redireciona para a OS
 *
 * Rotas (prefixo: portal):
 * - GET  /portal              → index()   — lista de OS do cliente
 * - GET  /portal/{ordem}      → show()    — detalhe de uma OS
 * - POST /portal/{ordem}/orcamento → responderOrcamento()
 * - GET  /portal/os/{ordem}/arquivos/{arquivo} → arquivo() — exibe arquivo da OS
 * - GET  /r/{token}           → showByToken() — acesso direto por token
 */
class PortalController extends Controller
{
    /**
     * Dashboard do portal — lista de OS do cliente logado.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $cliente = $this->clienteAtual();

        if (! $cliente) {
            return redirect()->route('portal.entrar');
        }

        $ordens = Ordem::with(['equipamento', 'historico'])
            ->where('cliente_id', $cliente->id)
            ->when($request->filled('status'), function ($q) use ($request) {
                match ($request->status) {
                    'abertas'     => $q->whereNotIn('status', ['finalizado', 'cancelado']),
                    'finalizadas' => $q->where('status', 'finalizado'),
                    default       => $q->where('status', $request->status),
                };
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total'       => Ordem::where('cliente_id', $cliente->id)->count(),
            'abertas'     => Ordem::where('cliente_id', $cliente->id)->whereNotIn('status', ['finalizado', 'cancelado'])->count(),
            'finalizadas' => Ordem::where('cliente_id', $cliente->id)->where('status', 'finalizado')->count(),
        ];

        return view('portal.index', compact('ordens', 'cliente', 'stats'));
    }

    /**
     * Detalhe de uma OS — protegido: a OS deve pertencer ao cliente logado.
     */
    public function show(Ordem $ordem): View|RedirectResponse
    {
        $cliente = $this->clienteAtual();

        if (! $cliente || $ordem->cliente_id !== $cliente->id) {
            return redirect()->route('portal.index');
        }

        return view('portal.show', array_merge(
            $this->buildShowData($ordem),
            [
                'cliente'        => $cliente,
                'isStaffPreview' => auth()->check(),
            ]
        ));
    }

    /**
     * Processa a resposta do cliente ao orçamento (aprovado/recusado).
     * Só permite uma resposta por orçamento (status_orcamento !== 'pendente' bloqueia).
     */
    public function responderOrcamento(Request $request, Ordem $ordem): RedirectResponse
    {
        $cliente = $this->clienteAtual();

        if (! $cliente || $ordem->cliente_id !== $cliente->id) {
            return redirect()->route('portal.index');
        }

        if (auth()->check()) {
            return back()->with('error', 'A aprovação ou recusa do orçamento deve ser feita pelo cliente no portal.');
        }

        if ($ordem->status_orcamento !== 'pendente') {
            return back()->with('error', 'Este orçamento já foi respondido anteriormente.');
        }

        $validated = $request->validate([
            'resposta' => 'required|in:aprovado,recusado',
        ]);

        $ordem->update(['status_orcamento' => $validated['resposta']]);

        $payload = $validated['resposta'] === 'aprovado'
            ? ['status' => 'aprovado', 'msg' => 'Orçamento aprovado! Nossa equipe iniciará o reparo em breve.']
            : ['status' => 'recusado', 'msg' => 'Orçamento recusado. Nossa equipe entrará em contato para discutir os próximos passos.'];

        return back()->with('orcamento_resposta', $payload);
    }

    /**
     * Exibe inline um arquivo da OS (foto/documento) para o cliente do portal.
     * Protegido: a OS deve pertencer ao cliente logado e o arquivo à OS.
     */
    public function arquivo(Ordem $ordem, OrdemArquivo $arquivo): StreamedResponse|RedirectResponse
    {
        $cliente = $this->clienteAtual();

        if (! $cliente || $ordem->cliente_id !== $cliente->id) {
            return redirect()->route('portal.index');
        }

        abort_if($arquivo->ordem_id !== $ordem->id, 403);

        if (! Storage::disk('local')->exists($arquivo->caminho)) {
            abort(404);
        }

        return Storage::disk('local')->response($arquivo->caminho, $arquivo->nome_original);
    }

    /**
     * Acesso por token curto — /r/{token}.
     * Autentica automaticamente o cliente sem exigir login explícito.
     * Usado no link enviado por WhatsApp quando a OS é criada.
     */
    public function showByToken(string $token): View|RedirectResponse
    {
        $ordem = Ordem::where('token', $token)
            ->orWhere('codigo_publico', $token)
            ->first();

        if (! $ordem || ! $ordem->cliente_id) {
            abort(404);
        }

        session()->regenerate();
        session(['portal_cliente_id' => $ordem->cliente_id]);

        return redirect()->route('portal.show', $ordem);
    }

    // ── Helpers privados ─────────────────────────────────────────────────────

    /**
     * Resolve o cliente logado no portal via sessão.
     * Retorna null se não houver sessão ativa (não autenticado).
     */
    private function clienteAtual(): ?Cliente
    {
        $id = session('portal_cliente_id');
        return $id ? Cliente::find($id) : null;
    }

    /**
     * Monta o array de dados comum para a view de detalhe da OS.
     * Inclui: link de WhatsApp, passos do progresso e flags de estado.
     */
    private function buildShowData(Ordem $ordem): array
    {
        $cliente = $ordem->cliente;
        $waUrl   = null;
        $waTel   = null;

        if ($cliente?->telefone) {
            $tel   = preg_replace('/\D/', '', $cliente->telefone);
            $waTel = "55{$tel}";
            $portalUrl    = route('portal.token', $ordem->token ?? $ordem->codigo_publico);
            $primeiroNome = explode(' ', trim($cliente->nome))[0];
            $equipamento  = $ordem->equipamento?->nome_completo ?? 'equipamento';

            $mensagem = implode("\n", [
                "Olá, *{$primeiroNome}*! 👋",
                "",
                "Recebemos seu *{$equipamento}* aqui na assistência. ✅",
                "",
                "📋 *OS:* {$ordem->codigo_publico}",
                "📅 *Entrada:* " . $ordem->created_at->format('d/m/Y \à\s H:i'),
                "",
                "Acompanhe o andamento pelo nosso *Portal do Cliente*:",
                "🔗 {$portalUrl}",
                "",
                "*Como acessar:*",
                "1️⃣ Clique no link acima",
                "2️⃣ Digite seu *CPF*",
                "3️⃣ Informe sua *data de nascimento*",
                "4️⃣ Pronto! Veja o status em tempo real",
                "",
                "📱 Dúvidas? É só chamar aqui no WhatsApp.",
                "— _AssistPro Assistência Técnica_",
            ]);

            $waUrl = "https://wa.me/55{$tel}?text=" . urlencode($mensagem);
        }

        $steps = [
            ['key' => 'entrada',    'label' => 'Entrada',     'desc' => 'Equipamento recebido pela assistência.'],
            ['key' => 'analise',    'label' => 'Em Análise',  'desc' => 'Diagnóstico técnico em andamento.'],
            ['key' => 'execucao',   'label' => 'Em Execução', 'desc' => 'Reparo sendo realizado.'],
            ['key' => 'em_teste',   'label' => 'Em Teste',    'desc' => 'Equipamento em testes finais.'],
            ['key' => 'finalizado', 'label' => 'Finalizado',  'desc' => 'Pronto para retirada.'],
        ];

        $currentStep = collect($steps)->search(fn ($s) => $s['key'] === $ordem->status) ?: 0;

        return [
            'ordem'       => $ordem,
            'mensagens'   => $ordem->mensagens()->latest()->limit(5)->get(),
            'waUrl'       => $waUrl,
            'waTel'       => $waTel,
            'steps'       => $steps,
            'currentStep' => $currentStep,
            'isFinished'  => $ordem->status === 'finalizado',
            'isCancelled' => $ordem->status === 'cancelado',
        ];
    }
}
