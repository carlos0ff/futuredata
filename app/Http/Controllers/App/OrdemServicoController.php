<?php

namespace App\Http\Controllers\App;

use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\Ordem;
use App\Models\OrdemHistorico;
use App\Models\User;
use App\Notifications\OrdemCriada;
use App\Notifications\OrdemStatusAlterado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class OrdemServicoController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $ordens = Ordem::query()
            ->with(['cliente', 'equipamento', 'tecnico'])
            ->when($user->isTecnico(), fn ($q) => $q->where('tecnico_id', $user->id))
            ->when($request->filled('busca'), fn ($q) =>
                $q->where(fn ($sub) =>
                    $sub->where('numero', 'like', "%{$request->busca}%")
                        ->orWhereHas('cliente', fn ($c) =>
                            $c->where('nome', 'like', "%{$request->busca}%")
                        )
                )
            )
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('app.ordens.index', [
            'ordens'  => $ordens,
            'status'  => Ordem::STATUS,
            'current' => $request->only('busca', 'status'),
        ]);
    }

    public function create(): View
    {
        return view('app.ordens.create', [
            'clientes'  => Cliente::orderBy('nome')->get(['id', 'nome', 'telefone']),
            'tecnicos'  => User::orderBy('name')->get(['id', 'name']),
            'status'    => Ordem::STATUS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'cliente_id'       => 'required|exists:clientes,id',
            'equipamento_tipo' => 'required|string|max:100',
            'equipamento_marca'=> 'nullable|string|max:100',
            'equipamento_modelo'=>'nullable|string|max:100',
            'equipamento_serie'=> 'nullable|string|max:100',
            'tecnico_id'       => 'nullable|exists:users,id',
            'status'           => 'required|string',
            'problema_relatado'=> 'required|string',
            'diagnostico'      => 'nullable|string',
            'valor_servico'    => 'nullable|numeric|min:0',
            'valor_pecas'      => 'nullable|numeric|min:0',
            'desconto'         => 'nullable|numeric|min:0',
            'previsao_entrega' => 'nullable|date',
            'observacoes'      => 'nullable|string',
        ]);

        $equipamento = Equipamento::create([
            'cliente_id'   => $dados['cliente_id'],
            'tipo'         => $dados['equipamento_tipo'],
            'marca'        => $dados['equipamento_marca'] ?? null,
            'modelo'       => $dados['equipamento_modelo'] ?? null,
            'numero_serie' => $dados['equipamento_serie'] ?? null,
        ]);

        $ordem = Ordem::create([
            'cliente_id'       => $dados['cliente_id'],
            'equipamento_id'   => $equipamento->id,
            'tecnico_id'       => $dados['tecnico_id'] ?? null,
            'status'           => $dados['status'],
            'problema_relatado'=> $dados['problema_relatado'],
            'diagnostico'      => $dados['diagnostico'] ?? null,
            'valor_servico'    => $dados['valor_servico'] ?? 0,
            'valor_pecas'      => $dados['valor_pecas'] ?? 0,
            'desconto'         => $dados['desconto'] ?? 0,
            'previsao_entrega' => $dados['previsao_entrega'] ?? null,
            'observacoes'      => $dados['observacoes'] ?? null,
        ]);

        OrdemHistorico::create([
            'ordem_id'        => $ordem->id,
            'user_id'         => auth()->id(),
            'status_anterior' => null,
            'status_novo'     => $ordem->status,
            'observacao'      => 'Entrada registrada.',
        ]);

        $gerentes = User::where('role', 'gerente')->where('id', '!=', auth()->id())->get();
        Notification::send($gerentes, new OrdemCriada($ordem));

        if ($ordem->tecnico_id && $ordem->tecnico_id !== auth()->id()) {
            $ordem->tecnico->notify(new OrdemCriada($ordem));
        }

        return redirect()->route('app.os.show', $ordem)
            ->with('success', "OS {$ordem->numero} criada com sucesso.");
    }

    public function show(Ordem $ordemServico): View
    {
        $ordemServico->load(['cliente', 'equipamento', 'tecnico', 'historico.usuario']);

        return view('app.ordens.show', [
            'ordem'  => $ordemServico,
            'status' => Ordem::STATUS,
        ]);
    }

    public function edit(Ordem $ordemServico): View
    {
        $ordemServico->load(['cliente', 'equipamento']);

        return view('app.ordens.edit', [
            'ordem'     => $ordemServico,
            'clientes'  => Cliente::orderBy('nome')->get(['id', 'nome']),
            'tecnicos'  => User::orderBy('name')->get(['id', 'name']),
            'status'    => Ordem::STATUS,
        ]);
    }

    public function update(Request $request, Ordem $ordemServico): RedirectResponse
    {
        $dados = $request->validate([
            'tecnico_id'       => 'nullable|exists:users,id',
            'status'           => 'required|string',
            'problema_relatado'=> 'required|string',
            'diagnostico'      => 'nullable|string',
            'solucao'          => 'nullable|string',
            'valor_servico'    => 'nullable|numeric|min:0',
            'valor_pecas'      => 'nullable|numeric|min:0',
            'desconto'         => 'nullable|numeric|min:0',
            'previsao_entrega' => 'nullable|date',
            'observacoes'      => 'nullable|string',
            'observacao_status'=> 'nullable|string',
        ]);

        $statusAnterior = $ordemServico->status;
        $ordemServico->update($dados);

        if ($dados['status'] !== $statusAnterior) {
            OrdemHistorico::create([
                'ordem_id'        => $ordemServico->id,
                'user_id'         => auth()->id(),
                'status_anterior' => $statusAnterior,
                'status_novo'     => $dados['status'],
                'observacao'      => $dados['observacao_status'] ?? null,
            ]);

            if ($dados['status'] === 'finalizado') {
                $ordemServico->update(['finalizado_em' => now()]);
            }

            $this->notificarMudancaStatus($ordemServico, $statusAnterior, $dados['status']);
        }

        return redirect()->route('app.os.show', $ordemServico)
            ->with('success', 'Ordem de serviço atualizada com sucesso.');
    }

    public function updateStatus(Request $request, Ordem $ordemServico): RedirectResponse
    {
        $dados = $request->validate([
            'status'    => 'required|string',
            'observacao'=> 'nullable|string',
        ]);

        $anterior = $ordemServico->status;
        $ordemServico->update(['status' => $dados['status']]);

        OrdemHistorico::create([
            'ordem_id'        => $ordemServico->id,
            'user_id'         => auth()->id(),
            'status_anterior' => $anterior,
            'status_novo'     => $dados['status'],
            'observacao'      => $dados['observacao'] ?? null,
        ]);

        if ($dados['status'] === 'finalizado') {
            $ordemServico->update(['finalizado_em' => now()]);
        }

        $this->notificarMudancaStatus($ordemServico, $anterior, $dados['status']);

        return back()->with('success', 'Status atualizado com sucesso.');
    }

    private function notificarMudancaStatus(Ordem $ordem, string $anterior, string $novo): void
    {
        $notificacao = new OrdemStatusAlterado($ordem, $anterior, $novo);
        $usuario     = auth()->user();

        if ($usuario->isTecnico()) {
            $gerentes = User::where('role', 'gerente')->where('id', '!=', $usuario->id)->get();
            Notification::send($gerentes, $notificacao);
        } else {
            if ($ordem->tecnico_id && $ordem->tecnico_id !== $usuario->id) {
                $ordem->tecnico->notify($notificacao);
            }
        }
    }

    public function destroy(Ordem $ordemServico): RedirectResponse
    {
        $numero = $ordemServico->numero;
        $ordemServico->delete();

        return redirect()->route('app.os.index')
            ->with('success', "OS {$numero} removida com sucesso.");
    }
}
