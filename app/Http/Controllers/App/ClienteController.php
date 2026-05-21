<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;

use App\Models\Cliente;
use App\Models\Equipamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $clientes = Cliente::query()
            ->withCount('ordens')
            ->when($request->filled('busca'), fn ($q) =>
                $q->where('nome', 'like', "%{$request->busca}%")
                  ->orWhere('email', 'like', "%{$request->busca}%")
                  ->orWhere('telefone', 'like', "%{$request->busca}%")
                  ->orWhere('cpf_cnpj', 'like', "%{$request->busca}%")
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('app.clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('app.clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome'       => 'required|string|max:255',
            'email'      => 'nullable|email|unique:clientes,email',
            'telefone'   => 'nullable|string|max:20',
            'cpf_cnpj'   => 'nullable|string|max:20|unique:clientes,cpf_cnpj',
            'endereco'   => 'nullable|string|max:500',
            'cidade'     => 'nullable|string|max:100',
            'estado'     => 'nullable|string|max:2',
            'cep'        => 'nullable|string|max:10',
            'observacoes'=> 'nullable|string',
        ]);

        $cliente = Cliente::create($dados);

        return redirect()->route('app.clientes.show', $cliente)
            ->with('success', 'Cliente cadastrado com sucesso.');
    }

    public function show(Cliente $cliente): View
    {
        $cliente->load(['ordens.equipamento', 'equipamentos']);

        return view('app.clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente): View
    {
        return view('app.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $dados = $request->validate([
            'nome'       => 'required|string|max:255',
            'email'      => "nullable|email|unique:clientes,email,{$cliente->id}",
            'telefone'   => 'nullable|string|max:20',
            'cpf_cnpj'   => "nullable|string|max:20|unique:clientes,cpf_cnpj,{$cliente->id}",
            'endereco'   => 'nullable|string|max:500',
            'cidade'     => 'nullable|string|max:100',
            'estado'     => 'nullable|string|max:2',
            'cep'        => 'nullable|string|max:10',
            'observacoes'=> 'nullable|string',
        ]);

        $cliente->update($dados);

        return redirect()->route('app.clientes.show', $cliente)
            ->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('app.clientes.index')
            ->with('success', 'Cliente removido com sucesso.');
    }

    public function equipamentos(Cliente $cliente): View
    {
        $equipamentos = $cliente->equipamentos()->latest()->get();

        return view('app.clientes.equipamentos', compact('cliente', 'equipamentos'));
    }
}
