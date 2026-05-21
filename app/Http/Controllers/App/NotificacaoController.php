<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificacaoController extends Controller
{
    public function index(Request $request): View
    {
        $user   = auth()->user();
        $filtro = $request->get('filtro', 'todas');

        $query = $user->notifications();

        if ($filtro === 'nao_lidas') {
            $query->whereNull('read_at');
        } elseif ($filtro === 'lidas') {
            $query->whereNotNull('read_at');
        }

        $notificacoes  = $query->latest()->paginate(20)->withQueryString();
        $totalNaoLidas = $user->unreadNotifications()->count();

        return view('app.notificacoes.index', compact('notificacoes', 'totalNaoLidas', 'filtro'));
    }

    public function open(string $id): RedirectResponse
    {
        $notificacao = auth()->user()->notifications()->findOrFail($id);
        $notificacao->markAsRead();

        $url = $notificacao->data['url'] ?? route('app.notificacoes.index');

        return redirect($url);
    }

    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Todas as notificações foram marcadas como lidas.');
    }

    public function destroy(string $id): RedirectResponse
    {
        auth()->user()->notifications()->findOrFail($id)->delete();

        return back()->with('success', 'Notificação removida.');
    }

    public function destroyAll(): RedirectResponse
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'Todas as notificações foram removidas.');
    }
}
