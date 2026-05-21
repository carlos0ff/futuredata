<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (session()->has('portal_cliente_id')) {
            return redirect()->route('portal.index');
        }

        return view('portal.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'cpf_cnpj'        => ['required', 'string'],
            'data_nascimento' => ['required', 'date'],
        ], [
            'cpf_cnpj.required'        => 'Informe o CPF ou CNPJ.',
            'data_nascimento.required' => 'Informe a data de nascimento.',
            'data_nascimento.date'     => 'Data de nascimento inválida.',
        ]);

        // Normalizar CPF/CNPJ removendo máscara
        $cpf = preg_replace('/\D/', '', $request->cpf_cnpj);

        $cliente = Cliente::whereRaw("REGEXP_REPLACE(cpf_cnpj, '[^0-9]', '') = ?", [$cpf])
            ->whereDate('data_nascimento', $request->data_nascimento)
            ->first();

        if (! $cliente) {
            return back()
                ->withInput($request->only('cpf_cnpj', 'data_nascimento'))
                ->withErrors(['cpf_cnpj' => 'CPF/CNPJ ou data de nascimento incorretos.']);
        }

        session()->regenerate();
        session(['portal_cliente_id' => $cliente->id]);

        return redirect()->route('portal.index');
    }

    public function sair(Request $request): RedirectResponse
    {
        $request->session()->forget('portal_cliente_id');
        $request->session()->regenerateToken();

        return redirect()->route('portal.entrar');
    }
}
