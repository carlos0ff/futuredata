@extends('layouts.app')
@section('title', 'Equipamentos')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [['label' => 'Equipamentos']]
    ])
@endsection

@section('content')

{{-- Page header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Equipamentos</h1>
        <p class="mt-0.5 text-[13px] text-slate-400">
            Gerencie os equipamentos cadastrados no sistema.
        </p>
    </div>
    <a href="{{ route('app.equipamentos.create') }}"
       class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2.5 text-[13px] font-semibold text-white shadow-md shadow-blue-600/20 transition-colors hover:bg-blue-700">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
        </svg>
        Novo Equipamento
    </a>
</div>

{{-- Filter bar (unified card) --}}
<form method="GET" action="{{ route('app.equipamentos.index') }}" class="mb-5">
    <div class="flex overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
        {{-- Search --}}
        <div class="relative flex flex-1 items-center border-r border-slate-100">
            <div class="pointer-events-none absolute left-0 flex items-center pl-4">
                <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
            </div>
            <input type="text" name="busca"
                   value="{{ request('busca') }}"
                   placeholder="Buscar por marca, modelo, série…"
                   class="h-11 w-full bg-transparent pl-10 pr-4 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none">
        </div>
        {{-- Tipo select --}}
        <div class="relative flex items-center border-r border-slate-100">
            <select name="tipo"
                    class="h-11 appearance-none bg-transparent pl-4 pr-9 text-[13px] text-slate-600 outline-none">
                <option value="">Todos os tipos</option>
                @foreach(['Notebook', 'Desktop', 'Impressora', 'Celular', 'Tablet', 'Monitor', 'Outro'] as $tipo)
                    <option value="{{ $tipo }}" @selected(request('tipo') === $tipo)>{{ $tipo }}</option>
                @endforeach
            </select>
            <svg class="pointer-events-none absolute right-3 h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        {{-- Actions --}}
        <div class="flex items-center gap-1 px-2">
            <button type="submit"
                    class="h-8 inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 text-[12.5px] font-semibold text-white shadow-sm shadow-blue-600/20 transition-colors hover:bg-blue-700">
                Filtrar
            </button>
            @if(request('busca') || request('tipo'))
                <a href="{{ route('app.equipamentos.index') }}"
                   class="h-8 inline-flex items-center rounded-lg px-3 text-[12.5px] text-slate-500 transition-colors hover:bg-slate-100">
                    Limpar
                </a>
            @endif
        </div>
    </div>
</form>

{{-- Table --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
    @if($equipamentos->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">
                <svg class="h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
                </svg>
            </div>
            <p class="text-[14px] font-semibold text-slate-800">Nenhum equipamento encontrado</p>
            <p class="mt-1 text-[13px] text-slate-400">
                @if(request('busca') || request('tipo'))
                    Tente ajustar os filtros ou <a href="{{ route('app.equipamentos.index') }}" class="font-semibold text-blue-600 hover:text-blue-700">limpar a busca</a>.
                @else
                    Cadastre o primeiro equipamento para começar.
                @endif
            </p>
            @if(!request('busca') && !request('tipo'))
                <a href="{{ route('app.equipamentos.create') }}"
                   class="mt-5 inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2.5 text-[13px] font-semibold text-white shadow-md shadow-blue-600/20 transition-colors hover:bg-blue-700">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                    </svg>
                    Novo Equipamento
                </a>
            @endif
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/60">
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Tipo</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Marca / Modelo</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Nº de Série</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Cliente</th>
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Garantia</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($equipamentos as $eq)
                    <tr class="group transition-colors hover:bg-slate-50/70">
                        <td class="border-l-[3px] border-l-transparent px-5 py-3.5 transition-colors group-hover:border-l-blue-500">
                            <span class="inline-flex rounded-lg bg-slate-100 px-2.5 py-1 text-[11.5px] font-semibold text-slate-600 transition-colors group-hover:bg-blue-50 group-hover:text-blue-700">
                                {{ $eq->tipo }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="text-[13.5px] font-semibold text-slate-900">{{ $eq->marca }}</p>
                            <p class="text-[12px] text-slate-500">{{ $eq->modelo }}</p>
                        </td>
                        <td class="px-5 py-3.5 font-mono text-[12.5px] text-slate-600">
                            {{ $eq->numero_serie ?: '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-[13px] text-slate-700">
                            {{ $eq->cliente?->nome ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5">
                            @if($eq->em_garantia)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Em garantia
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold text-slate-500">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                    Sem garantia
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-0.5 opacity-0 transition-opacity duration-150 group-hover:opacity-100">
                                <a href="{{ route('app.equipamentos.show', $eq) }}"
                                   title="Ver detalhes"
                                   class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-blue-100 hover:text-blue-600">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>
                                <a href="{{ route('app.equipamentos.edit', $eq) }}"
                                   title="Editar"
                                   class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-amber-100 hover:text-amber-600">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('app.equipamentos.destroy', $eq) }}"
                                      onsubmit="return confirm('Excluir o equipamento {{ $eq->marca }} {{ $eq->modelo }}?')"
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

        @if($equipamentos->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $equipamentos->withQueryString()->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
