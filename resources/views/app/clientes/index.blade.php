@extends('layouts.app')
@section('title', 'Clientes')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', ['items' => [['label' => 'Clientes']]])
@endsection

@section('content')

@php
$avatarPalette = [
    'bg-blue-100 text-blue-700',
    'bg-violet-100 text-violet-700',
    'bg-emerald-100 text-emerald-700',
    'bg-amber-100 text-amber-700',
    'bg-rose-100 text-rose-700',
    'bg-cyan-100 text-cyan-700',
    'bg-indigo-100 text-indigo-700',
    'bg-teal-100 text-teal-700',
];
$avatarColor = fn($nome) => $avatarPalette[ord(strtolower($nome[0] ?? 'a')) % count($avatarPalette)];
@endphp


{{-- ── HEADER ──────────────────────────────────────────────────────────────── --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Clientes</h1>
        <p class="mt-0.5 text-[13px] text-slate-400">
            {{ $clientes->total() }} cliente{{ $clientes->total() !== 1 ? 's' : '' }} cadastrado{{ $clientes->total() !== 1 ? 's' : '' }}
            @if(request('busca')) · filtro aplicado @endif
        </p>
    </div>
    <a href="{{ route('app.clientes.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-[13px] font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700 shrink-0">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Novo Cliente
    </a>
</div>

{{-- ── STATS ───────────────────────────────────────────────────────────────── --}}
<div class="mb-5 grid grid-cols-3 gap-3">
    <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-black/[0.06]">
        <p class="text-[10.5px] font-semibold uppercase tracking-widest text-slate-400">Total</p>
        <p class="mt-1 text-[26px] font-black tabular-nums text-slate-900 leading-none">{{ $clientes->total() }}</p>
        <p class="mt-1 text-[11px] text-slate-400">clientes cadastrados</p>
    </div>
    <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-black/[0.06]">
        <p class="text-[10.5px] font-semibold uppercase tracking-widest text-slate-400">Com OS</p>
        <p class="mt-1 text-[26px] font-black tabular-nums text-blue-600 leading-none">{{ $totalComOS }}</p>
        <p class="mt-1 text-[11px] text-slate-400">já usaram o serviço</p>
    </div>
    <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-black/[0.06]">
        <p class="text-[10.5px] font-semibold uppercase tracking-widest text-slate-400">Novos</p>
        <p class="mt-1 text-[26px] font-black tabular-nums text-emerald-600 leading-none">{{ $novosEsseMes }}</p>
        <p class="mt-1 text-[11px] text-slate-400">este mês</p>
    </div>
</div>

{{-- ── BUSCA ───────────────────────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('app.clientes.index') }}" class="mb-4">
    <div class="flex overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
        <div class="relative flex flex-1 items-center">
            <svg class="pointer-events-none absolute left-4 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" name="busca" value="{{ request('busca') }}"
                   placeholder="Buscar por nome, e-mail, telefone, CPF…"
                   class="h-11 w-full bg-transparent pl-11 pr-4 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none">
        </div>
        <div class="flex items-center gap-1.5 px-2">
            <button type="submit"
                    class="h-8 inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 text-[12.5px] font-semibold text-white shadow-sm shadow-blue-600/20 transition hover:bg-blue-700">
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                Buscar
            </button>
            @if(request('busca'))
            <a href="{{ route('app.clientes.index') }}"
               class="h-8 inline-flex items-center gap-1 rounded-lg px-3 text-[12.5px] text-slate-500 transition hover:bg-slate-100">
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                Limpar
            </a>
            @endif
        </div>
    </div>
</form>

{{-- ── LISTA DE CLIENTES ───────────────────────────────────────────────────── --}}
@if($clientes->isEmpty())
<div class="flex flex-col items-center justify-center rounded-2xl bg-white py-16 text-center shadow-sm ring-1 ring-black/[0.06]">
    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 mb-4">
        <svg class="h-7 w-7 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </div>
    @if(request('busca'))
        <p class="text-[15px] font-semibold text-slate-700">Nenhum cliente encontrado</p>
        <p class="mt-1 text-[13px] text-slate-400">Tente outros termos de busca.</p>
        <a href="{{ route('app.clientes.index') }}"
           class="mt-4 inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-4 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-slate-50">
            Limpar filtro
        </a>
    @else
        <p class="text-[15px] font-semibold text-slate-700">Nenhum cliente cadastrado</p>
        <p class="mt-1 text-[13px] text-slate-400">Comece adicionando o primeiro cliente.</p>
        <a href="{{ route('app.clientes.create') }}"
           class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-[13px] font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Novo Cliente
        </a>
    @endif
</div>

@else

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">

    {{-- Cabeçalho da tabela --}}
    <div class="hidden border-b border-slate-100 bg-slate-50/70 px-5 py-2.5 sm:grid sm:grid-cols-[minmax(0,1fr)_130px_100px_80px_44px] sm:gap-4 sm:items-center">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Cliente</p>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Contato</p>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Cidade</p>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 text-center">OS</p>
        <span></span>
    </div>

    {{-- Rows --}}
    <div class="divide-y divide-slate-100">
    @foreach($clientes as $cliente)
    @php
        $color   = $avatarColor($cliente->nome);
        $waPhone = $cliente->telefone ? '55'.preg_replace('/\D/','',$cliente->telefone) : null;
    @endphp
    <div class="group flex items-center gap-4 px-5 py-3.5 transition hover:bg-blue-50/40 sm:grid sm:grid-cols-[minmax(0,1fr)_130px_100px_80px_44px] sm:gap-4">

        {{-- Avatar + nome --}}
        <div class="flex min-w-0 flex-1 items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[13px] font-bold ring-2 ring-white {{ $color }}">
                {{ $cliente->iniciais }}
            </div>
            <div class="min-w-0">
                <a href="{{ route('app.clientes.show', $cliente) }}"
                   class="block truncate text-[13.5px] font-semibold text-slate-900 transition group-hover:text-blue-700">
                    {{ $cliente->nome }}
                </a>
                <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 mt-0.5">
                    @if($cliente->email)
                    <span class="truncate text-[11.5px] text-slate-400">{{ $cliente->email }}</span>
                    @endif
                    @if($cliente->cpf_cnpj)
                    <span class="font-mono text-[10.5px] text-slate-300">{{ $cliente->cpf_cnpj }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Contato --}}
        <div class="hidden sm:block min-w-0">
            @if($cliente->telefone)
            <div class="flex items-center gap-1.5">
                @if($waPhone)
                <a href="https://wa.me/{{ $waPhone }}" target="_blank"
                   class="flex items-center gap-1 text-[12px] font-medium text-slate-600 transition hover:text-emerald-600"
                   onclick="event.stopPropagation()">
                    <svg class="h-3 w-3 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    {{ $cliente->telefone }}
                </a>
                @else
                <span class="text-[12px] text-slate-500">{{ $cliente->telefone }}</span>
                @endif
            </div>
            @else
            <span class="text-[12px] text-slate-300">—</span>
            @endif
        </div>

        {{-- Cidade --}}
        <div class="hidden sm:block min-w-0">
            @if($cliente->cidade)
            <span class="text-[12px] text-slate-500 truncate block">
                {{ $cliente->cidade }}@if($cliente->estado), {{ $cliente->estado }}@endif
            </span>
            @else
            <span class="text-[12px] text-slate-300">—</span>
            @endif
        </div>

        {{-- OS count --}}
        <div class="hidden sm:flex sm:justify-center">
            @if($cliente->ordens_count > 0)
            <span class="inline-flex h-6 min-w-[24px] items-center justify-center rounded-full bg-blue-100 px-2 text-[11px] font-bold text-blue-700 tabular-nums">
                {{ $cliente->ordens_count }}
            </span>
            @else
            <span class="text-[12px] text-slate-300">—</span>
            @endif
        </div>

        {{-- Ação --}}
        <div class="flex shrink-0 items-center justify-end gap-1">
            <a href="{{ route('app.clientes.show', $cliente) }}"
               class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-400 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

    </div>
    @endforeach
    </div>

    {{-- Pagination --}}
    @if($clientes->hasPages())
    <div class="border-t border-slate-100 px-5 py-3.5 flex items-center justify-between">
        <p class="text-[12px] text-slate-400">
            Mostrando {{ $clientes->firstItem() }}–{{ $clientes->lastItem() }} de {{ $clientes->total() }}
        </p>
        <div class="flex items-center gap-1">
            @if($clientes->onFirstPage())
            <span class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-300 cursor-default">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            </span>
            @else
            <a href="{{ $clientes->previousPageUrl() }}"
               class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            @endif

            @foreach($clientes->getUrlRange(max(1,$clientes->currentPage()-2), min($clientes->lastPage(),$clientes->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
               class="flex h-8 w-8 items-center justify-center rounded-lg text-[13px] font-medium transition
                      {{ $page === $clientes->currentPage() ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900' }}">
                {{ $page }}
            </a>
            @endforeach

            @if($clientes->hasMorePages())
            <a href="{{ $clientes->nextPageUrl() }}"
               class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </a>
            @else
            <span class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-300 cursor-default">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </span>
            @endif
        </div>
    </div>
    @endif

</div>
@endif

@endsection
