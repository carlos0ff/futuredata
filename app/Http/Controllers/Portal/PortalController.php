<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Mensagem;
use App\Models\Ordem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function index(Request $request): View
    {
        $user    = auth()->user();
        $cliente = Cliente::where('email', $user->email)->first();

        $ordens = $cliente
            ? Ordem::with(['equipamento', 'historico'])
                ->where('cliente_id', $cliente->id)
                ->when($request->filled('status'), fn ($q) =>
                    $q->where('status', $request->status)
                )
                ->latest()
                ->paginate(10)
                ->withQueryString()
            : new LengthAwarePaginator([], 0, 10);

        $stats = $cliente ? [
            'total'       => Ordem::where('cliente_id', $cliente->id)->count(),
            'abertas'     => Ordem::where('cliente_id', $cliente->id)->whereNotIn('status', ['finalizado', 'cancelado'])->count(),
            'finalizadas' => Ordem::where('cliente_id', $cliente->id)->where('status', 'finalizado')->count(),
        ] : ['total' => 0, 'abertas' => 0, 'finalizadas' => 0];

        return view('pages.client-portal.index', compact('ordens', 'cliente', 'stats'));
    }

    public function show(string $codigo): View
    {
        $ordem = Ordem::with(['cliente', 'equipamento', 'tecnico', 'historico', 'mensagens'])
            ->where('codigo_publico', $codigo)
            ->firstOrFail();

        return view('pages.client-portal.show', compact('ordem'));
    }

    public function storeMessage(string $codigo, Request $request): RedirectResponse
    {
        $ordem = Ordem::where('codigo_publico', $codigo)->firstOrFail();

        $data = $request->validate(['mensagem' => 'required|string|max:1000']);

        Mensagem::create([
            'ordem_id' => $ordem->id,
            'user_id'  => null,
            'tipo'     => 'cliente',
            'conteudo' => $data['mensagem'],
        ]);

        return redirect()->route('portal.show', $codigo)
            ->with('success', 'Mensagem enviada com sucesso!')
            ->withFragment('mensagens');
    }

    public function orcamento(string $codigo, Request $request): RedirectResponse
    {
        $ordem = Ordem::where('codigo_publico', $codigo)->firstOrFail();

        $acao = $request->validate(['acao' => 'required|in:aprovado,recusado'])['acao'];

        $ordem->update(['status_orcamento' => $acao]);

        $msg = $acao === 'aprovado'
            ? 'Orçamento aprovado! Em breve entraremos em contato.'
            : 'Orçamento recusado. Nossa equipe entrará em contato.';

        return redirect()->route('portal.show', $codigo)->with('success', $msg);
    }
}
