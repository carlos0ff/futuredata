@extends('layouts.app')
@section('title', 'Ordens de Serviço')

@section('breadcrumbs')
@include('layouts.partials.breadcrumbs', ['items' => [['label' => 'Ordens de Serviço']]])
@endsection

@section('content')

{{-- Header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Ordens de Serviço</h1>
        <p class="mt-0.5 text-[13px] text-slate-500">{{ $ordens->total() }} ordens encontradas</p>
    </div>
    <a href="{{ route('ordens.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-[13.5px] font-semibold text-white shadow-sm transition hover:bg-blue-700">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        Nova OS
    </a>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('ordens.index') }}" class="mb-4 flex flex-col gap-3 sm:flex-row">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" name="busca" value="{{ $current['busca'] ?? '' }}" placeholder="Buscar por nº OS ou cliente…"
               class="h-9 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div class="relative w-full sm:w-52">
        <select name="status" class="h-9 w-full appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
            <option value="">Todos os status</option>
            @foreach($status as $key => $s)
            <option value="{{ $key }}" @selected(($current['status'] ?? '') === $key)>{{ $s['label'] }}</option>
            @endforeach
        </select>
        <svg class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
    </div>
    <button type="submit" class="h-9 rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-medium text-slate-700 transition hover:bg-slate-50">Filtrar</button>
    @if(array_filter($current ?? []))
    <a href="{{ route('ordens.index') }}" class="flex h-9 items-center rounded-xl border border-slate-200 bg-white px-3 text-[13px] text-slate-500 transition hover:bg-slate-50">Limpar</a>
    @endif
</form>

{{-- Tabela --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    @if($ordens->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">
            <svg class="h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2"/></svg>
        </div>
        <p class="text-[15px] font-semibold text-slate-900">Nenhuma ordem encontrada</p>
        <p class="mt-1 text-[13px] text-slate-500">Crie uma nova OS ou ajuste os filtros.</p>
        <a href="{{ route('ordens.create') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-[13px] font-semibold text-white transition hover:bg-blue-700">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Nova OS
        </a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/70">
                    <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Nº OS</th>
                    <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Cliente</th>
                    <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Equipamento</th>
                    <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                    <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Valor</th>
                    <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Data</th>
                    <th class="px-5 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($ordens as $ordem)
                @php
                    $badgeClass = match($ordem->status) {
                        'finalizado'         => 'bg-emerald-50 text-emerald-700',
                        'execucao'           => 'bg-blue-50 text-blue-700',
                        'em_teste'           => 'bg-cyan-50 text-cyan-700',
                        'analise'            => 'bg-amber-50 text-amber-700',
                        'aguardando_cliente' => 'bg-purple-50 text-purple-700',
                        'cancelado'          => 'bg-red-50 text-red-600',
                        default              => 'bg-slate-100 text-slate-600',
                    };
                    $dotClass = match($ordem->status) {
                        'finalizado'         => 'bg-emerald-500',
                        'execucao'           => 'bg-blue-500',
                        'em_teste'           => 'bg-cyan-500 animate-pulse',
                        'analise'            => 'bg-amber-500',
                        'aguardando_cliente' => 'bg-purple-500',
                        'cancelado'          => 'bg-red-500',
                        default              => 'bg-slate-400',
                    };
                @endphp
                <tr class="group transition hover:bg-slate-50/60">
                    <td class="px-5 py-4">
                        <a href="{{ route('ordens.show', $ordem) }}" class="font-mono text-[12.5px] font-semibold text-slate-800 hover:text-blue-600">{{ $ordem->numero }}</a>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 text-[11px] font-bold text-blue-700">
                                {{ $ordem->cliente?->iniciais ?? '??' }}
                            </div>
                            <div>
                                <p class="text-[13px] font-semibold text-slate-900">{{ $ordem->cliente?->nome ?? '—' }}</p>
                                <p class="text-[11.5px] text-slate-400">{{ $ordem->cliente?->telefone ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-[13px] font-medium text-slate-700">{{ $ordem->equipamento?->tipo ?? '—' }}</p>
                        <p class="text-[11.5px] text-slate-400">{{ $ordem->equipamento?->nomeCompleto ?? '' }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11.5px] font-semibold {{ $badgeClass }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $dotClass }}"></span>
                            {{ $status[$ordem->status]['label'] ?? $ordem->status }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-[13px] font-semibold text-slate-800">R$ {{ number_format($ordem->total, 2, ',', '.') }}</span>
                    </td>
                    <td class="px-5 py-4 text-[12.5px] text-slate-500 tabular-nums">
                        {{ $ordem->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('ordens.show', $ordem) }}" class="inline-flex h-8 items-center gap-1 rounded-lg px-2.5 text-[12px] font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Ver</a>
                            <a href="{{ route('ordens.edit', $ordem) }}" class="inline-flex h-8 items-center gap-1 rounded-lg px-2.5 text-[12px] font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Editar</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($ordens->hasPages())
    <div class="flex items-center justify-between border-t border-slate-100 px-5 py-3.5">
        <p class="text-[12px] text-slate-400">Exibindo <span class="font-medium text-slate-600">{{ $ordens->firstItem() }}–{{ $ordens->lastItem() }}</span> de <span class="font-medium text-slate-600">{{ $ordens->total() }}</span></p>
        <div class="flex items-center gap-1">{{ $ordens->links() }}</div>
    </div>
    @endif
    @endif
</div>

@endsection
