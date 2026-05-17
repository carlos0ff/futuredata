<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Mensagem;
use App\Models\Ordem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $ordens = Ordem::with(['mensagens' => fn ($q) => $q->latest()->limit(1)])
            ->where('cliente_id', auth()->user()->cliente_id ?? null)
            ->latest()
            ->get();

        $ordemAtiva = null;
        $mensagens  = collect();

        if ($request->filled('ordem')) {
            $ordemAtiva = Ordem::with('mensagens.autor')
                ->where('cliente_id', auth()->user()->cliente_id ?? null)
                ->findOrFail($request->ordem);

            $mensagens = $ordemAtiva->mensagens()
                ->with('autor')
                ->oldest()
                ->get();

            $ordemAtiva->mensagens()
                ->whereNull('lida_em')
                ->where('user_id', '!=', auth()->id())
                ->update(['lida_em' => now()]);
        }

        return view('pages.client-portal.messages.index', compact('ordens', 'ordemAtiva', 'mensagens'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'ordem_id' => ['required', 'exists:ordens,id'],
            'conteudo' => ['required', 'string', 'max:2000'],
        ]);

        Mensagem::create([
            'ordem_id' => $request->ordem_id,
            'user_id'  => auth()->id(),
            'tipo'     => 'cliente',
            'conteudo' => $request->conteudo,
        ]);

        return redirect()
            ->route('portal.mensagens.index', ['ordem' => $request->ordem_id])
            ->with('success', 'Mensagem enviada.');
    }
}
