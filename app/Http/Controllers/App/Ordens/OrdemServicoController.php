<?php

namespace App\Http\Controllers\App\Ordens;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\Mensagem;
use App\Models\Ordem;
use App\Models\OrdemArquivo;
use App\Models\OrdemHistorico;
use App\Models\User;
use App\Notifications\OrdemCriada;
use App\Notifications\OrdemStatusAlterado;
use App\Services\N8nService;
use App\Services\WhatsappService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

/**
 * CRUD completo de Ordens de Serviço (OS).
 *
 * Fluxo de criação de OS:
 *   1. O técnico preenche o formulário com dados do cliente, equipamento e defeito.
 *   2. O controller encontra ou cria o cliente pelo CPF (upsert).
 *   3. Cria o equipamento e a OS linkados ao cliente.
 *   4. Gera o primeiro registro em OrdemHistorico (status "entrada").
 *   5. Salva fotos de entrada se enviadas.
 *   6. Notifica gerentes e o técnico atribuído.
 *   7. Redireciona para a OS criada com link pronto para WhatsApp.
 *
 * Rotas (prefixo: app/ordens-servico):
 * - GET    /                         → index()
 * - GET    /nova                     → create()
 * - POST   /                         → store()
 * - GET    /{ordemServico}           → show()
 * - GET    /{ordemServico}/editar    → edit()
 * - PUT    /{ordemServico}           → update()
 * - DELETE /{ordemServico}           → destroy()
 * - PUT    /{ordemServico}/status    → updateStatus()
 * - POST   /{ordemServico}/mensagem  → storeMensagem()
 * - GET    /{ordemServico}/imprimir  → print()
 */
class OrdemServicoController extends Controller
{
    public function __construct(private N8nService $n8n) {}
    /**
     * Lista paginada de OS com filtros por busca e status.
     * Técnicos veem apenas suas próprias OS.
     */
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

    /**
     * Formulário de nova OS com lista de clientes pré-carregada para o Alpine.js.
     */
    public function create(): View
    {
        $clientes = Cliente::orderBy('nome')
            ->get(['id', 'nome', 'telefone', 'cpf_cnpj', 'data_nascimento',
                   'email', 'cidade', 'estado', 'cep', 'endereco', 'numero', 'complemento', 'bairro']);

        $clientesData = $clientes->map(fn ($c) => [
            'id'              => $c->id,
            'nome'            => $c->nome,
            'telefone'        => $c->telefone ?? '',
            'email'           => $c->email ?? '',
            'cpf_cnpj'        => $c->cpf_cnpj ?? '',
            'cpf_limpo'       => preg_replace('/\D/', '', $c->cpf_cnpj ?? ''),
            'data_nascimento' => $c->data_nascimento?->format('Y-m-d') ?? '',
            'cidade'          => $c->cidade ?? '',
            'estado'          => $c->estado ?? '',
            'cep'             => $c->cep ?? '',
            'endereco'        => $c->endereco ?? '',
            'numero'          => $c->numero ?? '',
            'complemento'     => $c->complemento ?? '',
            'bairro'          => $c->bairro ?? '',
            'iniciais'        => strtoupper(substr($c->nome, 0, 2)),
        ])->values()->all();

        return view('app.ordens.create', [
            'clientesData' => $clientesData,
            'tecnicos'     => User::orderBy('name')->get(['id', 'name']),
            'status'       => Ordem::STATUS,
        ]);
    }

