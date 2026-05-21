@extends('layouts.app')
@section('title', 'Ordens de Serviço')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [['label' => 'Ordens de Serviço']]
    ])
@endsection

@section('content')

{{-- Page header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Ordens de Serviço</h1>
        <p class="mt-0.5 text-[13px] text-slate-500">
            {{ $ordens->total() }} OS encontrada{{ $ordens->total() !== 1 ? 's' : '' }}
            @if(request('busca') || request('status'))
                <span class="text-slate-400">com filtros aplicados</span>
            @endif
        </p>
    </div>
    <a href="{{ route('app.os.create') }}"
       class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-[13px] font-semibold text-white shadow-sm transition-colors hover:bg-blue-700">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
        </svg>
        Nova OS
    </a>
</div>

{{-- Stats strip --}}
<div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
    <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100">
            <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Total</p>
            <p class="text-[18px] font-bold leading-tight text-slate-900">{{ $stats['total'] }}</p>
        </div>
    </div>
    <div class="flex items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 shadow-sm">
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100">
            <svg class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wider text-blue-400">Em aberto</p>
            <p class="text-[18px] font-bold leading-tight text-blue-700">{{ $stats['abertas'] }}</p>
        </div>
    </div>
    <div class="flex items-center gap-3 rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 shadow-sm">
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-100">
            <svg class="h-4 w-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wider text-indigo-400">Em execução</p>
            <p class="text-[18px] font-bold leading-tight text-indigo-700">{{ $stats['execucao'] }}</p>
        </div>
    </div>
    <div class="flex items-center gap-3 rounded-xl border {{ $stats['atrasadas'] > 0 ? 'border-red-100 bg-red-50' : 'border-slate-200 bg-white' }} px-4 py-3 shadow-sm">
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $stats['atrasadas'] > 0 ? 'bg-red-100' : 'bg-slate-100' }}">
            <svg class="h-4 w-4 {{ $stats['atrasadas'] > 0 ? 'text-red-500' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wider {{ $stats['atrasadas'] > 0 ? 'text-red-400' : 'text-slate-400' }}">Atrasadas</p>
            <p class="text-[18px] font-bold leading-tight {{ $stats['atrasadas'] > 0 ? 'text-red-600' : 'text-slate-500' }}">{{ $stats['atrasadas'] }}</p>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('app.os.index') }}"
      class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
    <div class="relative flex-1">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
        </div>
        <input type="text" name="busca"
               value="{{ $current['busca'] ?? '' }}"
               placeholder="Número da OS, cliente, equipamento…"
               class="h-9 w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-3 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
    </div>
    <select name="status"
            class="h-9 appearance-none rounded-xl border border-slate-200 bg-slate-50 pl-3 pr-8 text-[13.5px] text-slate-700 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
        <option value="">Todos os status</option>
        @foreach($status ?? [] as $key => $cfg)
            <option value="{{ $key }}" @selected(($current['status'] ?? '') === $key)>
                {{ $cfg['label'] ?? $key }}
            </option>
        @endforeach
    </select>
    <button type="submit"
            class="h-9 inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50">
        Filtrar
    </button>
    @if(request('busca') || request('status'))
        <a href="{{ route('app.os.index') }}"
           class="h-9 inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 text-[13px] text-slate-500 transition-colors hover:bg-slate-50">
            Limpar
        </a>
    @endif
</form>

