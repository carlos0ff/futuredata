@extends('layouts.app')
@section('title', 'Relatórios')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [['label' => 'Relatórios']]
    ])
@endsection

@section('content')

{{-- Page header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Relatórios</h1>
        <p class="mt-0.5 text-[13px] text-slate-500">Visão geral de desempenho e indicadores da assistência técnica.</p>
    </div>
    <form method="GET" action="{{ route('app.relatorios.index') }}" class="flex items-center gap-2">
        <select name="mes"
                class="h-9 appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" @selected(($mes ?? now()->month) == $m)>
                    {{ \Carbon\Carbon::create()->month($m)->locale('pt_BR')->isoFormat('MMMM') }}
                </option>
            @endforeach
        </select>
        <select name="ano"
                class="h-9 appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            @foreach(range(now()->year - 2, now()->year) as $y)
                <option value="{{ $y }}" @selected(($ano ?? now()->year) == $y)>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit"
                class="h-9 rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
            Aplicar
        </button>
    </form>
</div>

{{-- Stats --}}
<div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Total de OS</p>
        <p class="mt-1.5 text-[32px] font-bold leading-none text-slate-900">{{ $stats['total'] ?? 0 }}</p>
    </div>
    <div class="rounded-2xl border border-amber-100 bg-amber-50/50 p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Em Aberto</p>
        <p class="mt-1.5 text-[32px] font-bold leading-none text-amber-700">{{ $stats['em_aberto'] ?? 0 }}</p>
    </div>
    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Finalizadas (mês)</p>
        <p class="mt-1.5 text-[32px] font-bold leading-none text-emerald-700">{{ $stats['finalizadas_mes'] ?? 0 }}</p>
    </div>
    <div class="rounded-2xl border border-purple-100 bg-purple-50/50 p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Faturamento (mês)</p>
        <p class="mt-1.5 text-[26px] font-bold leading-none text-purple-700">
            R$ {{ number_format($stats['faturamento'] ?? 0, 2, ',', '.') }}
        </p>
    </div>
</div>

<div class="grid grid-cols-1 gap-5 lg:grid-cols-2 mb-5">

    {{-- OS por Status --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-[14px] font-bold text-slate-900">OS por Status</h2>
            <p class="text-[12px] text-slate-500 mt-0.5">Distribuição das ordens pelo status atual.</p>
        </div>
        <div class="p-6 space-y-3">
            @php
            $statusConfig = [
                'entrada'            => ['label' => 'Entrada',         'color' => 'bg-slate-500',   'light' => 'bg-slate-100'],
                'analise'            => ['label' => 'Em Análise',      'color' => 'bg-amber-500',   'light' => 'bg-amber-100'],
                'execucao'           => ['label' => 'Em Execução',     'color' => 'bg-blue-500',    'light' => 'bg-blue-100'],
                'aguardando_cliente' => ['label' => 'Aguardando',      'color' => 'bg-purple-500',  'light' => 'bg-purple-100'],
                'em_teste'           => ['label' => 'Em Teste',        'color' => 'bg-cyan-500',    'light' => 'bg-cyan-100'],
                'finalizado'         => ['label' => 'Finalizado',      'color' => 'bg-emerald-500', 'light' => 'bg-emerald-100'],
                'cancelado'          => ['label' => 'Cancelado',       'color' => 'bg-red-500',     'light' => 'bg-red-100'],
            ];
            $totalOS = max(array_sum(array_column($porStatus ?? [], 'total')), 1);
            @endphp

            @foreach($statusConfig as $key => $cfg)
            @php $count = collect($porStatus ?? [])->firstWhere('status', $key)?->total ?? 0; @endphp
            @php $pct = round(($count / $totalOS) * 100); @endphp
            <div>
                <div class="mb-1 flex items-center justify-between text-[12.5px]">
                    <span class="font-medium text-slate-700">{{ $cfg['label'] }}</span>
                    <span class="font-semibold text-slate-900">
                        {{ $count }}
                        <span class="font-normal text-slate-400">({{ $pct }}%)</span>
                    </span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full {{ $cfg['light'] }}">
                    <div class="h-2 rounded-full {{ $cfg['color'] }} transition-all duration-500" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Summary --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-[14px] font-bold text-slate-900">Resumo do Período</h2>
            <p class="text-[12px] text-slate-500 mt-0.5">
                {{ \Carbon\Carbon::create()->month($mes ?? now()->month)->locale('pt_BR')->isoFormat('MMMM') }}
                de {{ $ano ?? now()->year }}
            </p>
        </div>
        <div class="divide-y divide-slate-50">
            @php
            $summaryItems = [
                ['label' => 'Total de OS abertas',    'value' => $stats['total'] ?? 0,            'type' => 'number'],
                ['label' => 'OS em andamento',        'value' => $stats['em_aberto'] ?? 0,         'type' => 'number'],
                ['label' => 'OS finalizadas no mês',  'value' => $stats['finalizadas_mes'] ?? 0,   'type' => 'number'],
                ['label' => 'Faturamento bruto',      'value' => $stats['faturamento'] ?? 0,       'type' => 'money'],
            ];
            @endphp
            @foreach($summaryItems as $item)
            <div class="flex items-center justify-between px-6 py-3.5">
                <span class="text-[13px] text-slate-600">{{ $item['label'] }}</span>
                <span class="text-[13.5px] font-semibold text-slate-900">
                    @if($item['type'] === 'money')
                        R$ {{ number_format($item['value'], 2, ',', '.') }}
                    @else
                        {{ $item['value'] }}
                    @endif
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Recent OS --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <h2 class="text-[14px] font-bold text-slate-900">OS Recentes</h2>
        <a href="{{ route('app.os.index') }}"
           class="text-[12.5px] font-semibold text-blue-600 hover:text-blue-700">
            Ver todas →
        </a>
    </div>

    @php
    $badgeClass = fn($s) => match($s) {
        'finalizado'         => 'bg-emerald-100 text-emerald-700',
        'execucao'           => 'bg-blue-100 text-blue-700',
        'em_teste'           => 'bg-cyan-100 text-cyan-700',
        'analise'            => 'bg-amber-100 text-amber-700',
        'aguardando_cliente' => 'bg-purple-100 text-purple-700',
        'cancelado'          => 'bg-red-100 text-red-600',
        default              => 'bg-slate-100 text-slate-600',
    };
    $statusLabel = [
        'entrada'            => 'Entrada',
        'analise'            => 'Em análise',
        'execucao'           => 'Em execução',
        'aguardando_cliente' => 'Aguardando',
        'em_teste'           => 'Em teste',
        'finalizado'         => 'Finalizado',
        'cancelado'          => 'Cancelado',
    ];
    @endphp

    @if(empty($recentes) || count($recentes) === 0)
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <p class="text-[14px] font-semibold text-slate-700">Nenhum resultado encontrado</p>
            <p class="mt-1 text-[13px] text-slate-400">Não há OS registradas no período.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Número</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Cliente</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Técnico</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Valor</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($recentes as $os)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('app.os.show', $os) }}"
                               class="font-mono text-[13px] font-bold text-blue-600 hover:text-blue-700">
                                {{ $os->numero }}
                            </a>
                        </td>
                        <td class="px-5 py-3.5 text-[13px] text-slate-700">{{ $os->cliente?->nome ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $badgeClass($os->status) }}">
                                {{ $statusLabel[$os->status] ?? $os->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-[12.5px] text-slate-500">{{ $os->tecnico?->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-right text-[13px] font-medium text-slate-900 tabular-nums">
                            R$ {{ number_format(($os->valor_servico ?? 0) + ($os->valor_pecas ?? 0) - ($os->desconto ?? 0), 2, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right text-[12px] text-slate-400 tabular-nums">
                            {{ $os->created_at?->format('d/m/Y') ?? '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
