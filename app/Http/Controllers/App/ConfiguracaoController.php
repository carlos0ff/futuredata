<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfiguracaoController extends Controller
{
    public function index(): View
    {
        $config = [
            'empresa_nome'     => config('app.name', 'Future Data'),
            'empresa_cnpj'     => '',
            'empresa_telefone' => '',
            'empresa_email'    => '',
            'empresa_endereco' => '',
        ];

        return view('app.configuracoes.index', compact('config'));
    }

    public function empresa(Request $request): RedirectResponse
    {
        $request->validate([
            'empresa_nome'     => 'required|string|max:255',
            'empresa_cnpj'     => 'nullable|string|max:20',
            'empresa_telefone' => 'nullable|string|max:20',
            'empresa_email'    => 'nullable|email',
            'empresa_endereco' => 'nullable|string|max:500',
        ]);

        // TODO: persist to settings table or .env
        return back()->with('success', 'Configurações salvas com sucesso.');
    }

    public function sistema(Request $request): RedirectResponse
    {
        return back()->with('success', 'Configurações do sistema salvas.');
    }

    public function email(Request $request): RedirectResponse
    {
        return back()->with('success', 'Configurações de e-mail salvas.');
    }
}
