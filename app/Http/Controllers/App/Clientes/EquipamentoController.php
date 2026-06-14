<?php

namespace App\Http\Controllers\App\Clientes;
use App\Http\Controllers\Controller;

use App\Models\Cliente;
use App\Models\Equipamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipamentoController extends Controller
{
    public function index(Request $request): View
    {
        $equipamentos = Equipamento::query()
            ->with('cliente')
            ->when($request->filled('busca'), fn ($q) =>
                $q->where(fn ($sub) =>
                    $sub->where('marca', 'like', "%{$request->busca}%")
                        ->orWhere('modelo', 'like', "%{$request->busca}%")
                        ->orWhere('numero_serie', 'like', "%{$request->busca}%")
                        ->orWhereHas('cliente', fn ($c) =>
                            $c->where('nome', 'like', "%{$request->busca}%")
                        )
                )
            )
            ->when($request->filled('tipo'), fn ($q) => $q->where('tipo', $request->tipo))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('app.equipamentos.index', [
            'equipamentos' => $equipamentos,
            'tipos'        => ['Notebook', 'Desktop', 'Impressora', 'Celular', 'Tablet', 'Monitor', 'Outro'],
            'current'      => $request->only('busca', 'tipo'),
        ]);
    }

    public function create(): View
    {
        return view('app.equipamentos.create', [
            'clientes' => Cliente::orderBy('nome')->get(['id', 'nome']),
            'tipos'    => ['Notebook', 'Desktop', 'Impressora', 'Celular', 'Tablet', 'Monitor', 'Outro'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'cliente_id'       => 'required|exists:clientes,id',
            'tipo'             => 'required|string|max:100',
            'marca'            => 'nullable|string|max:100',
            'modelo'           => 'nullable|string|max:100',
            'numero_serie'     => 'nullable|string|max:100',
            'patrimonio'       => 'nullable|string|max:100',
            'acessorios'       => 'nullable|string',
            'condicao_entrada' => 'nullable|string',
            'em_garantia'      => 'boolean',
            'observacoes'      => 'nullable|string',
        ]);

        $dados['em_garantia'] = $request->boolean('em_garantia');
        $equipamento = Equipamento::create($dados);

        return redirect()->route('app.equipamentos.show', $equipamento)
            ->with('success', 'Equipamento cadastrado com sucesso.');
    }

    public function show(Equipamento $equipamento): View
    {
        $equipamento->load(['cliente', 'ordens.tecnico']);

        return view('app.equipamentos.show', [
            'equipamento' => $equipamento,
        ]);
    }

    public function edit(Equipamento $equipamento): View
    {
        return view('app.equipamentos.edit', [
            'equipamento' => $equipamento,
            'clientes'    => Cliente::orderBy('nome')->get(['id', 'nome']),
            'tipos'       => ['Notebook', 'Desktop', 'Impressora', 'Celular', 'Tablet', 'Monitor', 'Outro'],
        ]);
    }

    public function update(Request $request, Equipamento $equipamento): RedirectResponse
    {
        $dados = $request->validate([
            'cliente_id'       => 'required|exists:clientes,id',
            'tipo'             => 'required|string|max:100',
            'marca'            => 'nullable|string|max:100',
            'modelo'           => 'nullable|string|max:100',
            'numero_serie'     => 'nullable|string|max:100',
            'patrimonio'       => 'nullable|string|max:100',
            'acessorios'       => 'nullable|string',
            'condicao_entrada' => 'nullable|string',
            'em_garantia'      => 'boolean',
            'observacoes'      => 'nullable|string',
        ]);

        $dados['em_garantia'] = $request->boolean('em_garantia');
        $equipamento->update($dados);

        return redirect()->route('app.equipamentos.show', $equipamento)
            ->with('success', 'Equipamento atualizado com sucesso.');
    }

    public function destroy(Equipamento $equipamento): RedirectResponse
    {
        $equipamento->delete();

        return redirect()->route('app.equipamentos.index')
            ->with('success', 'Equipamento removido com sucesso.');
    }
}
