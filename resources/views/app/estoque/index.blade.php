@extends('layouts.app')
@section('title', 'Estoque')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [['label' => 'Estoque']]
    ])
@endsection

@section('content')

{{-- Page header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Estoque</h1>
        <p class="mt-0.5 text-[13px] text-slate-500">Inventário de equipamentos cadastrados no sistema.</p>
    </div>
    <a href="{{ route('app.equipamentos.create') }}"
       class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
        </svg>
        Novo Equipamento
    </a>
</div>

{{-- Development notice --}}
<div class="mb-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3.5">
    <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/>
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75h.007v.008H12v-.008z"/>
    </svg>
    <p class="text-[12.5px] text-amber-700">
        <strong>Funcionalidade em desenvolvimento.</strong>
        Exibindo inventário de equipamentos cadastrados no sistema.
    </p>
</div>

{{-- Stats --}}
<div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Total de Equipamentos</p>
        <p class="mt-1.5 text-[32px] font-bold leading-none text-slate-900">{{ $stats['total'] ?? 0 }}</p>
    </div>
    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/50 p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Em Garantia</p>
        <p class="mt-1.5 text-[32px] font-bold leading-none text-emerald-700">{{ $stats['em_garantia'] ?? 0 }}</p>
    </div>
    <div class="rounded-2xl border border-amber-100 bg-amber-50/50 p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aguardando Retirada</p>
        <p class="mt-1.5 text-[32px] font-bold leading-none text-amber-700">{{ $stats['aguardando_retirada'] ?? 0 }}</p>
    </div>
    <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tipos Distintos</p>
        <p class="mt-1.5 text-[32px] font-bold leading-none text-blue-700">{{ count($porTipo ?? []) }}</p>
    </div>
</div>

{{-- Por Tipo --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-6 py-4">
        <h2 class="text-[14px] font-bold text-slate-900">Equipamentos por Tipo</h2>
        <p class="text-[12px] text-slate-500 mt-0.5">Distribuição do inventário por categoria.</p>
    </div>

    @php
    $tipoIcons = [
        'Notebook'   => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
        'Desktop'    => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
        'Impressora' => '<polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>',
        'Celular'    => '<rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>',
        'Tablet'     => '<rect x="4" y="2" width="16" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>',
        'Monitor'    => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
        'Outro'      => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01"/>',
    ];
    $tipoColors = [
        'Notebook' => 'bg-blue-50 text-blue-600',
        'Desktop'  => 'bg-indigo-50 text-indigo-600',
        'Impressora' => 'bg-orange-50 text-orange-600',
        'Celular'  => 'bg-green-50 text-green-600',
        'Tablet'   => 'bg-cyan-50 text-cyan-600',
        'Monitor'  => 'bg-purple-50 text-purple-600',
        'Outro'    => 'bg-slate-100 text-slate-600',
    ];
    @endphp

    @if(empty($porTipo) || count($porTipo) === 0)
        <div class="flex flex-col items-center justify-center py-14 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                <svg class="h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
            </div>
            <p class="text-[14px] font-semibold text-slate-700">Nenhum resultado encontrado</p>
            <p class="mt-1 text-[13px] text-slate-400">Não há equipamentos cadastrados ainda.</p>
        </div>
    @else
        <div class="grid grid-cols-2 gap-4 p-5 sm:grid-cols-3 lg:grid-cols-4">
            @foreach($porTipo as $tipo => $count)
            @php
                $icon = $tipoIcons[$tipo] ?? $tipoIcons['Outro'];
                $color = $tipoColors[$tipo] ?? $tipoColors['Outro'];
            @endphp
            <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/50 p-4 hover:bg-slate-50 transition-colors">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $color }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        {!! $icon !!}
                    </svg>
                </div>
                <div>
                    <p class="text-[12px] font-semibold text-slate-600">{{ $tipo }}</p>
                    <p class="text-[20px] font-bold leading-tight text-slate-900">{{ $count }}</p>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
