<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /** Mostrar formulário de login */
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('app.dashboard');
        }

        return view('auth.entrar');
    }

    /** Processar login */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas. Verifique o e-mail e a senha.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('app.dashboard'));
    }

    /** Logout */
    public function sair(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.entrar');
    }

    /** Mostrar formulário de recuperação */
    public function recuperar(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('app.dashboard');
        }

        return view('auth.recuperar');
    }

    /** Processar recuperação */
    public function recuperarPost(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link de recuperação enviado para o seu e-mail.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
