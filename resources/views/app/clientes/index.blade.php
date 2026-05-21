@extends('layouts.app')
@section('title', 'Clientes')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', ['items' => [['label' => 'Clientes']]])
@endsection

@section('content')

{{-- Page header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Clientes</h1>
        <p class="mt-0.5 text-[13px] text-slate-500">
            {{ $clientes->total() }} cliente{{ $clientes->total() !== 1 ? 's' : '' }} cadastrado{{ $clientes->total() !== 1 ? 's' : '' }}
        </p>
    </div>
    <a href="{{ route('app.clientes.create') }}"
       class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-[13px] font-semibold text-white shadow-sm transition-colors hover:bg-blue-700">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
        </svg>
        Novo Cliente
    </a>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="mb-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-[13.5px] font-medium text-emerald-700">
    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Filters --}}
<form method="GET" action="{{ route('app.clientes.index') }}"
      class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
    <div class="relative flex-1">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
        </div>
        <input type="text" name="busca" value="{{ request('busca') }}"
               placeholder="Buscar por nome, e-mail, telefone, CPF…"
               class="h-9 w-full rounded-xl border border-slate-200 bg-slate-50 pl-8 pr-3 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
    </div>
    <button type="submit"
            class="h-9 inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50">
        Filtrar
    </button>
    @if(request('busca'))
        <a href="{{ route('app.clientes.index') }}"
           class="h-9 inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 text-[13px] text-slate-500 transition-colors hover:bg-slate-50">
            Limpar
        </a>
    @endif
</form>

{{-- Table --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    @if($clientes->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">
                <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/>
                </svg>
            </div>
            <p class="text-[14px] font-semibold text-slate-700">Nenhum cliente encontrado</p>
            <p class="mt-1 text-[13px] text-slate-400">
                @if(request('busca'))
                    Tente ajustar o filtro ou <a href="{{ route('app.clientes.index') }}" class="font-semibold text-blue-600 hover:text-blue-700">limpar a busca</a>.
                @else
                    Comece cadastrando o primeiro cliente.
                @endif
            </p>
            @if(!request('busca'))
                <a href="{{ route('app.clientes.create') }}"
                   class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-[12.5px] font-semibold text-white transition-colors hover:bg-blue-700">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                    </svg>
                    Cadastrar cliente
                </a>
            @endif
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Cliente</th>
                        <th class="hidden px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500 sm:table-cell">Contato</th>
                        <th class="hidden px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500 md:table-cell">Cidade</th>
                        <th class="hidden px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500 lg:table-cell">OS</th>
                        <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($clientes as $c)
                    <tr class="group transition-all duration-150 hover:bg-blue-50/40">
                        <td class="border-l-[3px] border-l-transparent px-5 py-3.5 transition-all duration-150 group-hover:border-l-blue-500">
                            <div class="flex items-center gap-3">
                                <x-app.avatar :initials="$c->iniciais" size="sm" />
                                <div>
                                    <p class="text-[13.5px] font-semibold text-slate-900">{{ $c->nome }}</p>
                                    @if($c->cpf_cnpj)
                                        <p class="font-mono text-[11px] text-slate-400">{{ $c->cpf_cnpj }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="hidden px-5 py-3.5 sm:table-cell">
                            @if($c->telefone)
                                <p class="text-[13px] text-slate-700">{{ $c->telefone }}</p>
                            @endif
                            @if($c->email)
                                <p class="text-[11.5px] text-slate-400">{{ $c->email }}</p>
                            @endif
                            @if(!$c->telefone && !$c->email)
                                <span class="text-[12px] text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="hidden px-5 py-3.5 text-[13px] text-slate-600 md:table-cell">
                            @if($c->cidade)
                                {{ $c->cidade }}{{ $c->estado ? ' · ' . $c->estado : '' }}
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="hidden px-5 py-3.5 lg:table-cell">
                            @if($c->ordens_count > 0)
                                <span class="inline-flex items-center justify-center rounded-full bg-blue-100 px-2.5 py-0.5 text-[11.5px] font-bold text-blue-700">
                                    {{ $c->ordens_count }}
                                </span>
                            @else
                                <span class="text-[12px] text-slate-300">0</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-0.5 opacity-0 transition-opacity duration-150 group-hover:opacity-100">
                                <a href="{{ route('app.clientes.show', $c) }}"
                                   title="Ver cliente"
                                   class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-blue-100 hover:text-blue-600">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>
                                <a href="{{ route('app.clientes.edit', $c) }}"
                                   title="Editar"
                                   class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-amber-100 hover:text-amber-600">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('app.clientes.destroy', $c) }}"
                                      onsubmit="return confirm('Excluir {{ addslashes($c->nome) }}? Esta ação não pode ser desfeita.')"
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

        @if($clientes->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $clientes->withQueryString()->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
