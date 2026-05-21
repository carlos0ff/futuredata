@extends('layouts.app')
@section('title', $cliente->nome)

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Clientes', 'href' => route('app.clientes.index')],
            ['label' => $cliente->nome],
        ]
    ])
@endsection

@section('content')

{{-- Flash --}}
@if(session('success'))
<div class="mb-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-[13.5px] font-medium text-emerald-700">
    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Page header --}}
<div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-400"></div>
    <div class="flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-start sm:justify-between">
        <div class="flex items-center gap-4">
            <x-app.avatar :initials="$cliente->iniciais" size="xl" />
            <div>
                <h1 class="text-[22px] font-bold tracking-tight text-slate-900">{{ $cliente->nome }}</h1>
                <p class="mt-0.5 text-[13px] text-slate-500">
                    {{ $cliente->email ?? $cliente->telefone ?? 'Sem contacto cadastrado' }}
                </p>
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 text-[12.5px] text-slate-500">
                        <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                        </svg>
                        <span class="font-semibold text-slate-700">{{ $cliente->ordens->count() }}</span> OS
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[12.5px] text-slate-500">
                        <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
                        </svg>
                        <span class="font-semibold text-slate-700">{{ $cliente->equipamentos->count() }}</span> equipamento{{ $cliente->equipamentos->count() !== 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex shrink-0 flex-wrap items-center gap-2">
            <a href="{{ route('app.clientes.index') }}"
               class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-[13px] font-semibold text-slate-600 shadow-sm transition-colors hover:bg-slate-50">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M5 12l7-7M5 12l7 7"/>
                </svg>
                Voltar
            </a>
            <a href="{{ route('app.os.create') }}"
               class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-2 text-[13px] font-semibold text-emerald-700 shadow-sm transition-colors hover:bg-emerald-100">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                </svg>
                Nova OS
            </a>
            <a href="{{ route('app.clientes.edit', $cliente) }}"
               class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-3.5 py-2 text-[13px] font-semibold text-white shadow-sm transition-colors hover:bg-blue-700">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Editar
            </a>
        </div>
    </div>
</div>

<div class="grid gap-5 lg:grid-cols-3">

    {{-- Left sidebar --}}
    <div class="space-y-5 lg:col-span-1">

        {{-- Informações --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[14px] font-bold text-slate-900">Informações</h2>
            </div>
            <dl class="divide-y divide-slate-50">
                @foreach([
                    ['icon' => 'M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.6 19.79 19.79 0 0 1 1.61 5c-.11-1.18.8-2.18 2-2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 10.91A16 16 0 0 0 13.09 15.91l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 16.92z', 'label' => 'Telefone', 'val' => $cliente->telefone],
                    ['icon' => 'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z M22 6l-10 7L2 6', 'label' => 'E-mail', 'val' => $cliente->email],
                    ['icon' => 'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M12 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8z', 'label' => 'CPF/CNPJ', 'val' => $cliente->cpf_cnpj, 'mono' => true],
                    ['icon' => 'M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z M12 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z', 'label' => 'Endereço', 'val' => $cliente->endereco],
                    ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 0 0 1 1h3m10-11l2 2m-2-2v10a1 1 0 0 1-1 1h-3m-6 0a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1m-6 0h6', 'label' => 'Cidade', 'val' => $cliente->cidade ? $cliente->cidade . ($cliente->estado ? ' · ' . $cliente->estado : '') : null],
                    ['icon' => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14', 'label' => 'CEP', 'val' => $cliente->cep],
                ] as $row)
                    @if($row['val'])
                    <div class="flex items-center gap-3 px-5 py-3">
                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-slate-100">
                            <svg class="h-3 w-3 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="{{ $row['icon'] }}"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">{{ $row['label'] }}</p>
                            <p class="mt-0.5 truncate text-[13px] {{ ($row['mono'] ?? false) ? 'font-mono' : '' }} text-slate-800">{{ $row['val'] }}</p>
                        </div>
                    </div>
                    @endif
                @endforeach
            </dl>
        </div>

        {{-- Equipamentos --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-[14px] font-bold text-slate-900">
                    Equipamentos
                    @if($cliente->equipamentos->count() > 0)
                        <span class="ml-1.5 inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-slate-100 px-1.5 text-[11px] font-bold text-slate-600">{{ $cliente->equipamentos->count() }}</span>
                    @endif
                </h2>
                <a href="{{ route('app.equipamentos.create') }}"
                   class="text-[12px] font-semibold text-blue-600 transition-colors hover:text-blue-700">+ Novo</a>
            </div>
            @if($cliente->equipamentos->isEmpty())
                <div class="px-6 py-6 text-center">
                    <p class="text-[13px] text-slate-400">Nenhum equipamento cadastrado.</p>
                </div>
            @else
                <div class="divide-y divide-slate-50">
                    @foreach($cliente->equipamentos->take(5) as $eq)
                    <a href="{{ route('app.equipamentos.show', $eq) }}"
                       class="flex items-center justify-between gap-3 px-5 py-3 transition-colors hover:bg-slate-50">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100">
                                <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[12.5px] font-medium text-slate-800">{{ $eq->marca }} {{ $eq->modelo }}</p>
                                <p class="text-[11px] text-slate-400">{{ $eq->tipo }}{{ $eq->numero_serie ? ' · S/N ' . $eq->numero_serie : '' }}</p>
                            </div>
                        </div>
                        <svg class="h-3.5 w-3.5 shrink-0 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                        </svg>
                    </a>
                    @endforeach
                    @if($cliente->equipamentos->count() > 5)
                        <div class="px-5 py-3 text-center">
                            <a href="{{ route('app.clientes.equipamentos', $cliente) }}"
                               class="text-[12px] font-semibold text-blue-600 hover:text-blue-700">
                                Ver todos ({{ $cliente->equipamentos->count() }}) →
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- OS column --}}
    <div class="lg:col-span-2">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-[14px] font-bold text-slate-900">
                        Ordens de Serviço
                        @if($cliente->ordens->count() > 0)
                            <span class="ml-1.5 inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-blue-100 px-1.5 text-[11px] font-bold text-blue-600">{{ $cliente->ordens->count() }}</span>
                        @endif
                    </h2>
                    <p class="mt-0.5 text-[12px] text-slate-400">Histórico completo de ordens de serviço</p>
                </div>
                <a href="{{ route('app.os.create') }}"
                   class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-[12px] font-semibold text-emerald-700 transition-colors hover:bg-emerald-100">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                    </svg>
                    Nova OS
                </a>
            </div>

            @if($cliente->ordens->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 text-center">
                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">
                        <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                        </svg>
                    </div>
                    <p class="text-[13.5px] font-semibold text-slate-700">Nenhuma OS encontrada</p>
                    <p class="mt-0.5 text-[12.5px] text-slate-400">Este cliente ainda não possui ordens de serviço.</p>
                    <a href="{{ route('app.os.create') }}"
                       class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-[12.5px] font-semibold text-white transition-colors hover:bg-blue-700">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                        </svg>
                        Criar primeira OS
                    </a>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($cliente->ordens->sortByDesc('created_at') as $os)
                    <div class="group flex items-center justify-between gap-4 px-6 py-4 transition-all duration-150 hover:bg-blue-50/40">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-[13px] font-bold text-slate-900">{{ $os->numero }}</span>
                                <x-app.os-status :status="$os->status" />
                            </div>
                            @if($os->equipamento)
                                <p class="mt-0.5 truncate text-[12px] text-slate-500">
                                    {{ $os->equipamento->tipo }} {{ $os->equipamento->marca }}
                                    @if($os->equipamento->modelo)· {{ $os->equipamento->modelo }}@endif
                                </p>
                            @endif
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-[12px] text-slate-400">{{ $os->created_at->format('d/m/Y') }}</p>
                            @if($os->total > 0)
                                <p class="text-[12.5px] font-semibold text-slate-700">R$ {{ number_format($os->total, 2, ',', '.') }}</p>
                            @endif
                            <a href="{{ route('app.os.show', $os) }}"
                               class="mt-0.5 inline-flex items-center gap-0.5 text-[12px] font-semibold text-blue-600 opacity-0 transition-opacity group-hover:opacity-100 hover:text-blue-700">
                                Ver OS
                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
