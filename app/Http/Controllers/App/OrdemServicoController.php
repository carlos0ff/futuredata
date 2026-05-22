<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;

use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\Ordem;
use App\Models\OrdemArquivo;
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

        $base = Ordem::query()
            ->when($user->isTecnico(), fn ($q) => $q->where('tecnico_id', $user->id));

        $stats = [
            'total'     => (clone $base)->count(),
            'abertas'   => (clone $base)->whereNotIn('status', ['finalizado', 'cancelado'])->count(),
            'execucao'  => (clone $base)->where('status', 'execucao')->count(),
            'atrasadas' => (clone $base)
                ->whereNotIn('status', ['finalizado', 'cancelado'])
                ->whereNotNull('previsao_entrega')
                ->whereDate('previsao_entrega', '<', today())
                ->count(),
        ];

        $ordens = (clone $base)
            ->with(['cliente', 'equipamento', 'tecnico'])
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
            'stats'   => $stats,
            'status'  => Ordem::STATUS,
            'current' => $request->only('busca', 'status'),
        ]);
    }

    public function create(): View
    {
        $clientes = Cliente::orderBy('nome')
            ->get(['id', 'nome', 'telefone', 'cpf_cnpj', 'data_nascimento',
                   'email', 'cidade', 'estado', 'cep', 'endereco', 'numero', 'complemento', 'bairro']);

        $clientesJson = $clientes->map(fn($c) => [
            'id'             => $c->id,
            'nome'           => $c->nome,
            'telefone'       => $c->telefone ?? '',
            'email'          => $c->email ?? '',
            'cpf_cnpj'       => $c->cpf_cnpj ?? '',
            'cpf_limpo'      => preg_replace('/\D/', '', $c->cpf_cnpj ?? ''),
            'data_nascimento'=> $c->data_nascimento?->format('Y-m-d') ?? '',
            'cidade'         => $c->cidade ?? '',
            'estado'         => $c->estado ?? '',
            'cep'            => $c->cep ?? '',
            'endereco'       => $c->endereco ?? '',
            'numero'         => $c->numero ?? '',
            'complemento'    => $c->complemento ?? '',
            'bairro'         => $c->bairro ?? '',
            'iniciais'       => strtoupper(substr($c->nome, 0, 2)),
        ])->toJson();

        return view('app.ordens.create', [
            'clientesJson' => $clientesJson,
            'tecnicos'     => User::orderBy('name')->get(['id', 'name']),
            'status'       => Ordem::STATUS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Cliente
            'nome'             => 'required|string|max:255',
            'cpf_cnpj'         => 'nullable|string|max:20',
            'data_nascimento'  => 'nullable|date|before:today',
            'telefone'         => 'required|string|max:20',
            'email'            => 'nullable|email|max:255',
            'cep'              => 'nullable|string|max:10',
            'endereco'         => 'nullable|string|max:255',
            'numero'           => 'nullable|string|max:20',
            'complemento'      => 'nullable|string|max:100',
            'bairro'           => 'nullable|string|max:100',
            'cidade'           => 'nullable|string|max:100',
            'estado'           => 'nullable|string|max:2',
            // Equipamento
            'equipamento_tipo'    => 'required|string|max:80',
            'equipamento_marca'   => 'nullable|string|max:80',
            'equipamento_modelo'  => 'nullable|string|max:120',
            'equipamento_serie'   => 'nullable|string|max:100',
            'forma_entrada'       => 'required|string|in:balcao,coleta,motoboy,correios,outro',
            'estado_fisico'       => 'nullable|string|max:255',
            'acessorios'          => 'nullable|string',
            // Ordem
            'tecnico_id'          => 'nullable|exists:users,id',
            'problema_relatado'   => 'required|string',
            'observacoes'         => 'nullable|string',
            'previsao_entrega'    => 'nullable|date',
            // Fotos
            'fotos.*'             => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ], [
            'nome.required'             => 'O nome do cliente é obrigatório.',
            'telefone.required'         => 'O telefone é obrigatório.',
            'equipamento_tipo.required' => 'Informe o tipo do equipamento.',
            'forma_entrada.required'    => 'Selecione como o equipamento chegou.',
            'problema_relatado.required'=> 'Descreva o defeito relatado.',
        ]);

        // Encontrar ou criar cliente por CPF
        $cpfLimpo = preg_replace('/\D/', '', $request->cpf_cnpj ?? '');
        $cliente  = null;

        if ($cpfLimpo) {
            $cliente = Cliente::whereRaw("REGEXP_REPLACE(cpf_cnpj, '[^0-9]', '') = ?", [$cpfLimpo])->first();
        }

        $clienteData = [
            'nome'           => $request->nome,
            'telefone'       => $request->telefone,
            'email'          => $request->email,
            'cpf_cnpj'       => $request->cpf_cnpj ?: null,
            'data_nascimento'=> $request->data_nascimento ?: null,
            'endereco'       => $request->endereco,
            'numero'         => $request->numero,
            'complemento'    => $request->complemento,
            'bairro'         => $request->bairro,
            'cidade'         => $request->cidade,
            'estado'         => $request->estado,
            'cep'            => $request->cep,
        ];

        if ($cliente) {
            $cliente->update($clienteData);
        } else {
            $cliente = Cliente::create($clienteData);
        }

        // Criar equipamento
        $equipamento = Equipamento::create([
            'cliente_id'      => $cliente->id,
            'tipo'            => $request->equipamento_tipo,
            'marca'           => $request->equipamento_marca,
            'modelo'          => $request->equipamento_modelo,
            'numero_serie'    => $request->equipamento_serie,
            'forma_entrada'   => $request->forma_entrada,
            'estado_fisico'   => $request->estado_fisico,
            'acessorios'      => $request->acessorios,
            'condicao_entrada'=> $request->estado_fisico,
        ]);

        // Criar OS
        $ordem = Ordem::create([
            'cliente_id'       => $cliente->id,
            'equipamento_id'   => $equipamento->id,
            'tecnico_id'       => $request->tecnico_id ?: null,
            'status'           => 'entrada',
            'problema_relatado'=> $request->problema_relatado,
            'observacoes'      => $request->observacoes,
            'previsao_entrega' => $request->previsao_entrega ?: null,
        ]);

        // Histórico
        OrdemHistorico::create([
            'ordem_id'        => $ordem->id,
            'user_id'         => auth()->id(),
            'status_anterior' => null,
            'status_novo'     => 'entrada',
            'observacao'      => 'Equipamento recebido e OS aberta.',
        ]);

        // Upload de fotos
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store("ordens/{$ordem->id}", 'private');
                $ordem->arquivos()->create([
                    'user_id'        => auth()->id(),
                    'tipo'           => 'foto_entrada',
                    'nome_original'  => $foto->getClientOriginalName(),
                    'caminho'        => $path,
                    'mime_type'      => $foto->getMimeType(),
                    'tamanho'        => $foto->getSize(),
                ]);
            }
        }

        // Notificações
        $gerentes = User::where('role', 'gerente')->where('id', '!=', auth()->id())->get();
        Notification::send($gerentes, new OrdemCriada($ordem));

        if ($ordem->tecnico_id && $ordem->tecnico_id !== auth()->id()) {
            $ordem->tecnico->notify(new OrdemCriada($ordem));
        }

        // Mensagem WhatsApp
        $nomeEquip  = trim("{$equipamento->marca} {$equipamento->modelo}") ?: $equipamento->tipo;
        $linkPortal = route('portal.token', $ordem->token);
        $cpfExibido = $request->cpf_cnpj ?: '—';
        $nascimento = $request->data_nascimento
            ? \Carbon\Carbon::parse($request->data_nascimento)->format('d/m/Y')
            : '—';

        $msgWa = urlencode(
            "Olá, {$cliente->nome}!\n".
            "Seu equipamento foi recebido com sucesso em nossa assistência técnica.\n\n".
            "📄 OS: {$ordem->numero}\n".
            "📱 Equipamento: {$nomeEquip}\n".
            "📍 Status atual: Equipamento recebido\n\n".
            "Para acompanhar sua OS, acesse o portal do cliente:\n".
            "CPF: {$cpfExibido}\n".
            "Senha: sua data de nascimento ({$nascimento})\n\n".
            "🔗 Link do portal: {$linkPortal}\n\n".
            "Em caso de dúvidas, estamos à disposição."
        );

        $telLimpo = preg_replace('/\D/', '', $cliente->telefone ?? '');
        $waLink   = $telLimpo ? "https://wa.me/55{$telLimpo}?text={$msgWa}" : null;

        return redirect()
            ->route('app.os.show', $ordem)
            ->with('entrada_sucesso', [
                'os'       => $ordem->numero,
                'wa_link'  => $waLink,
                'telefone' => $cliente->telefone,
                'portal'   => $linkPortal,
                'token'    => $ordem->token,
            ]);
    }

    public function show(Ordem $ordemServico): View
    {
        $ordemServico->load(['cliente', 'equipamento', 'tecnico', 'historico.usuario', 'arquivos.usuario']);

        return view('app.ordens.show', [
            'ordem'  => $ordemServico,
            'status' => Ordem::STATUS,
            'tipos'  => OrdemArquivo::TIPOS,
        ]);
    }

    public function print(Ordem $ordemServico): View
    {
        $ordemServico->load(['cliente', 'equipamento', 'tecnico']);

        return view('app.ordens.print', ['ordem' => $ordemServico]);
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
            'status_orcamento' => 'nullable|string|in:pendente,aprovado,recusado',
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
