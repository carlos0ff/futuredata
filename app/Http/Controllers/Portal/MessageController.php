<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Mensagem;
use App\Models\Ordem;
use App\Models\User;
use App\Notifications\MensagemPortal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class MessageController extends Controller
{
    /** Enviar mensagem via portal público */
    public function store(Ordem $ordemServico, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'conteudo' => 'required|string|max:1000',
        ]);

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

        return redirect()
            ->route('portal.token', $ordemServico->token)
            ->with('success', 'Mensagem enviada com sucesso!')
            ->withFragment('mensagens');
    }
}
