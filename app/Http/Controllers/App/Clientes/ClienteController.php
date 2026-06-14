<?php

namespace App\Http\Controllers\App\Clientes;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CRUD de clientes da assistência técnica.
 *
 * Rotas (prefixo: app/clientes, middleware: role:gerente,admin,atendente):
 * - GET    /app/clientes                  → index()
 * - GET    /app/clientes/novo             → create()
 * - POST   /app/clientes                  → store()
 * - GET    /app/clientes/{cliente}        → show()
 * - GET    /app/clientes/{cliente}/editar → edit()
 * - PUT    /app/clientes/{cliente}        → update()
 * - DELETE /app/clientes/{cliente}        → destroy()
 * - GET    /app/clientes/{cliente}/equipamentos → equipamentos()
 */
class ClienteController extends Controller
{
    /**
     * Lista paginada de clientes com busca por nome, e-mail, telefone ou CPF/CNPJ.
     */
    public function index(Request $request): View
    {
        $clientes = Cliente::query()
            ->withCount('ordens')
            ->when($request->filled('busca'), fn ($q) =>
                $q->where('nome',     'like', "%{$request->busca}%")
                  ->orWhere('email',   'like', "%{$request->busca}%")
                  ->orWhere('telefone','like', "%{$request->busca}%")
                  ->orWhere('cpf_cnpj','like', "%{$request->busca}%")
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalComOS   = Cliente::has('ordens')->count();
        $novosEsseMes = Cliente::whereMonth('created_at', now()->month)
                               ->whereYear('created_at', now()->year)
                               ->count();

        return view('app.clientes.index', compact('clientes', 'totalComOS', 'novosEsseMes'));
    }

    /**
     * Exibe o formulário de cadastro de novo cliente.
     */
    public function create(): View
    {
        return view('app.clientes.create');
    }

    /**
     * Persiste um novo cliente no banco de dados.
     */
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome'            => 'required|string|max:255',
            'email'           => 'nullable|email|unique:clientes,email',
            'telefone'        => 'nullable|string|max:20',
            'cpf_cnpj'        => 'nullable|string|max:20|unique:clientes,cpf_cnpj',
            'data_nascimento' => 'nullable|date',
            'endereco'        => 'nullable|string|max:500',
            'numero'          => 'nullable|string|max:20',
            'complemento'     => 'nullable|string|max:100',
            'bairro'          => 'nullable|string|max:100',
            'cidade'          => 'nullable|string|max:100',
            'estado'          => 'nullable|string|max:2',
            'cep'             => 'nullable|string|max:10',
            'observacoes'     => 'nullable|string',
        ]);

        $cliente = Cliente::create($dados);

        return redirect()->route('app.clientes.show', $cliente)
            ->with('success', 'Cliente cadastrado com sucesso.');
    }

    /**
     * Exibe o perfil completo do cliente com suas OS e equipamentos.
     */
    public function show(Cliente $cliente): View
    {
        $cliente->load(['ordens.equipamento', 'equipamentos']);

        return view('app.clientes.show', compact('cliente'));
    }

    /**
     * Exibe o formulário de edição com métricas (total gasto, última OS).
     */
    public function edit(Cliente $cliente): View
    {
        $cliente->loadCount('ordens');

        $totalGasto = $cliente->ordens()
            ->where('status', 'finalizado')
            ->selectRaw('COALESCE(SUM(valor_servico + valor_pecas - desconto), 0) as total')
            ->value('total') ?? 0;

        $ultimaOs = $cliente->ordens()->latest()->first();

        $temOsEmAndamento = $cliente->ordens()->whereNotIn('status', \App\Models\Ordem::STATUS_FINAIS)->exists();

        return view('app.clientes.edit', compact('cliente', 'totalGasto', 'ultimaOs', 'temOsEmAndamento'));
    }

    /**
     * Atualiza os dados cadastrais do cliente.
     * Nome e CPF/CNPJ não podem ser alterados enquanto houver OS em andamento,
     * para preservar a integridade do histórico.
     */
    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $dados = $request->validate([
            'nome'            => 'required|string|max:255',
            'email'           => "nullable|email|unique:clientes,email,{$cliente->id}",
            'telefone'        => 'nullable|string|max:20',
            'cpf_cnpj'        => "nullable|string|max:20|unique:clientes,cpf_cnpj,{$cliente->id}",
            'data_nascimento' => 'nullable|date',
            'cep'             => 'nullable|string|max:10',
            'endereco'        => 'nullable|string|max:255',
            'numero'          => 'nullable|string|max:20',
            'complemento'     => 'nullable|string|max:100',
            'bairro'          => 'nullable|string|max:100',
            'cidade'          => 'nullable|string|max:100',
            'estado'          => 'nullable|string|max:2',
            'observacoes'     => 'nullable|string',
        ]);

        $temOsEmAndamento = $cliente->ordens()->whereNotIn('status', \App\Models\Ordem::STATUS_FINAIS)->exists();

        if ($temOsEmAndamento) {
            $cpfMudou = ($dados['cpf_cnpj'] ?? null) !== $cliente->cpf_cnpj;

            if ($dados['nome'] !== $cliente->nome || $cpfMudou) {
                return back()->withInput()->withErrors([
                    'nome' => 'Nome e CPF/CNPJ não podem ser alterados enquanto o cliente tiver uma OS em andamento.',
                ]);
            }
        }

        $cliente->update($dados);

        return redirect()->route('app.clientes.show', $cliente)
            ->with('success', 'Cliente atualizado com sucesso.');
    }

    /**
     * Remove o cliente e todos os dados relacionados (cascade definido nas migrations).
     */
    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('app.clientes.index')
            ->with('success', 'Cliente removido com sucesso.');
    }

    /**
     * Lista todos os equipamentos já cadastrados para o cliente.
     */
    public function equipamentos(Cliente $cliente): View
    {
        $equipamentos = $cliente->equipamentos()->latest()->get();

        return view('app.clientes.equipamentos', compact('cliente', 'equipamentos'));
    }
}
