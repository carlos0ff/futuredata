<?php

namespace App\Http\Controllers\App\Perfil;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Gerencia o perfil do usuário autenticado.
 *
 * Rotas relacionadas (prefixo: app/perfil):
 * - GET  /app/perfil          → perfil()
 * - PUT  /app/perfil/atualizar → atualizarPerfil()
 * - PUT  /app/perfil/senha     → alterarSenha()
 */
class PerfilController extends Controller
{
    /**
     * Exibe a página de perfil do usuário logado.
     */
    public function perfil(): View
    {
        return view('app.perfil.index', [
            'usuario' => auth()->user(),
        ]);
    }

    /**
     * Atualiza o nome do usuário logado.
     */
    public function atualizarPerfil(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        auth()->user()->update(['name' => $dados['name']]);

        return back()->with('success', 'Perfil atualizado com sucesso.');
    }

    /**
     * Altera a senha do usuário logado, exigindo a senha atual.
     */
    public function alterarSenha(Request $request): RedirectResponse
    {
        $user  = auth()->user();
        $dados = $request->validate([
            'current_password' => 'required|string',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        if (! Hash::check($dados['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
        }

        $user->update(['password' => Hash::make($dados['password'])]);

        return back()->with('success', 'Senha alterada com sucesso.');
    }
}
