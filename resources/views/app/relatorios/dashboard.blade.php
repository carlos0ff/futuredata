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
        <h1 class="text-[24px] font-bold tracking-tight text-slate-900">Relatórios</h1>
        <p class="mt-0.5 text-[13px] text-slate-500">Visão geral do desempenho e indicadores da assistência técnica.</p>
    </div>
    <div class="flex items-center gap-2">
        {{-- Date range filter --}}
        <select class="h-9 appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] text-slate-600 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            <option>Últimos 30 dias</option>
            <option>Últimos 90 dias</option>
            <option>Este mês</option>
            <option>Este ano</option>
        </select>
        <x-ui.button variant="secondary" size="sm" href="#">
            <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Exportar
        </x-ui.button>
    </div>
</div>

{{-- Stats Row --}}
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <x-dashboard.stats-card
        title="Total de OS"
        :value="$stats['total'] ?? '0'"
        change="+12% vs mês anterior"
        change-type="up"
        color="blue"
        icon='<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2"/>'
    />

    <x-dashboard.stats-card
        title="Em Aberto"
        :value="$stats['em_aberto'] ?? '0'"
        change="5 aguardando técnico"
        change-type="neutral"
        color="amber"
        icon='<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'
    />

    <x-dashboard.stats-card
        title="Finalizadas (mês)"
        :value="$stats['finalizadas_mes'] ?? '0'"
        change="+8% vs mês anterior"
        change-type="up"
        color="emerald"
        icon='<path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/>'
    />

    <x-dashboard.stats-card
        title="Faturamento (mês)"
        :value="'R$ ' . number_format($stats['faturamento_mes'] ?? 0, 2, ',', '.')"
        change="+23% vs mês anterior"
        change-type="up"
        color="purple"
        icon='<path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>'
    />
</div>

{{-- Charts row --}}
<div class="mb-6 grid grid-cols-1 gap-5 lg:grid-cols-2">

    {{-- OS por Status --}}
    <x-ui.card title="OS por Status" description="Distribuição das ordens pelo status atual.">
        <div class="space-y-3">
            @php
            $statusData = $stats['por_status'] ?? [
                'aberto'       => ['label' => 'Em Aberto',    'count' => 0, 'color' => 'bg-blue-500', 'light' => 'bg-blue-100'],
                'em_andamento' => ['label' => 'Em Andamento', 'count' => 0, 'color' => 'bg-amber-500', 'light' => 'bg-amber-100'],
                'em_teste'     => ['label' => 'Em Teste',     'count' => 0, 'color' => 'bg-cyan-500', 'light' => 'bg-cyan-100'],
                'concluido'    => ['label' => 'Concluído',    'count' => 0, 'color' => 'bg-emerald-500', 'light' => 'bg-emerald-100'],
                'cancelado'    => ['label' => 'Cancelado',    'count' => 0, 'color' => 'bg-red-500', 'light' => 'bg-red-100'],
            ];
            $totalStatus = array_sum(array_column($statusData, 'count')) ?: 1;
            @endphp

            @foreach($statusData as $key => $item)
            @php $pct = round(($item['count'] / $totalStatus) * 100); @endphp
            <div>
                <div class="mb-1 flex items-center justify-between text-[12.5px]">
                    <span class="font-medium text-slate-700">{{ $item['label'] }}</span>
                    <span class="font-semibold text-slate-900">{{ $item['count'] }} <span class="text-slate-400 font-normal">({{ $pct }}%)</span></span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full {{ $item['light'] }}">
                    <div class="h-2 rounded-full {{ $item['color'] }} transition-all duration-500" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </x-ui.card>

    {{-- Faturamento mensal --}}
    <x-ui.card title="Faturamento Mensal" description="Receita dos últimos 6 meses.">
        <div class="flex h-48 items-end gap-2 pb-4">
            @php
            $faturamentoMeses = $stats['faturamento_meses'] ?? [
                ['mes' => 'Dez', 'valor' => 4200],
                ['mes' => 'Jan', 'valor' => 5800],
                ['mes' => 'Fev', 'valor' => 3900],
                ['mes' => 'Mar', 'valor' => 7200],
                ['mes' => 'Abr', 'valor' => 6100],
                ['mes' => 'Mai', 'valor' => 8400],
            ];
            $maxValor = max(array_column($faturamentoMeses, 'valor')) ?: 1;
            @endphp
            @foreach($faturamentoMeses as $mes)
            @php $height = round(($mes['valor'] / $maxValor) * 100); @endphp
            <div class="group flex flex-1 flex-col items-center gap-1.5">
                <div class="relative w-full overflow-hidden rounded-t-lg bg-blue-100 transition-all duration-300 group-hover:bg-blue-200" style="height: {{ $height }}%">
                    <div class="absolute inset-x-0 bottom-0 rounded-t-lg bg-blue-600 transition-all duration-300" style="height: 100%"></div>
                </div>
                <span class="text-[11px] text-slate-500">{{ $mes['mes'] }}</span>
                <span class="text-[11px] font-semibold text-slate-700">R$ {{ number_format($mes['valor'], 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
    </x-ui.card>
</div>

{{-- Recent OS table --}}
<x-ui.card title="Ordens recentes" description="Últimas 10 OS abertas ou atualizadas.">
    <x-slot:actions>
        <x-ui.button variant="ghost" size="sm" href="{{ route('ordens.index') }}">
            Ver todas
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </x-ui.button>
    </x-slot:actions>

    @if(isset($ordensRecentes) && $ordensRecentes->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400">Nº OS</th>
                        <th class="pb-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400">Cliente</th>
                        <th class="pb-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-400">Status</th>
                        <th class="pb-3 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400">Valor</th>
                        <th class="pb-3 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-400">Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($ordensRecentes as $ordem)
                    @php
                        $sm = ['aberto' => 'primary', 'em_andamento' => 'warning', 'em_teste' => 'info', 'concluido' => 'success', 'cancelado' => 'danger'];
                        $sl = ['aberto' => 'Em Aberto', 'em_andamento' => 'Em Andamento', 'em_teste' => 'Em Teste', 'concluido' => 'Concluído', 'cancelado' => 'Cancelado'];
                    @endphp
                    <tr class="transition-colors hover:bg-slate-50">
                        <td class="py-3">
                            <a href="{{ route('ordens.show', $ordem) }}" class="font-mono text-[13px] font-semibold text-blue-600 hover:underline">{{ $ordem->numero }}</a>
                        </td>
                        <td class="py-3 text-[13px] text-slate-700">{{ $ordem->cliente->nome ?? '—' }}</td>
                        <td class="py-3">
                            <x-ui.badge :variant="$sm[$ordem->status ?? 'aberto'] ?? 'default'">
                                {{ $sl[$ordem->status ?? 'aberto'] ?? '—' }}
                            </x-ui.badge>
                        </td>
                        <td class="py-3 text-right text-[13px] font-medium text-slate-900">
                            R$ {{ number_format(($ordem->valor_servicos ?? 0) + ($ordem->valor_pecas ?? 0) - ($ordem->desconto ?? 0), 2, ',', '.') }}
                        </td>
                        <td class="py-3 text-right text-[12px] tabular-nums text-slate-500">
                            {{ $ordem->created_at?->format('d/m/Y') ?? '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-ui.empty-state
            title="Nenhuma OS recente"
            description="Ainda não há ordens de serviço registradas no período selecionado."
        />
    @endif
</x-ui.card>

@endsection
