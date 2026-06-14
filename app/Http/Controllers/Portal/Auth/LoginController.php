<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal\Auth;

use App\Http\Controllers\Controller;
use App\Models\Ordem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Autenticação do Portal do Cliente.
 *
 * O portal não usa o sistema de auth padrão do Laravel.
 * A autenticação é feita pelo código da OS (token ou codigo_publico),
 * que é guardado na sessão como `portal_cliente_id`.
 *
 * Rotas:
 * - GET  /portal/entrar  → index()        — exibe o formulário de login
 * - POST /portal/entrar  → authenticate() — valida o código e cria a sessão
 * - POST /portal/sair    → sair()         — destroi a sessão do portal
 */
class LoginController extends Controller
{
    /**
     * Exibe a tela de login do portal.
     * Se já autenticado, redireciona para a lista de OS.
     * Aceita ?ref={token} para pré-exibir dados da OS ao cliente.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if (session()->has('portal_cliente_id')) {
            return redirect()->route('portal.index');
        }

        $ordemRef = null;
        if ($request->filled('ref')) {
            $ordemRef = Ordem::where('token', $request->ref)
                ->orWhere('codigo_publico', $request->ref)
                ->with(['equipamento'])
                ->first();
        }

        return view('portal.login', compact('ordemRef'));
    }

    /**
     * Valida o código da OS e autentica o cliente na sessão.
     * Aceita token (7 chars aleatórios) ou codigo_publico (OS00001).
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'codigo' => ['required', 'string'],
        ], [
            'codigo.required' => 'Informe o código de acesso da sua OS.',
        ]);

        $codigo = strtoupper(trim($request->codigo));

        $ordem = Ordem::where('token', $codigo)
            ->orWhere('token', strtolower($codigo))
            ->orWhere('codigo_publico', $codigo)
            ->first();

        if (! $ordem || ! $ordem->cliente_id) {
            return back()
                ->withInput($request->only('codigo'))
                ->withErrors(['codigo' => 'Código de acesso inválido. Verifique o código enviado via WhatsApp.']);
        }

        session()->regenerate();
        session(['portal_cliente_id' => $ordem->cliente_id]);

        return redirect()->route('portal.show', $ordem);
    }

    /**
     * Encerra a sessão do portal e redireciona para o login.
     */
    public function sair(Request $request): RedirectResponse
    {
        $request->session()->forget('portal_cliente_id');
        $request->session()->regenerateToken();

        return redirect()->route('portal.entrar');
    }
}
