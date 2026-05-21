@extends('layouts.app')
@section('title', $equipamento->marca . ' ' . $equipamento->modelo)

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Equipamentos', 'href' => route('app.equipamentos.index')],
            ['label' => $equipamento->marca . ' ' . $equipamento->modelo],
        ]
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
$dotClass = fn($s) => match($s) {
    'finalizado'         => 'bg-emerald-500',
    'execucao'           => 'bg-blue-500',
    'em_teste'           => 'bg-cyan-500',
    'analise'            => 'bg-amber-500',
    'aguardando_cliente' => 'bg-purple-500',
    'cancelado'          => 'bg-red-400',
    default              => 'bg-slate-400',
};
$statusLabel = [
    'entrada'            => 'Entrada',
    'analise'            => 'Em análise',
    'execucao'           => 'Em execução',
    'aguardando_cliente' => 'Aguardando',
    'em_teste'           => 'Em teste',
    'finalizado'         => 'Finalizado',
    'cancelado'          => 'Cancelado',
];

$totalOs   = $equipamento->ordens->count();
$osAbertas = $equipamento->ordens->whereNotIn('status', ['finalizado', 'cancelado'])->count();
$ultimaOs  = $equipamento->ordens->sortByDesc('created_at')->first();

$tipoIconPath = match($equipamento->tipo) {
    'Notebook'   => 'M4 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6zm-2 9h20M8 21h8',
    'Desktop'    => 'M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM12 18v3M8 21h8',
    'Impressora' => 'M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z',
    'Celular'    => 'M12 18h.01M8 21h8a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z',
    'Tablet'     => 'M9 21H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-3M12 17h.01',
    'Monitor'    => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z',
    default      => 'M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2v-4M9 21H5a2 2 0 0 1-2-2v-4m0 0h18',
};
@endphp

