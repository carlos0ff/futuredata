@extends('layouts.app')
@section('title', 'Receitas')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Financeiro', 'href' => route('app.financeiro.index')],
            ['label' => 'Receitas'],
        ]
    ])
@endsection

@section('content')

{{-- Page header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Receitas</h1>
        <p class="mt-0.5 text-[13px] text-slate-500">OS finalizadas e faturamento por período.</p>
    </div>
    <form method="GET" action="{{ route('app.financeiro.receitas') }}" class="flex items-center gap-2">
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

{{-- Summary card --}}
<div class="mb-5 rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-5 shadow-sm">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-[12px] font-semibold uppercase tracking-wider text-emerald-700">Total do período</p>
            <p class="mt-1 text-[36px] font-bold leading-none text-emerald-800">
                R$ {{ number_format($totalPeriodo ?? 0, 2, ',', '.') }}
            </p>
            <p class="mt-1 text-[13px] text-emerald-600">
                {{ \Carbon\Carbon::create()->month($mes ?? now()->month)->locale('pt_BR')->isoFormat('MMMM') }}
                de {{ $ano ?? now()->year }}
            </p>
        </div>
        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-200/50">
            <svg class="h-8 w-8 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    @if($ordens->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                <svg class="h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </div>
            <p class="text-[14px] font-semibold text-slate-700">Nenhum resultado encontrado</p>
            <p class="mt-1 text-[13px] text-slate-400">Não há receitas registradas no período selecionado.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">OS Nº</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Cliente</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Data Finalização</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Serviço</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Peças</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Desconto</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($ordens as $os)
                    @php
                        $servico  = $os->valor_servico ?? 0;
                        $pecas    = $os->valor_pecas ?? 0;
                        $desconto = $os->desconto ?? 0;
                        $total    = $servico + $pecas - $desconto;
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('app.os.show', $os) }}"
                               class="font-mono text-[13px] font-bold text-blue-600 hover:text-blue-700">
                                {{ $os->numero }}
                            </a>
                        </td>
                        <td class="px-5 py-3.5 text-[13px] text-slate-700">{{ $os->cliente?->nome ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-[12.5px] text-slate-500 tabular-nums">
                            {{ $os->updated_at?->format('d/m/Y') ?? '—' }}
                        </td>
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
            </table>
        </div>

        @if($ordens->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $ordens->withQueryString()->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
