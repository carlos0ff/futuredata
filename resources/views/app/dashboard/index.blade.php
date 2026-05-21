@extends('layouts.app')
@section('title', 'Dashboard')

@section('breadcrumbs')
<span class="text-slate-900 font-semibold">Dashboard</span>
@endsection

@section('content')

<div class="mb-6">
    <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Olá, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
    <p class="mt-0.5 text-[13px] text-slate-500">Resumo do sistema · {{ now()->format('d/m/Y') }}</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-8">
    @foreach([
        ['label' => 'Total de OS',   'value' => $stats['total_ordens'],   'color' => 'border-slate-200 bg-white',         'val' => 'text-slate-900'],
        ['label' => 'Em aberto',     'value' => $stats['em_aberto'],      'color' => 'border-blue-100 bg-blue-50/50',     'val' => 'text-blue-700'],
        ['label' => 'Finalizadas',   'value' => $stats['finalizadas'],    'color' => 'border-emerald-100 bg-emerald-50/50','val' => 'text-emerald-700'],
        ['label' => 'Clientes',      'value' => $stats['total_clientes'], 'color' => 'border-purple-100 bg-purple-50/50', 'val' => 'text-purple-700'],
    ] as $stat)
    <div class="rounded-2xl border {{ $stat['color'] }} p-5 shadow-sm">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">{{ $stat['label'] }}</p>
        <p class="mt-1.5 text-[32px] font-bold leading-none {{ $stat['val'] }}">{{ $stat['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- OS Recentes --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <h2 class="text-[14px] font-bold text-slate-900">Ordens Recentes</h2>
        <a href="{{ route('app.os.index') }}" class="text-[12.5px] font-semibold text-blue-600 hover:text-blue-700">Ver todas →</a>
    </div>

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
    $statusLabel = [
        'entrada'            => 'Entrada',
        'analise'            => 'Em análise',
        'execucao'           => 'Em execução',
        'aguardando_cliente' => 'Aguardando',
        'em_teste'           => 'Em teste',
        'finalizado'         => 'Finalizado',
        'cancelado'          => 'Cancelado',
    ];
    @endphp

    @if($recentes->isEmpty())
    <div class="py-12 text-center text-[13px] text-slate-400">Nenhuma ordem registrada ainda.</div>
    @else
    <div class="divide-y divide-slate-50">
        @foreach($recentes as $os)
        <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-slate-50/50 transition-colors">
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <span class="font-mono text-[12.5px] font-bold text-slate-900">{{ $os->numero }}</span>
                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10.5px] font-semibold {{ $badgeClass($os->status) }}">
                        {{ $statusLabel[$os->status] ?? $os->status }}
                    </span>
                </div>
                <p class="mt-0.5 text-[12px] text-slate-500 truncate">
                    {{ $os->cliente?->nome ?? '—' }}
                    @if($os->equipamento) · {{ $os->equipamento->tipo }} {{ $os->equipamento->marca }} @endif
                </p>
            </div>
            <div class="shrink-0 text-right">
                <p class="text-[11.5px] text-slate-400">{{ $os->created_at->format('d/m/Y') }}</p>
                @if($os->tecnico)
                <p class="text-[11px] text-slate-400">{{ $os->tecnico->name }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