    /**
     * Cria uma nova OS — encontra ou cria cliente por CPF, cria equipamento,
     * gera histórico inicial, faz upload de fotos e envia notificações.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nome'                => 'required|string|max:255',
            'cpf_cnpj'            => 'nullable|string|max:20',
            'data_nascimento'     => 'nullable|date|before:today',
            'telefone'            => 'required|string|max:20',
            'email'               => 'nullable|email|max:255',
            'cep'                 => 'nullable|string|max:10',
            'endereco'            => 'nullable|string|max:255',
            'numero'              => 'nullable|string|max:20',
            'complemento'         => 'nullable|string|max:100',
            'bairro'              => 'nullable|string|max:100',
            'cidade'              => 'nullable|string|max:100',
            'estado'              => 'nullable|string|max:2',
            'equipamento_tipo'    => 'required|string|max:80',
            'equipamento_marca'   => 'nullable|string|max:80',
            'equipamento_modelo'  => 'nullable|string|max:120',
            'equipamento_serie'   => 'nullable|string|max:100',
            'forma_entrada'       => 'required|string|in:balcao,coleta,motoboy,correios,outro',
            'estado_fisico'       => 'nullable|string|max:255',
            'acessorios'          => 'nullable|string',
            'tecnico_id'          => 'nullable|exists:users,id',
            'problema_relatado'   => 'required|string',
            'observacoes'         => 'nullable|string',
            'previsao_entrega'    => 'nullable|date',
            'fotos.*'             => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ], [
            'nome.required'              => 'O nome do cliente é obrigatório.',
            'telefone.required'          => 'O telefone é obrigatório.',
            'equipamento_tipo.required'  => 'Informe o tipo do equipamento.',
            'forma_entrada.required'     => 'Selecione como o equipamento chegou.',
            'problema_relatado.required' => 'Descreva o defeito relatado.',
        ]);

        // Encontra ou cria cliente por CPF (upsert)
        $cpfLimpo = preg_replace('/\D/', '', $request->cpf_cnpj ?? '');
        $cliente  = null;

        if ($cpfLimpo) {
            $cliente = Cliente::whereRaw("REGEXP_REPLACE(cpf_cnpj, '[^0-9]', '') = ?", [$cpfLimpo])->first();
        }

        $clienteData = [
            'nome'            => $request->nome,
            'telefone'        => $request->telefone,
            'email'           => $request->email,
            'cpf_cnpj'        => $request->cpf_cnpj ?: null,
            'data_nascimento' => $request->data_nascimento ?: null,
            'endereco'        => $request->endereco,
            'numero'          => $request->numero,
            'complemento'     => $request->complemento,
            'bairro'          => $request->bairro,
            'cidade'          => $request->cidade,
            'estado'          => $request->estado,
            'cep'             => $request->cep,
        ];

        $cliente = $cliente ? tap($cliente)->update($clienteData) : Cliente::create($clienteData);

        $equipamento = Equipamento::create([
            'cliente_id'       => $cliente->id,
            'tipo'             => $request->equipamento_tipo,
            'marca'            => $request->equipamento_marca,
            'modelo'           => $request->equipamento_modelo,
            'numero_serie'     => $request->equipamento_serie,
            'forma_entrada'    => $request->forma_entrada,
            'estado_fisico'    => $request->estado_fisico,
            'acessorios'       => $request->acessorios,
            'condicao_entrada' => $request->estado_fisico,
        ]);

        $ordem = Ordem::create([
            'cliente_id'        => $cliente->id,
            'equipamento_id'    => $equipamento->id,
            'tecnico_id'        => $request->tecnico_id ?: null,
            'status'            => 'entrada',
            'problema_relatado' => $request->problema_relatado,
            'observacoes'       => $request->observacoes,
            'previsao_entrega'  => $request->previsao_entrega ?: null,
        ]);

        OrdemHistorico::create([
            'ordem_id'        => $ordem->id,
            'user_id'         => auth()->id(),
            'status_anterior' => null,
            'status_novo'     => 'entrada',
            'observacao'      => 'Equipamento recebido e OS aberta.',
        ]);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store("ordens/{$ordem->id}", 'private');
                $ordem->arquivos()->create([
                    'user_id'       => auth()->id(),
                    'tipo'          => 'foto_entrada',
                    'nome_original' => $foto->getClientOriginalName(),
                    'caminho'       => $path,
                    'mime_type'     => $foto->getMimeType(),
                    'tamanho'       => $foto->getSize(),
                ]);
            }
        }

        $gerentes = User::where('role', 'gerente')->where('id', '!=', auth()->id())->get();
        Notification::send($gerentes, new OrdemCriada($ordem));

        if ($ordem->tecnico_id && $ordem->tecnico_id !== auth()->id()) {
            $ordem->tecnico->notify(new OrdemCriada($ordem));
        }

        $this->n8n->dispatch('os.criada', $ordem->load('cliente', 'equipamento'));

        // Monta link do WhatsApp para envio manual pelo atendente
        $nomeEquip  = trim("{$equipamento->marca} {$equipamento->modelo}") ?: $equipamento->tipo;
        $linkPortal = route('portal.token', $ordem->token);
        $cpfExibido = $request->cpf_cnpj ?: '—';
        $nascimento = $request->data_nascimento
            ? \Carbon\Carbon::parse($request->data_nascimento)->format('d/m/Y')
            : '—';

        $msgWa = urlencode(
            "Olá, {$cliente->nome}!\n" .
            "Seu equipamento foi recebido com sucesso em nossa assistência técnica.\n\n" .
            "📄 OS: {$ordem->numero}\n" .
            "📱 Equipamento: {$nomeEquip}\n" .
            "📍 Status atual: Equipamento recebido\n\n" .
            "Para acompanhar sua OS, acesse o portal do cliente:\n" .
            "CPF: {$cpfExibido}\n" .
            "Senha: sua data de nascimento ({$nascimento})\n\n" .
            "🔗 Link do portal: {$linkPortal}\n\n" .
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

    /**
     * Exibe o detalhe completo da OS com histórico, arquivos e mensagens.
     */
    public function show(Ordem $ordemServico): View
    {
        $ordemServico->load(['cliente', 'equipamento', 'tecnico', 'historico.usuario', 'arquivos.usuario', 'mensagens.autor']);

        return view('app.ordens.show', [
            'ordem'    => $ordemServico,
            'status'   => Ordem::STATUS,
            'tipos'    => OrdemArquivo::TIPOS,
            'tecnicos' => User::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Adiciona uma mensagem interna do técnico à OS (sem passar pelo WhatsApp).
     */
    public function storeMensagem(Request $request, Ordem $ordemServico): RedirectResponse
    {
        $request->validate(['conteudo' => 'required|string|max:2000']);

        Mensagem::create([
            'ordem_id' => $ordemServico->id,
            'user_id'  => auth()->id(),
            'tipo'     => 'tecnico',
            'conteudo' => $request->conteudo,
        ]);

        return back()->with('success', 'Mensagem enviada.')->withFragment('mensagens');
    }

    /**
     * Exibe a página de impressão da OS (sem layout padrão).
     */
    public function print(Ordem $ordemServico): View
    {
        $ordemServico->load(['cliente', 'equipamento', 'tecnico']);

        return view('app.ordens.print', ['ordem' => $ordemServico]);
    }

    /**
     * Formulário de edição da OS.
     * OS finalizada ou cancelada não pode mais ser editada.
     */
    public function edit(Ordem $ordemServico): View|RedirectResponse
    {
        if ($ordemServico->bloqueada_para_edicao) {
            return redirect()->route('app.os.show', $ordemServico)
                ->with('error', 'Esta OS está ' . strtolower($ordemServico->status_label) . ' e não pode mais ser editada.');
        }

        $ordemServico->load(['cliente', 'equipamento']);

        return view('app.ordens.edit', [
            'ordem'    => $ordemServico,
            'clientes' => Cliente::orderBy('nome')->get(['id', 'nome']),
            'tecnicos' => User::orderBy('name')->get(['id', 'name']),
            'status'   => Ordem::STATUS,
        ]);
    }

    /**
     * Atualiza todos os campos editáveis da OS, incluindo status.
     * Se o status mudou, cria registro no histórico e notifica os envolvidos.
     */
    public function update(Request $request, Ordem $ordemServico): RedirectResponse
    {
        if ($ordemServico->bloqueada_para_edicao) {
            return redirect()->route('app.os.show', $ordemServico)
                ->with('error', 'Esta OS está ' . strtolower($ordemServico->status_label) . ' e não pode mais ser editada.');
        }

        $dados = $request->validate([
            'tecnico_id'        => 'nullable|exists:users,id',
            'status'            => 'required|string',
            'problema_relatado' => 'required|string',
            'diagnostico'       => 'nullable|string',
            'solucao'           => 'nullable|string',
            'valor_servico'     => 'nullable|numeric|min:0',
            'valor_pecas'       => 'nullable|numeric|min:0',
            'desconto'          => 'nullable|numeric|min:0',
            'status_orcamento'  => 'nullable|string|in:pendente,aprovado,recusado',
            'previsao_entrega'  => 'nullable|date',
            'observacoes'       => 'nullable|string',
            'observacao_status' => 'nullable|string',
        ]);

        $statusAnterior   = $ordemServico->status;
        $orcamentoAnterior = $ordemServico->status_orcamento;
        $ordemServico->update($dados);

        // Notifica cliente via WhatsApp quando orçamento é definido como pendente
        if (($dados['status_orcamento'] ?? null) === 'pendente' && $orcamentoAnterior !== 'pendente') {
            $this->notificarOrcamentoWhatsapp($ordemServico->fresh(['cliente', 'equipamento']));
        }

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

    /**
     * Atualização rápida de status (sem editar outros campos).
     * Usada no botão de avanço rápido de status na listagem.
     */
    public function updateStatus(Request $request, Ordem $ordemServico): RedirectResponse
    {
        if ($ordemServico->bloqueada_para_edicao) {
            return back()->with('error', 'Esta OS está ' . strtolower($ordemServico->status_label) . ' e não pode mais ser editada.');
        }

        $dados = $request->validate([
            'status'     => 'required|string',
            'observacao' => 'nullable|string',
        ]);

        $anterior = $ordemServico->status;
        $ordemServico->update(['status' => $dados['status']]);

        if ($dados['status'] !== $anterior) {
            OrdemHistorico::create([
                'ordem_id'        => $ordemServico->id,
                'user_id'         => auth()->id(),
                'status_anterior' => $anterior,
                'status_novo'     => $dados['status'],
                'observacao'      => $dados['observacao'] ?? null,
            ]);
        }

        if ($dados['status'] === 'finalizado') {
            $ordemServico->update(['finalizado_em' => now()]);
        }

        $this->notificarMudancaStatus($ordemServico, $anterior, $dados['status']);

        if ($dados['status'] !== $anterior) {
            $this->n8n->dispatch('os.status_alterado', $ordemServico->load('cliente', 'equipamento'), [
                'status_anterior'       => $anterior,
                'status_anterior_label' => Ordem::STATUS[$anterior]['label'] ?? $anterior,
            ]);
        }

        return back()->with('success', 'Status atualizado com sucesso.');
    }

    /**
     * Envia notificação de mudança de status para os usuários relevantes.
     * Técnico notifica gerentes; gerente notifica o técnico da OS.
     */
    private function notificarMudancaStatus(Ordem $ordem, string $anterior, string $novo): void
    {
        $notificacao = new OrdemStatusAlterado($ordem, $anterior, $novo);
        $usuario     = auth()->user();

        if ($usuario->isTecnico()) {
            $gerentes = User::where('role', 'gerente')->where('id', '!=', $usuario->id)->get();
            Notification::send($gerentes, $notificacao);
        } elseif ($ordem->tecnico_id && $ordem->tecnico_id !== $usuario->id) {
            $ordem->tecnico->notify($notificacao);
        }
    }

    /** Envia notificação de orçamento pendente para o cliente via WhatsApp. */
    private function notificarOrcamentoWhatsapp(Ordem $ordem): void
    {
        $cliente = $ordem->cliente;
        if (! $cliente?->telefone) return;

        $whatsapp = app(WhatsappService::class);
        $device   = $ordem->equipamento
            ? trim("{$ordem->equipamento->marca} {$ordem->equipamento->modelo}")
            : 'Equipamento';

        $total   = 'R$ ' . number_format($ordem->total, 2, ',', '.');
        $servico = $ordem->valor_servico > 0 ? 'R$ ' . number_format($ordem->valor_servico, 2, ',', '.') : null;
        $pecas   = $ordem->valor_pecas   > 0 ? 'R$ ' . number_format($ordem->valor_pecas,   2, ',', '.') : null;

        $msg  = "💰 *Orçamento pronto para aprovação!*\n";
        $msg .= "────────────────────\n";
        $msg .= "🔢 OS: *{$ordem->numero}*\n";
        $msg .= "📱 Equipamento: *{$device}*\n";
        if ($ordem->diagnostico) $msg .= "🔬 Diagnóstico: {$ordem->diagnostico}\n";
        $msg .= "────────────────────\n";
        if ($servico) $msg .= "🔧 Mão de obra: *{$servico}*\n";
        if ($pecas)   $msg .= "🔩 Peças: *{$pecas}*\n";
        $msg .= "💵 *Total: {$total}*\n";
        $msg .= "────────────────────\n\n";
        $msg .= "Para *AUTORIZAR* o serviço, responda: *SIM*\n";
        $msg .= "Para *RECUSAR*, responda: *NÃO*\n\n";
        $msg .= "_Você também pode responder pelo portal:_\n";
        $msg .= url("/portal/{$ordem->token}");

        $whatsapp->send($cliente->telefone, $msg);
    }

    /**
     * Remove a OS permanentemente.
     */
    public function destroy(Ordem $ordemServico): RedirectResponse
    {
        $numero = $ordemServico->numero;
        $ordemServico->delete();

        return redirect()->route('app.os.index')
            ->with('success', "OS {$numero} removida com sucesso.");
    }
}
