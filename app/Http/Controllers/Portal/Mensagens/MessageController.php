<?php

namespace App\Http\Controllers\Portal\Mensagens;

use App\Http\Controllers\Controller;
use App\Models\Mensagem;
use App\Models\Ordem;
use App\Models\User;
use App\Notifications\MensagemPortal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

/**
 * Central de mensagens do portal do cliente.
 *
 * Lista as conversas (uma por OS) e permite que o cliente envie mensagens
 * para a equipe interna. Após salvar, notifica todos os gerentes e o técnico
 * responsável pela OS. A mensagem é salva com `tipo = "cliente"` e `user_id = null`.
 *
 * Rotas (prefixo: portal/mensagens):
 * - GET  / → index()
 * - POST / → store()
 */
class MessageController extends Controller
{
    /**
     * Central de conversas: uma thread por OS do cliente logado.
     * `?ordem={id}` abre a thread correspondente.
     */
    public function index(Request $request): View
    {
        $clienteId = session('portal_cliente_id');

        $ordens = Ordem::with(['equipamento', 'mensagens' => fn ($q) => $q->latest()])
            ->where('cliente_id', $clienteId)
            ->whereHas('mensagens')
            ->latest()
            ->get();

        // Última mensagem de cada OS (resumo da thread)
        $mensagens = $ordens->map(fn (Ordem $ordem) => $ordem->mensagens->first());

        $ordemAtiva = null;
        $thread     = collect();

        if ($request->filled('ordem')) {
            $ordemAtiva = Ordem::with('equipamento')
                ->where('cliente_id', $clienteId)
                ->find($request->ordem);

            if ($ordemAtiva) {
                $thread = $ordemAtiva->mensagens()->oldest()->get();
            }
        }

        return view('portal.mensagens.index', compact('mensagens', 'thread', 'ordemAtiva'));
    }

    /**
     * Salva a mensagem do cliente e notifica a equipe interna.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'conteudo' => 'required|string|max:1000',
            'ordem_id' => 'required|exists:ordens,id',
        ]);

        $ordemServico = Ordem::findOrFail($data['ordem_id']);

        abort_if($ordemServico->cliente_id !== session('portal_cliente_id'), 403);

        $mensagem = Mensagem::create([
            'ordem_id' => $ordemServico->id,
            'user_id'  => null,
            'tipo'     => 'cliente',
            'conteudo' => $data['conteudo'],
        ]);

        $mensagem->load('ordem');
        $gerentes = User::where('role', 'gerente')->get();
        Notification::send($gerentes, new MensagemPortal($mensagem));

        if ($ordemServico->tecnico_id) {
            $ordemServico->tecnico->notify(new MensagemPortal($mensagem));
        }

        return back()
            ->with('success', 'Mensagem enviada com sucesso!')
            ->withFragment('mensagens');
    }
}
