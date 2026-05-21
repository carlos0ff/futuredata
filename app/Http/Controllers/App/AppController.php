<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AppController extends Controller
{
    public function perfil(): View
    {
        return view('app.perfil.index', [
            'usuario' => auth()->user(),
        ]);
    }

    public function atualizarPerfil(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $dados = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user->update(['name' => $dados['name']]);

        return back()->with('success', 'Perfil atualizado com sucesso.');
    }

    public function alterarSenha(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $dados = $request->validate([
            'current_password'      => 'required|string',
            'password'              => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($dados['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Senha actual incorrecta.']);
        }

        $user->update(['password' => Hash::make($dados['password'])]);

        return back()->with('success', 'Senha alterada com sucesso.');
    }
}
