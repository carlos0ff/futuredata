<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RecuperarRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function entrar(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->intended(route('ordens.index'));
        }

        return view('pages.auth.entrar');
    }

    public function entrarPost(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('ordens.index'));
    }

    public function recuperar(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('ordens.index');
        }

        return view('pages.auth.recuperar');
    }

    public function recuperarPost(RecuperarRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link de recuperação enviado para seu e-mail.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function sair(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
