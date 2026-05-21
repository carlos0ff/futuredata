<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Ordem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    /** Resolve o cliente logado via sessão do portal */
    private function clienteAtual(): ?Cliente
    {
        $id = session('portal_cliente_id');
        return $id ? Cliente::find($id) : null;
    }

    /** Dashboard do portal — lista de OS do cliente */
    public function index(Request $request): View|RedirectResponse
    {
        $cliente = $this->clienteAtual();

        if (! $cliente) {
            return redirect()->route('portal.entrar');
        }

        $ordens = Ordem::with(['equipamento', 'historico'])
            ->where('cliente_id', $cliente->id)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
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

    /** Detalhe de uma OS (protegido — deve pertencer ao cliente logado) */
    public function show(Ordem $ordem): View|RedirectResponse
    {
        $cliente = $this->clienteAtual();

        if (! $cliente || $ordem->cliente_id !== $cliente->id) {
            return redirect()->route('portal.index');
        }

        return view('portal.show', array_merge(
            $this->buildShowData($ordem),
            ['cliente' => $cliente]
        ));
    }

    /** Acesso público por token curto — /r/{token} — sem login */
    public function showByToken(string $token): View|RedirectResponse
    {
        $ordem = Ordem::where('token', $token)
            ->orWhere('codigo_publico', $token)
            ->first();

        if (! $ordem) {
            abort(404);
        }

        $cliente = $ordem->cliente;

        return view('portal.show', array_merge(
            $this->buildShowData($ordem),
            ['cliente' => $cliente]
        ));
    }

    /** Monta os dados comuns da view de detalhe */
    private function buildShowData(Ordem $ordem): array
    {
        $cliente = $ordem->cliente;
        $waUrl   = null;

        if ($cliente?->telefone) {
            $tel      = preg_replace('/\D/', '', $cliente->telefone);
            $mensagem = urlencode("Olá {$cliente->nome}, sua OS {$ordem->codigo_publico} está disponível no portal.");
            $waUrl    = "https://wa.me/55{$tel}?text={$mensagem}";
        }

        $steps = [
            ['key' => 'entrada',   'label' => 'Entrada',        'desc' => 'Equipamento recebido pela assistência.'],
            ['key' => 'analise',   'label' => 'Em Análise',     'desc' => 'Diagnóstico técnico em andamento.'],
            ['key' => 'execucao',  'label' => 'Em Execução',    'desc' => 'Reparo sendo realizado.'],
            ['key' => 'em_teste',  'label' => 'Em Teste',       'desc' => 'Equipamento em testes finais.'],
            ['key' => 'finalizado','label' => 'Finalizado',     'desc' => 'Pronto para retirada.'],
        ];

        $currentStep = collect($steps)->search(fn ($s) => $s['key'] === $ordem->status) ?: 0;

        return [
            'ordem'       => $ordem,
            'waUrl'       => $waUrl,
            'steps'       => $steps,
            'currentStep' => $currentStep,
            'isFinished'  => $ordem->status === 'finalizado',
            'isCancelled' => $ordem->status === 'cancelado',
        ];
    }
}
