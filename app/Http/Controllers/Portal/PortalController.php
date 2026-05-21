<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Mensagem;
use App\Models\Ordem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class PortalController extends Controller
{
    /** Portal autenticado — lista de OS do cliente */
    public function index(Request $request): View
    {
        $user    = auth()->user();
        $cliente = Cliente::where('email', $user->email)->first();

        $ordens = $cliente
            ? Ordem::with(['equipamento', 'historico'])
                ->where('cliente_id', $cliente->id)
                ->when($request->filled('status'), fn ($q) =>
                    $q->where('status', $request->status)
                )
                ->latest()
                ->paginate(10)
                ->withQueryString()
            : new LengthAwarePaginator([], 0, 10);

        $stats = $cliente ? [
            'total'       => Ordem::where('cliente_id', $cliente->id)->count(),
            'abertas'     => Ordem::where('cliente_id', $cliente->id)->whereNotIn('status', ['finalizado', 'cancelado'])->count(),
            'finalizadas' => Ordem::where('cliente_id', $cliente->id)->where('status', 'finalizado')->count(),
        ] : ['total' => 0, 'abertas' => 0, 'finalizadas' => 0];

        return view('portal.index', compact('ordens', 'cliente', 'stats'));
    }

    /** Portal público — acesso via token curto /r/{token} */
    public function show(Ordem $ordemServico)
{
    $cliente = $ordemServico->cliente;

    $waUrl = null;

    if ($cliente?->telefone) {
        $telefone = preg_replace('/\D/', '', $cliente->telefone);

        $mensagem = urlencode(
            "Olá {$cliente->nome}, sua ordem {$ordemServico->codigo_publico} está disponível no portal."
        );

        $waUrl = "https://wa.me/55{$telefone}?text={$mensagem}";
    }

    $statusDot = [
        'aberto'      => 'bg-sky-400',
        'analise'     => 'bg-amber-400',
        'aprovado'    => 'bg-emerald-400',
        'andamento'   => 'bg-blue-400',
        'finalizado'  => 'bg-green-400',
        'cancelado'   => 'bg-red-400',
    ];

    $statusBg = [
        'aberto'      => 'bg-sky-500/10 text-sky-300 ring-1 ring-sky-500/20',
        'analise'     => 'bg-amber-500/10 text-amber-300 ring-1 ring-amber-500/20',
        'aprovado'    => 'bg-emerald-500/10 text-emerald-300 ring-1 ring-emerald-500/20',
        'andamento'   => 'bg-blue-500/10 text-blue-300 ring-1 ring-blue-500/20',
        'finalizado'  => 'bg-green-500/10 text-green-300 ring-1 ring-green-500/20',
        'cancelado'   => 'bg-red-500/10 text-red-300 ring-1 ring-red-500/20',
    ];

    $isFinished = $ordemServico->status === 'finalizado';
    $isCancelled = $ordemServico->status === 'cancelado';

    $steps = [
    [
        'key' => 'aberto',
        'label' => 'Aberta',
        'desc' => 'Ordem criada e aguardando análise técnica.',
    ],
    [
        'key' => 'analise',
        'label' => 'Análise',
        'desc' => 'Equipamento em diagnóstico pela assistência.',
    ],
    [
        'key' => 'aprovado',
        'label' => 'Aprovado',
        'desc' => 'Orçamento aprovado para execução do serviço.',
    ],
    [
        'key' => 'andamento',
        'label' => 'Em andamento',
        'desc' => 'Reparo sendo realizado pela equipe técnica.',
    ],
    [
        'key' => 'finalizado',
        'label' => 'Finalizado',
        'desc' => 'Serviço concluído e equipamento disponível.',
    ],
];

    $currentStep = collect($steps)
    ->search(fn ($step) => $step['key'] === $ordemServico->status);

if ($currentStep === false) {
    $currentStep = 0;
}

    if ($currentStep === false) {
        $currentStep = 0;
    }

    return view('portal.show', [
        'ordemServico' => $ordemServico,
        'ordem' => $ordemServico,
        'waUrl' => $waUrl,

        'statusDot' => $statusDot,
        'statusBg' => $statusBg,

        'isFinished' => $isFinished,
        'isCancelled' => $isCancelled,

        'steps' => $steps,
        'currentStep' => $currentStep,
    ]);
}
    
}
