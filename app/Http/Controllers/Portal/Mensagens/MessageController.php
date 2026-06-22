<?php

namespace App\Http\Controllers\Portal\Mensagens;

use App\Http\Controllers\Controller;
use App\Models\Mensagem;
use App\Models\Ordem;
use App\Models\User;
use App\Notifications\MensagemPortal;
use App\Services\PortalBotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

/**
 * Central de mensagens do portal do cliente.
 *
 * Rotas (prefixo: portal/mensagens):
 * - GET  /            → index()  — página de mensagens (legado)
 * - POST /            → store()  — envia mensagem (web form ou JSON)
 * - GET  /{ordem}     → thread() — retorna mensagens de uma OS (JSON, widget)
 */
class MessageController extends Controller
{
    /**
     * Página de mensagens (lista de threads por OS).
     */
    public function index(Request $request): View
    {
        $clienteId = session('portal_cliente_id');

        $ordens = Ordem::with(['equipamento', 'mensagens' => fn ($q) => $q->latest()])
            ->where('cliente_id', $clienteId)
            ->whereHas('mensagens')
            ->latest()
            ->get();

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
     * Retorna as mensagens de uma OS como JSON (usado pelo widget flutuante).
     * Se ainda não há mensagens, cria a saudação inicial do bot.
     */
    public function thread(Ordem $ordem): JsonResponse
    {
        abort_if($ordem->cliente_id !== session('portal_cliente_id'), 403);

        // Cria saudação inicial do bot na primeira abertura
        if ($ordem->mensagens()->count() === 0) {
            $cliente     = \App\Models\Cliente::find(session('portal_cliente_id'));
            $primeiroNome = $cliente ? explode(' ', trim($cliente->nome))[0] : 'Cliente';

            Mensagem::create([
                'ordem_id' => $ordem->id,
                'user_id'  => null,
                'tipo'     => 'tecnico',
                'conteudo' => "Olá, {$primeiroNome}! 👋 Sou o assistente virtual da Future Data.\n\nComo posso te ajudar hoje?\n• Dúvidas sobre o status do reparo\n• Informações sobre orçamento\n• Prazo de entrega\n\nOu clique em **\"Desejo falar com o técnico\"** para falar diretamente com nossa equipe.",
            ]);
        }

        $msgs = $ordem->mensagens()
            ->with('autor')
            ->orderBy('created_at')
            ->get()
            ->map(fn ($m) => [
                'id'             => $m->id,
                'from'           => $m->tipo === 'cliente' ? 'me' : ($m->user_id ? 'operator' : 'bot'),
                'text'           => $m->conteudo,
                'time'           => $m->created_at->format('H:i'),
                'author_name'    => $m->user_id ? $m->autor->name : 'Assistente FD',
                'author_initials'=> $m->user_id ? $m->autor->iniciais : 'FD',
                'author_avatar'  => null,
                'author_color'   => $m->user_id ? $this->operatorColor($m->user_id) : null,
            ]);

        return response()->json($msgs);
    }

    private function operatorColor(int $userId): string
    {
        $palette = ['#2563eb', '#7c3aed', '#059669', '#d97706', '#e11d48', '#0284c7', '#0d9488'];

        return $palette[$userId % count($palette)];
    }

    /**
     * Salva a mensagem do cliente e aciona o bot automático.
     * Aceita request normal (redirect) ou XHR/JSON (retorna JSON).
     */
    public function store(Request $request, PortalBotService $bot): RedirectResponse|JsonResponse
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

        $gerentes = User::where('role', 'gerente')->get();
        Notification::send($gerentes, new MensagemPortal($mensagem->load('ordem')));

        if ($ordemServico->tecnico_id) {
            $ordemServico->tecnico->notify(new MensagemPortal($mensagem));
        }

        $bot->handle(session('portal_cliente_id'), $ordemServico->id, $data['conteudo']);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Mensagem enviada!')->withFragment('mensagens');
    }
}
