<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ordens\StoreOrdemRequest;
use App\Http\Requests\Ordens\UpdateOrdemRequest;
use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\Ordem;
use App\Models\OrdemHistorico;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrdemController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $ordens = Ordem::query()
            ->with(['cliente', 'equipamento', 'tecnico'])
            // Técnico só vê as próprias OS
            ->when($user->isTecnico(), fn ($q) =>
                $q->where('tecnico_id', $user->id)
            )
            ->when($request->filled('busca'), function ($q) use ($request) {
                $q->where('numero', 'like', "%{$request->busca}%")
                  ->orWhereHas('cliente', fn ($c) =>
                      $c->where('nome', 'like', "%{$request->busca}%")
                  );
            })
            ->when($request->filled('status'), fn ($q) =>
                $q->where('status', $request->status)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pages.ordens.index', [
            'ordens'  => $ordens,
            'status'  => Ordem::STATUS,
            'current' => $request->only('busca', 'status'),
        ]);
    }

    public function create(): View
    {
        $clientesJson = Cliente::orderBy('nome')
            ->get(['id', 'nome', 'telefone'])
            ->map(function ($c) {
                $palavras  = explode(' ', trim($c->nome));
                $iniciais  = strtoupper(substr($palavras[0], 0, 1));
                $iniciais .= isset($palavras[1]) ? strtoupper(substr($palavras[1], 0, 1)) : '';
                return [
                    'id'       => $c->id,
                    'nome'     => $c->nome,
                    'telefone' => $c->telefone ?? '',
                    'iniciais' => $iniciais,
                ];
            })
            ->values()
            ->toJson();

        return view('pages.ordens.create', [
            'clientesJson' => $clientesJson,
            'status'       => Ordem::STATUS,
            'tecnicos'     => User::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreOrdemRequest $request): RedirectResponse
    {
        $dados = $request->validated();

        // 1. Criar cliente novo se necessário
        if ($dados['tipo_cliente'] === 'novo') {
            $cliente = Cliente::create([
                'nome'     => $dados['novo_cliente_nome'],
                'telefone' => $dados['novo_cliente_telefone'] ?? null,
                'email'    => $dados['novo_cliente_email'] ?? null,
                'cpf_cnpj' => $dados['novo_cliente_cpf'] ?? null,
                'cidade'   => $dados['novo_cliente_cidade'] ?? null,
                'estado'   => $dados['novo_cliente_estado'] ?? null,
            ]);
            $dados['cliente_id'] = $cliente->id;
        }

        // 2. Criar equipamento inline
        if (!empty($dados['equipamento_tipo'])) {
            $equipamento = Equipamento::create([
                'cliente_id'       => $dados['cliente_id'],
                'tipo'             => $dados['equipamento_tipo'],
                'marca'            => $dados['equipamento_marca'] ?? null,
                'modelo'           => $dados['equipamento_modelo'] ?? null,
                'numero_serie'     => $dados['equipamento_serie'] ?? null,
                'acessorios'       => $dados['equipamento_acessorios'] ?? null,
                'condicao_entrada' => $dados['equipamento_condicao'] ?? null,
                'em_garantia'      => !empty($dados['equipamento_garantia']),
            ]);
            $dados['equipamento_id'] = $equipamento->id;
        }

        // 3. Criar a OS
        $campos = [
            'cliente_id',
            'equipamento_id',
            'tecnico_id',
            'status',
            'problema_relatado',
            'diagnostico',
            'valor_servico',
            'valor_pecas',
            'desconto',
            'previsao_entrega',
            'observacoes'
        ];

        $ordem = Ordem::create(array_intersect_key($dados, array_flip($campos)));

        OrdemHistorico::create([
            'ordem_id'        => $ordem->id,
            'user_id'         => auth()->id(),
            'status_anterior' => null,
            'status_novo'     => $ordem->status,
            'observacao'      => 'Entrada registrada.',
        ]);

        return redirect()
            ->route('ordens.show', $ordem)
            ->with('success', "OS {$ordem->numero} criada com sucesso.");
    }

    public function show(Ordem $ordem): View
    {
        $ordem->load(['cliente', 'equipamento', 'tecnico', 'historico.usuario']);

        return view('pages.ordens.show', [
            'ordem'  => $ordem,
            'status' => Ordem::STATUS,
        ]);
    }

    /**
     * 
     */
    public function edit(Ordem $ordem): View
    {
        $ordem->load(['cliente', 'equipamento']);

        return view('pages.ordens.edit', [
            'ordem'       => $ordem,
            'clientes'    => Cliente::orderBy('nome')->get(['id', 'nome']),
            'equipamentos'=> Equipamento::where('cliente_id', $ordem->cliente_id)->get(),
            'status'      => Ordem::STATUS,
            'tecnicos'    => User::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * 
     */
    public function update(UpdateOrdemRequest $request, Ordem $ordem): RedirectResponse
    {
        $statusAnterior = $ordem->status;
        $dados = $request->validated();

        $ordem->update($dados);

        if (isset($dados['status']) && $dados['status'] !== $statusAnterior) {
            OrdemHistorico::create([
                'ordem_id'        => $ordem->id,
                'user_id'         => auth()->id(),
                'status_anterior' => $statusAnterior,
                'status_novo'     => $dados['status'],
                'observacao'      => $dados['observacao_status'] ?? null,
            ]);

            if ($dados['status'] === 'finalizado') {
                $ordem->update(['finalizado_em' => now()]);
            }
        }

        return redirect()
            ->route('ordens.show', $ordem)
            ->with('success', 'Ordem de serviço atualizada.');
    }

    /**
     * 
     */
    public function destroy(Ordem $ordem): RedirectResponse
    {
        $numero = $ordem->numero;
        $ordem->delete();

        return redirect()
            ->route('ordens.index')
            ->with('success', "OS {$numero} removida.");
    }

    /**
     * Retorna equipamentos de um cliente via JSON (usado no create/edit)
     */
    public function equipamentosPorCliente(Cliente $cliente)
    {
        return response()->json(
            $cliente->equipamentos()->get(['id', 'tipo', 'marca', 'modelo'])
        );
    }
}