{{-- Page Header --}}
<div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-400"></div>
    <div class="px-6 py-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

            {{-- Identity --}}
            <div class="flex items-start gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 shadow-sm">
                    <svg class="h-7 w-7 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tipoIconPath }}"/>
                    </svg>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">
                            {{ $equipamento->marca }} {{ $equipamento->modelo }}
                        </h1>
                        <span class="inline-flex rounded-md bg-slate-100 px-2.5 py-0.5 text-[11.5px] font-semibold text-slate-600">
                            {{ $equipamento->tipo }}
                        </span>
                        @if($equipamento->em_garantia)
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
                    </div>
                    @if($equipamento->numero_serie)
                        <p class="mt-0.5 font-mono text-[12.5px] text-slate-400">S/N: {{ $equipamento->numero_serie }}</p>
                    @endif

                    {{-- Stats --}}
                    <div class="mt-3 flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-1.5 text-[12.5px] text-slate-500">
                            <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                            </svg>
                            <span><span class="font-semibold text-slate-700">{{ $totalOs }}</span> OS no histórico</span>
                        </div>
                        @if($osAbertas > 0)
                            <div class="flex items-center gap-1.5 text-[12.5px] text-amber-600">
                                <span class="h-2 w-2 animate-pulse rounded-full bg-amber-400"></span>
                                <span><span class="font-semibold">{{ $osAbertas }}</span> em andamento</span>
                            </div>
                        @endif
                        @if($ultimaOs)
                            <div class="flex items-center gap-1.5 text-[12.5px] text-slate-500">
                                <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                                </svg>
                                Última OS: <span class="ml-1 font-semibold text-slate-700">{{ $ultimaOs->created_at->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex shrink-0 flex-wrap items-center gap-2">
                <a href="{{ route('app.equipamentos.index') }}"
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
                <a href="{{ route('app.equipamentos.edit', $equipamento) }}"
                   class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-3.5 py-2 text-[13px] font-semibold text-white shadow-sm transition-colors hover:bg-blue-700">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Editar
                </a>
                <form method="POST" action="{{ route('app.equipamentos.destroy', $equipamento) }}"
                      onsubmit="return confirm('Excluir este equipamento? Esta ação não pode ser desfeita.')"
                      class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-200 bg-red-50 text-red-500 shadow-sm transition-colors hover:bg-red-100 hover:text-red-600">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
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

<div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

    {{-- Left column --}}
    <div class="space-y-5 lg:col-span-2">

        {{-- Ficha técnica --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[14px] font-bold text-slate-900">Ficha do Equipamento</h2>
                <p class="mt-0.5 text-[12px] text-slate-400">Especificações e dados de entrada</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2 lg:grid-cols-3">

                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100">
                            <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Tipo</p>
                            <p class="mt-0.5 text-[13.5px] font-medium text-slate-800">{{ $equipamento->tipo ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100">
                            <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Marca</p>
                            <p class="mt-0.5 text-[13.5px] font-medium text-slate-800">{{ $equipamento->marca ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100">
                            <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Modelo</p>
                            <p class="mt-0.5 text-[13.5px] font-medium text-slate-800">{{ $equipamento->modelo ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100">
                            <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="M7 8v2M10 8v2M13 8v2M16 8v2M7 14v2M10 14v2M13 14v2M16 14v2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Nº de Série</p>
                            <p class="mt-0.5 font-mono text-[13px] text-slate-800">{{ $equipamento->numero_serie ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100">
                            <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Patrimônio</p>
                            <p class="mt-0.5 text-[13.5px] font-medium text-slate-800">{{ $equipamento->patrimonio ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg {{ $equipamento->em_garantia ? 'bg-emerald-50' : 'bg-slate-100' }}">
                            <svg class="h-3.5 w-3.5 {{ $equipamento->em_garantia ? 'text-emerald-500' : 'text-slate-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Garantia</p>
                            <p class="mt-0.5 text-[13.5px] font-medium {{ $equipamento->em_garantia ? 'text-emerald-600' : 'text-slate-800' }}">
                                {{ $equipamento->em_garantia ? 'Sim — em garantia' : 'Não' }}
                            </p>
                        </div>
                    </div>
                </div>

                @if($equipamento->acessorios || $equipamento->condicao_entrada)
                    <div class="mt-5 grid grid-cols-1 gap-4 border-t border-slate-100 pt-5 sm:grid-cols-2">
                        @if($equipamento->acessorios)
                            <div>
                                <p class="mb-1.5 text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Acessórios</p>
                                <p class="rounded-xl bg-slate-50 px-3.5 py-2.5 text-[13px] leading-relaxed text-slate-700 whitespace-pre-line">{{ $equipamento->acessorios }}</p>
                            </div>
                        @endif
                        @if($equipamento->condicao_entrada)
                            <div>
                                <p class="mb-1.5 text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Condição de Entrada</p>
                                <p class="rounded-xl bg-slate-50 px-3.5 py-2.5 text-[13px] leading-relaxed text-slate-700 whitespace-pre-line">{{ $equipamento->condicao_entrada }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                @if($equipamento->observacoes)
                    <div class="mt-5 border-t border-slate-100 pt-5">
                        <p class="mb-1.5 text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Observações</p>
                        <div class="rounded-xl border border-amber-100 bg-amber-50 px-4 py-3 text-[13px] leading-relaxed text-amber-800 whitespace-pre-line">
                            {{ $equipamento->observacoes }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Ordens de Serviço --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-[14px] font-bold text-slate-900">
                        Ordens de Serviço
                        @if($totalOs > 0)
                            <span class="ml-1.5 inline-flex h-5 min-w-[20px] items-center justify-center rounded-full bg-blue-100 px-1.5 text-[11px] font-bold text-blue-600">{{ $totalOs }}</span>
                        @endif
                    </h2>
                    <p class="mt-0.5 text-[12px] text-slate-400">Histórico de OS vinculadas a este equipamento</p>
                </div>
                <a href="{{ route('app.os.create') }}"
                   class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-[12px] font-semibold text-emerald-700 transition-colors hover:bg-emerald-100">
                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                    </svg>
                    Nova OS
                </a>
            </div>

            @if($equipamento->ordens->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 text-center">
                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">
                        <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
                        </svg>
                    </div>
                    <p class="text-[13.5px] font-semibold text-slate-700">Nenhuma OS encontrada</p>
                    <p class="mt-0.5 text-[12.5px] text-slate-400">Este equipamento ainda não possui ordens de serviço.</p>
                    <a href="{{ route('app.os.create') }}"
                       class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-[12.5px] font-semibold text-white transition-colors hover:bg-blue-700">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                        </svg>
                        Criar primeira OS
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50">
                                <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Número</th>
                                <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                                <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Data</th>
                                <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Técnico</th>
                                <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Total</th>
                                <th class="w-10 px-2 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($equipamento->ordens->sortByDesc('created_at') as $os)
                            <tr class="group transition-all duration-150 hover:bg-blue-50/40">
                                <td class="border-l-[3px] border-l-transparent px-5 py-3.5 transition-all duration-150 group-hover:border-l-blue-500">
                                    <span class="font-mono text-[13px] font-bold text-slate-900">{{ $os->numero }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $badgeClass($os->status) }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $dotClass($os->status) }}"></span>
                                        {{ $statusLabel[$os->status] ?? $os->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 tabular-nums text-[12.5px] text-slate-500">
                                    {{ $os->created_at?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-[13px] text-slate-600">
                                    {{ $os->tecnico?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    @if($os->total > 0)
                                        <span class="text-[13px] font-semibold text-slate-800">R$ {{ number_format($os->total, 2, ',', '.') }}</span>
                                    @else
                                        <span class="text-[12.5px] text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-right">
                                    <a href="{{ route('app.os.show', $os) }}"
                                       title="Ver OS"
                                       class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-[12px] font-semibold text-blue-600 opacity-0 transition-all duration-150 group-hover:opacity-100 hover:bg-blue-50">
                                        Ver
                                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Right column --}}
    <div class="space-y-5">

        {{-- Client Card --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[14px] font-bold text-slate-900">Cliente vinculado</h2>
            </div>
            @if($equipamento->cliente)
                <div class="p-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 text-[13px] font-bold text-white shadow-sm">
                            {{ $equipamento->cliente->iniciais }}
                        </div>
                        <div>
                            <p class="text-[14px] font-semibold text-slate-900">{{ $equipamento->cliente->nome }}</p>
                            @if($equipamento->cliente->email)
                                <p class="text-[12px] text-slate-500">{{ $equipamento->cliente->email }}</p>
                            @endif
                        </div>
                    </div>

                    @if($equipamento->cliente->telefone || $equipamento->cliente->cpf_cnpj)
                        <div class="mt-4 space-y-2.5 border-t border-slate-100 pt-4">
                            @if($equipamento->cliente->telefone)
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-slate-100">
                                        <svg class="h-3 w-3 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.6 19.79 19.79 0 0 1 1.61 5c-.11-1.18.8-2.18 2-2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 10.91A16 16 0 0 0 13.09 15.91l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 16.92z"/>
                                        </svg>
                                    </div>
                                    <span class="text-[13px] text-slate-700">{{ $equipamento->cliente->telefone }}</span>
                                </div>
                            @endif
                            @if($equipamento->cliente->cpf_cnpj)
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-slate-100">
                                        <svg class="h-3 w-3 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                                        </svg>
                                    </div>
                                    <span class="font-mono text-[12.5px] text-slate-700">{{ $equipamento->cliente->cpf_cnpj }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-4 flex flex-col gap-2 border-t border-slate-100 pt-4">
                        <a href="{{ route('app.clientes.show', $equipamento->cliente) }}"
                           class="flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-[12.5px] font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                            <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            Ver perfil do cliente
                        </a>
                        <a href="{{ route('app.clientes.equipamentos', $equipamento->cliente) }}"
                           class="flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-[12.5px] font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                            <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
                            </svg>
                            Equipamentos do cliente
                        </a>
                    </div>
                </div>
            @else
                <div class="p-6 text-center">
                    <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-slate-100">
                        <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/>
                        </svg>
                    </div>
                    <p class="text-[13px] text-slate-500">Nenhum cliente vinculado.</p>
                    <a href="{{ route('app.equipamentos.edit', $equipamento) }}"
                       class="mt-1.5 inline-flex text-[12px] font-semibold text-blue-600 hover:text-blue-700">
                        Vincular cliente →
                    </a>
                </div>
            @endif
        </div>

        {{-- Resumo de OS --}}
        @if($totalOs > 0)
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[14px] font-bold text-slate-900">Resumo de OS</h2>
            </div>
            <div class="space-y-2 p-5">
                @php
                $countByStatus = $equipamento->ordens->groupBy('status');
                $statusGroups = [
                    ['key' => 'finalizado',          'label' => 'Finalizadas',   'dot' => 'bg-emerald-500', 'text' => 'text-emerald-700', 'bg' => 'bg-emerald-50'],
                    ['key' => 'execucao',             'label' => 'Em execução',   'dot' => 'bg-blue-500',    'text' => 'text-blue-700',    'bg' => 'bg-blue-50'],
                    ['key' => 'analise',              'label' => 'Em análise',    'dot' => 'bg-amber-500',   'text' => 'text-amber-700',   'bg' => 'bg-amber-50'],
                    ['key' => 'aguardando_cliente',   'label' => 'Aguardando',    'dot' => 'bg-purple-500',  'text' => 'text-purple-700',  'bg' => 'bg-purple-50'],
                    ['key' => 'em_teste',             'label' => 'Em teste',      'dot' => 'bg-cyan-500',    'text' => 'text-cyan-700',    'bg' => 'bg-cyan-50'],
                    ['key' => 'cancelado',            'label' => 'Canceladas',    'dot' => 'bg-red-400',     'text' => 'text-red-600',     'bg' => 'bg-red-50'],
                ];
                @endphp
                @foreach($statusGroups as $sg)
                    @php $cnt = $countByStatus->get($sg['key'], collect())->count(); @endphp
                    @if($cnt > 0)
                        <div class="flex items-center justify-between rounded-xl {{ $sg['bg'] }} px-3.5 py-2.5">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full {{ $sg['dot'] }}"></span>
                                <span class="text-[12.5px] font-medium {{ $sg['text'] }}">{{ $sg['label'] }}</span>
                            </div>
                            <span class="text-[13px] font-bold {{ $sg['text'] }}">{{ $cnt }}</span>
                        </div>
                    @endif
                @endforeach

                @php $totalValor = $equipamento->ordens->sum('total'); @endphp
                @if($totalValor > 0)
                    <div class="border-t border-slate-100 pt-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Total em serviços</span>
                            <span class="text-[15px] font-bold text-slate-900">R$ {{ number_format($totalValor, 2, ',', '.') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Metadata --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[14px] font-bold text-slate-900">Registro</h2>
            </div>
            <div class="space-y-3 p-5">
                <div class="flex items-center justify-between">
                    <span class="text-[12px] text-slate-500">Cadastrado em</span>
                    <span class="text-[13px] font-medium text-slate-700">{{ $equipamento->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[12px] text-slate-500">Última atualização</span>
                    <span class="text-[13px] font-medium text-slate-700">{{ $equipamento->updated_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center justify-between border-t border-slate-50 pt-3">
                    <span class="text-[12px] text-slate-400">ID interno</span>
                    <span class="font-mono text-[12px] text-slate-400">#{{ $equipamento->id }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