{{-- Table --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    @if($ordens->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">
                <svg class="h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                </svg>
            </div>
            <p class="text-[14px] font-semibold text-slate-700">Nenhuma OS encontrada</p>
            <p class="mt-1 text-[13px] text-slate-400">
                @if(request('busca') || request('status'))
                    Tente ajustar os filtros ou <a href="{{ route('app.os.index') }}" class="font-semibold text-blue-600 hover:text-blue-700">limpar a busca</a>.
                @else
                    Crie a primeira ordem de serviço.
                @endif
            </p>
            @if(!request('busca') && !request('status'))
                <a href="{{ route('app.os.create') }}"
                   class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-[12.5px] font-semibold text-white transition-colors hover:bg-blue-700">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                    </svg>
                    Criar primeira OS
                </a>
            @endif
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Número</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Cliente</th>
                        <th class="hidden px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500 md:table-cell">Equipamento</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="hidden px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500 lg:table-cell">Técnico</th>
                        <th class="hidden px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500 lg:table-cell">Previsão</th>
                        <th class="hidden px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500 sm:table-cell">Total</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($ordens as $os)
                    @php
                        $isAtrasada = $os->previsao_entrega
                            && !in_array($os->status, ['finalizado', 'cancelado'])
                            && \Carbon\Carbon::parse($os->previsao_entrega)->isPast();
                        $isHoje = $os->previsao_entrega
                            && \Carbon\Carbon::parse($os->previsao_entrega)->isToday();
                    @endphp
                    <tr class="group transition-all duration-150 hover:bg-blue-50/40">
                        <td class="border-l-[3px] border-l-transparent px-5 py-3.5 transition-all duration-150 group-hover:border-l-blue-500">
                            <a href="{{ route('app.os.show', $os) }}"
                               class="font-mono text-[13px] font-bold text-blue-600 transition-colors hover:text-blue-700">
                                {{ $os->numero }}
                            </a>
                            <p class="mt-0.5 text-[11px] text-slate-400 tabular-nums">{{ $os->created_at->format('d/m/Y') }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($os->cliente)
                                <div class="flex items-center gap-2.5">
                                    <x-app.avatar :initials="$os->cliente->iniciais" size="sm" />
                                    <p class="text-[13px] font-medium text-slate-800">{{ $os->cliente->nome }}</p>
                                </div>
                            @else
                                <span class="text-[13px] text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="hidden px-5 py-3.5 md:table-cell">
                            @if($os->equipamento)
                                <p class="text-[13px] text-slate-700">{{ $os->equipamento->marca }} {{ $os->equipamento->modelo }}</p>
                                <p class="text-[11.5px] text-slate-400">{{ $os->equipamento->tipo }}</p>
                            @else
                                <span class="text-[13px] text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <x-app.os-status :status="$os->status" />
                        </td>
                        <td class="hidden px-5 py-3.5 lg:table-cell">
                            @if($os->tecnico)
                                <div class="flex items-center gap-2">
                                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-200 text-[9px] font-bold text-slate-600">
                                        {{ strtoupper(substr($os->tecnico->name, 0, 2)) }}
                                    </div>
                                    <span class="text-[12.5px] text-slate-600">{{ explode(' ', $os->tecnico->name)[0] }}</span>
                                </div>
                            @else
                                <span class="text-[12.5px] text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="hidden px-5 py-3.5 lg:table-cell">
                            @if($os->previsao_entrega)
                                <span class="text-[12.5px] tabular-nums {{ $isAtrasada ? 'font-semibold text-red-600' : ($isHoje ? 'font-semibold text-amber-600' : 'text-slate-500') }}">
                                    @if($isAtrasada)
                                        <span class="mr-1 inline-block h-1.5 w-1.5 rounded-full bg-red-500 align-middle"></span>
                                    @elseif($isHoje)
                                        <span class="mr-1 inline-block h-1.5 w-1.5 rounded-full bg-amber-400 align-middle"></span>
                                    @endif
                                    {{ \Carbon\Carbon::parse($os->previsao_entrega)->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-[12.5px] text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="hidden px-5 py-3.5 text-right sm:table-cell">
                            @php $total = ($os->valor_servico ?? 0) + ($os->valor_pecas ?? 0) - ($os->desconto ?? 0); @endphp
                            @if($total > 0)
                                <span class="text-[13px] font-semibold text-slate-800 tabular-nums">
                                    R$ {{ number_format($total, 2, ',', '.') }}
                                </span>
                            @else
                                <span class="text-[12px] text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-0.5 opacity-0 transition-opacity duration-150 group-hover:opacity-100">
                                <a href="{{ route('app.os.show', $os) }}"
                                   title="Ver OS"
                                   class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-blue-100 hover:text-blue-600">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>
                                <a href="{{ route('app.os.edit', $os) }}"
                                   title="Editar"
                                   class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-amber-100 hover:text-amber-600">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('app.os.destroy', $os) }}"
                                      onsubmit="return confirm('Excluir a OS {{ $os->numero }}?')"
                                      class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Excluir"
                                            class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-red-100 hover:text-red-600">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/>
                                        </svg>
                                    </button>
                                </form>
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
