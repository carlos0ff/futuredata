@extends('layouts.app')
@section('title', 'Financeiro')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [['label' => 'Financeiro']]
    ])
@endsection

@section('content')

{{-- Page header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Financeiro</h1>
        <p class="mt-0.5 text-[13px] text-slate-500">Resumo financeiro do período selecionado.</p>
    </div>
    <form method="GET" action="{{ route('app.financeiro.index') }}" class="flex items-center gap-2">
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
    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Faturamento do Mês</p>
        <p class="mt-1.5 text-[28px] font-bold leading-none text-emerald-700">
            R$ {{ number_format($stats['faturamento'] ?? 0, 2, ',', '.') }}
        </p>
    </div>
    <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">OS Finalizadas</p>
        <p class="mt-1.5 text-[32px] font-bold leading-none text-blue-700">{{ $stats['os_finalizadas'] ?? 0 }}</p>
    </div>
    <div class="rounded-2xl border border-purple-100 bg-purple-50/50 p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Ticket Médio</p>
        <p class="mt-1.5 text-[28px] font-bold leading-none text-purple-700">
            R$ {{ number_format($stats['ticket_medio'] ?? 0, 2, ',', '.') }}
        </p>
    </div>
    <div class="rounded-2xl border border-amber-100 bg-amber-50/50 p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Em Aberto</p>
        <p class="mt-1.5 text-[28px] font-bold leading-none text-amber-700">
            R$ {{ number_format($stats['em_aberto'] ?? 0, 2, ',', '.') }}
        </p>
    </div>
</div>

{{-- Quick nav --}}
<div class="mb-5 flex gap-2">
    <a href="{{ route('app.financeiro.receitas') }}"
       class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
        <svg class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 0 1 5.814-5.519l2.74-1.22m0 0-5.94-2.28m5.94 2.28-2.28 5.941"/>
        </svg>
        Ver Receitas
    </a>
    <a href="{{ route('app.financeiro.despesas') }}"
       class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
        <svg class="h-3.5 w-3.5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181"/>
        </svg>
        Ver Despesas
    </a>
</div>

{{-- Recent finalized OS --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-6 py-4">
        <h2 class="text-[14px] font-bold text-slate-900">OS Finalizadas Recentemente</h2>
        <p class="text-[12px] text-slate-500 mt-0.5">
            {{ \Carbon\Carbon::create()->month($mes ?? now()->month)->locale('pt_BR')->isoFormat('MMMM') }}
            de {{ $ano ?? now()->year }}
        </p>
    </div>

    @if(empty($recentes) || count($recentes) === 0)
        <div class="flex flex-col items-center justify-center py-14 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                <svg class="h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <p class="text-[14px] font-semibold text-slate-700">Nenhum resultado encontrado</p>
            <p class="mt-1 text-[13px] text-slate-400">Não há OS finalizadas no período selecionado.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Número</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Cliente</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Serviço</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Peças</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Desconto</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php $totalServico = 0; $totalPecas = 0; $totalDesconto = 0; $totalGeral = 0; @endphp
                    @foreach($recentes as $os)
                    @php
                        $servico  = $os->valor_servico ?? 0;
                        $pecas    = $os->valor_pecas ?? 0;
                        $desconto = $os->desconto ?? 0;
                        $total    = $servico + $pecas - $desconto;
                        $totalServico  += $servico;
                        $totalPecas    += $pecas;
                        $totalDesconto += $desconto;
                        $totalGeral    += $total;
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('app.os.show', $os) }}"
                               class="font-mono text-[13px] font-bold text-blue-600 hover:text-blue-700">
                                {{ $os->numero }}
                            </a>
                        </td>
                        <td class="px-5 py-3.5 text-[13px] text-slate-700">{{ $os->cliente?->nome ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-right text-[13px] text-slate-700 tabular-nums">
                            R$ {{ number_format($servico, 2, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right text-[13px] text-slate-700 tabular-nums">
                            R$ {{ number_format($pecas, 2, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right text-[13px] text-red-500 tabular-nums">
                            @if($desconto > 0)- R$ {{ number_format($desconto, 2, ',', '.') }}@else —@endif
                        </td>
                        <td class="px-5 py-3.5 text-right text-[13.5px] font-semibold text-slate-900 tabular-nums">
                            R$ {{ number_format($total, 2, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-200 bg-slate-50">
                        <td colspan="2" class="px-5 py-3 text-[12px] font-semibold uppercase tracking-wider text-slate-500">Total do período</td>
                        <td class="px-5 py-3 text-right text-[13px] font-bold text-slate-900 tabular-nums">R$ {{ number_format($totalServico, 2, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right text-[13px] font-bold text-slate-900 tabular-nums">R$ {{ number_format($totalPecas, 2, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right text-[13px] font-bold text-red-500 tabular-nums">
                            @if($totalDesconto > 0)- R$ {{ number_format($totalDesconto, 2, ',', '.') }}@else —@endif
                        </td>
                        <td class="px-5 py-3 text-right text-[14px] font-bold text-emerald-700 tabular-nums">
                            R$ {{ number_format($totalGeral, 2, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>

@endsection
