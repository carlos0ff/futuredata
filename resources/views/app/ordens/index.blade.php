@extends('layouts.app')
@section('title', 'Ordens de Serviço')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [['label' => 'Ordens de Serviço']]
    ])
@endsection

@section('content')

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
@endphp

{{-- Page header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Ordens de Serviço</h1>
        <p class="mt-0.5 text-[13px] text-slate-500">Gerencie todas as OS do sistema.</p>
    </div>
    <a href="{{ route('app.os.create') }}"
       class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-[13px] font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
        </svg>
        Nova OS
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('app.os.index') }}"
      class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
    <input
        type="text"
        name="busca"
        value="{{ $current['busca'] ?? request('busca') }}"
        placeholder="Buscar por número, cliente, equipamento..."
        class="h-9 flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100"
    />
    <select name="status"
            class="h-9 appearance-none rounded-xl border border-slate-200 bg-slate-50 pl-3 pr-8 text-[13.5px] text-slate-700 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
        <option value="">Todos os status</option>
        @foreach($status ?? [] as $key => $cfg)
            <option value="{{ $key }}" @selected(($current['status'] ?? request('status')) === $key)>
                {{ $cfg['label'] ?? $key }}
            </option>
        @endforeach
    </select>
    <button type="submit"
            class="h-9 inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
        <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
        </svg>
        Filtrar
    </button>
    @if(request('busca') || request('status'))
        <a href="{{ route('app.os.index') }}"
           class="h-9 inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 text-[13px] text-slate-500 hover:bg-slate-50 transition-colors">
            Limpar
        </a>
    @endif
</form>

{{-- Table --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    @if($ordens->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                <svg class="h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                </svg>
            </div>
            <p class="text-[14px] font-semibold text-slate-700">Nenhum resultado encontrado</p>
            <p class="mt-1 text-[13px] text-slate-400">Tente ajustar os filtros ou crie uma nova OS.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Número</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Cliente</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Equipamento</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Técnico</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Previsão</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Total</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($ordens as $os)
                    <tr class="group transition-colors hover:bg-slate-50/60">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('app.os.show', $os) }}"
                               class="font-mono text-[13px] font-bold text-blue-600 hover:text-blue-700">
                                {{ $os->numero }}
                            </a>
                        </td>
                        <td class="px-5 py-3.5 text-[13px] text-slate-700">
                            {{ $os->cliente?->nome ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5">
                            @if($os->equipamento)
                                <p class="text-[13px] text-slate-700">{{ $os->equipamento->marca }} {{ $os->equipamento->modelo }}</p>
                                <p class="text-[11.5px] text-slate-400">{{ $os->equipamento->tipo }}</p>
                            @else
                                <span class="text-[13px] text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $badgeClass($os->status) }}">
                                {{ ($status ?? [])[$os->status]['label'] ?? $os->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-[12.5px] text-slate-500">
                            {{ $os->tecnico?->name ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-[12.5px] text-slate-500 tabular-nums">
                            {{ $os->previsao_entrega ? \Carbon\Carbon::parse($os->previsao_entrega)->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-right text-[13px] font-semibold text-slate-900 tabular-nums">
                            R$ {{ number_format(($os->valor_servico ?? 0) + ($os->valor_pecas ?? 0) - ($os->desconto ?? 0), 2, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('app.os.show', $os) }}"
                                   class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                                    Ver
                                </a>
                                <a href="{{ route('app.os.edit', $os) }}"
                                   class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                                    Editar
                                </a>
                            </div>
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
