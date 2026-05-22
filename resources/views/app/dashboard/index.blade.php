@extends('layouts.app')
@section('title', 'Dashboard')

@section('breadcrumbs')
<span class="font-semibold text-slate-900">Dashboard</span>
@endsection

@section('content')

@php
/* ── helpers de data ─────────────────────────────────────── */
$hour     = now()->hour;
$greeting = $hour < 12 ? 'Bom dia' : ($hour < 18 ? 'Boa tarde' : 'Boa noite');
$meses    = ['jan','fev','mar','abr','mai','jun','jul','ago','set','out','nov','dez'];
$diasSem  = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
$dataStr  = $diasSem[now()->dayOfWeek].', '.now()->day.' de '.$meses[now()->month-1].' · '.now()->year;

/* ── config de status ────────────────────────────────────── */
$statusCfg = [
    'entrada'            => ['label'=>'Entrada',     'bar'=>'bg-slate-400',   'dot'=>'bg-slate-400',   'badge'=>'bg-slate-100 text-slate-700',  'num'=>'text-slate-800'],
    'analise'            => ['label'=>'Em análise',  'bar'=>'bg-amber-400',   'dot'=>'bg-amber-400',   'badge'=>'bg-amber-100 text-amber-700',  'num'=>'text-amber-800'],
    'execucao'           => ['label'=>'Em execução', 'bar'=>'bg-blue-500',    'dot'=>'bg-blue-500',    'badge'=>'bg-blue-100 text-blue-700',    'num'=>'text-blue-800'],
    'aguardando_cliente' => ['label'=>'Aguardando',  'bar'=>'bg-violet-500',  'dot'=>'bg-violet-500',  'badge'=>'bg-violet-100 text-violet-700','num'=>'text-violet-800'],
    'em_teste'           => ['label'=>'Em teste',    'bar'=>'bg-cyan-500',    'dot'=>'bg-cyan-500',    'badge'=>'bg-cyan-100 text-cyan-700',    'num'=>'text-cyan-800'],
    'finalizado'         => ['label'=>'Finalizado',  'bar'=>'bg-emerald-500', 'dot'=>'bg-emerald-500', 'badge'=>'bg-emerald-100 text-emerald-700','num'=>'text-emerald-800'],
    'cancelado'          => ['label'=>'Cancelado',   'bar'=>'bg-red-400',     'dot'=>'bg-red-400',     'badge'=>'bg-red-100 text-red-600',      'num'=>'text-red-700'],
];

/* ── totais ──────────────────────────────────────────────── */
$total = max(1, $stats['total_ordens']);

/* ── mini-bar chart: alturas relativas ──────────────────── */
$maxDia   = max(1, $ultimos7dias->max());
$barH     = fn($n) => $n > 0 ? max(8, (int) round(($n / $maxDia) * 52)) : 3;

/* ── device icons (SVG path por tipo) ───────────────────── */
$deviceIcon = function(string $tipo): string {
    return match(strtolower($tipo)) {
        'notebook', 'laptop' =>
            '<path stroke-linecap="round" stroke-linejoin="round" d="M2 6h20v12H2zM1 18h22M8 22h8M12 18v4"/>',
        'celular', 'smartphone' =>
            '<rect x="7" y="2" width="10" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>',
        'monitor' =>
            '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
        'tablet' =>
            '<rect x="6" y="2" width="12" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>',
        'impressora' =>
            '<polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>',
        'tv' =>
            '<rect x="2" y="7" width="20" height="15" rx="2"/><polyline points="17 2 12 7 7 2"/>',
        'videogame', 'console' =>
            '<line x1="6" y1="12" x2="18" y2="12"/><line x1="9" y1="9" x2="9" y2="15"/><circle cx="16" cy="11" r="1"/><circle cx="18" cy="13" r="1"/>',
        default =>
            '<path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
    };
};

/* ── urgência da OS ─────────────────────────────────────── */
$urgencia = function($os): string {
    if ($os->previsao_entrega && $os->previsao_entrega->isPast()) return 'atrasada';
    if ($os->previsao_entrega && $os->previsao_entrega->isToday()) return 'hoje';
    return 'normal';
};
@endphp

{{-- ╔══════════════════════════════════════════════════════╗
     ║  HERO CARD — dark, métricas + mini chart            ║
     ╚══════════════════════════════════════════════════════╝ --}}
<div class="relative mb-5 overflow-hidden rounded-2xl bg-[#0d0f16] px-6 pb-6 pt-5">

    {{-- glows --}}
    <div class="pointer-events-none absolute -right-20 -top-20 h-80 w-80 rounded-full bg-blue-600/[0.08] blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-0 left-1/4 h-40 w-40 rounded-full bg-indigo-600/[0.06] blur-2xl"></div>

    {{-- greeting row --}}
    <div class="relative mb-5 flex items-start justify-between gap-3">
        <div>
            <h1 class="text-[20px] font-extrabold tracking-tight text-white">
                {{ $greeting }}, {{ explode(' ', auth()->user()->name)[0] }}
            </h1>
            <p class="mt-0.5 text-[12px] text-slate-500">{{ $dataStr }}</p>
        </div>
        <a href="{{ route('app.os.create') }}"
           class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-blue-600 px-3.5 py-2 text-[12.5px] font-semibold text-white shadow-lg shadow-blue-950/50 transition hover:bg-blue-500 active:scale-[0.97]">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
            </svg>
            Nova OS
        </a>
    </div>

    {{-- kpi + chart grid --}}
    <div class="relative grid grid-cols-2 gap-3 lg:grid-cols-[1fr_1fr_1fr_1fr_auto]">

        {{-- Total --}}
        <div class="rounded-xl border border-white/[0.07] bg-white/[0.04] p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Total de OS</p>
            <p class="mt-2 text-[34px] font-black leading-none tracking-tight text-white tabular-nums">{{ $stats['total_ordens'] }}</p>
            <p class="mt-2 text-[11px] text-slate-500">
                @if($stats['tendencia'] !== 0)
                    <span class="font-semibold {{ $stats['tendencia'] > 0 ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $stats['tendencia'] > 0 ? '+' : '' }}{{ $stats['tendencia'] }}%
                    </span>
                    vs semana anterior
                @else
                    {{ $stats['semana_atual'] }} esta semana
                @endif
            </p>
        </div>

        {{-- Em aberto --}}
        <div class="rounded-xl border border-blue-500/[0.2] bg-blue-600/[0.1] p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-blue-400/70">Em aberto</p>
            <p class="mt-2 text-[34px] font-black leading-none tracking-tight text-blue-400 tabular-nums">{{ $stats['em_aberto'] }}</p>
            <p class="mt-2 text-[11px] text-slate-500">
                {{ round(($stats['em_aberto'] / $total) * 100) }}% do total
            </p>
        </div>

        {{-- Finalizadas --}}
        <div class="rounded-xl border border-emerald-500/[0.2] bg-emerald-600/[0.08] p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-emerald-400/70">Finalizadas</p>
            <p class="mt-2 text-[34px] font-black leading-none tracking-tight text-emerald-400 tabular-nums">{{ $stats['finalizadas'] }}</p>
            <p class="mt-2 text-[11px] text-slate-500">
                {{ $stats['taxa_conclusao'] }}% de conclusão
                @if($stats['finalizadas_hoje'] > 0)
                    · <span class="text-emerald-400 font-semibold">+{{ $stats['finalizadas_hoje'] }} hoje</span>
                @endif
            </p>
        </div>

        {{-- Atrasadas --}}
        <div class="rounded-xl border {{ $stats['atrasadas'] > 0 ? 'border-red-500/[0.2] bg-red-600/[0.1]' : 'border-white/[0.06] bg-white/[0.03]' }} p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest {{ $stats['atrasadas'] > 0 ? 'text-red-400/70' : 'text-slate-500' }}">Atrasadas</p>
            <p class="mt-2 text-[34px] font-black leading-none tracking-tight tabular-nums {{ $stats['atrasadas'] > 0 ? 'text-red-400' : 'text-slate-500' }}">{{ $stats['atrasadas'] }}</p>
            <p class="mt-2 text-[11px]">
                @if($stats['atrasadas'] > 0)
                    <a href="{{ route('app.os.index') }}" class="font-semibold text-red-400 transition hover:text-red-300">Resolver agora →</a>
                @else
                    <span class="font-semibold text-emerald-400">Tudo em dia</span>
                @endif
            </p>
        </div>

        {{-- Mini bar chart: OS / 7 dias --}}
        <div class="col-span-2 rounded-xl border border-white/[0.07] bg-white/[0.04] px-4 py-4 lg:col-span-1 lg:min-w-[140px]">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">OS · 7 dias</p>
            <div class="flex items-end justify-between gap-1" style="height:52px">
                @foreach($ultimos7dias as $dia => $count)
                <div class="flex flex-1 flex-col items-center gap-1.5">
                    <div class="{{ $count > 0 ? 'bg-blue-500/70' : 'bg-white/[0.07]' }} w-full rounded-sm transition-all"
                         style="height:{{ $barH($count) }}px"></div>
                    <span class="text-[9px] font-medium text-slate-600 tabular-nums">{{ substr($dia, 0, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- ╔══════════════════════════════════════════════════════╗
     ║  ALERTAS URGENTES  (só aparece se houver)           ║
     ╚══════════════════════════════════════════════════════╝ --}}
@if($urgentes->isNotEmpty())
<div class="mb-5 overflow-hidden rounded-2xl border border-red-200 bg-red-50">
    <div class="flex items-center gap-3 border-b border-red-200/70 px-5 py-3">
        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-red-500">
            <svg class="h-3.5 w-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            </svg>
        </div>
        <p class="text-[13px] font-bold text-red-800">
            {{ $urgentes->count() }} OS {{ $urgentes->count() === 1 ? 'com prazo vencido ou vence hoje' : 'com prazo vencido ou que vencem hoje' }}
        </p>
        <a href="{{ route('app.os.index') }}" class="ml-auto text-[12px] font-semibold text-red-600 transition hover:text-red-700">Ver todas →</a>
    </div>
    <div class="divide-y divide-red-200/50">
        @foreach($urgentes as $os)
        @php $urg = $urgencia($os); @endphp
        <a href="{{ route('app.os.show', $os) }}"
           class="group flex items-center gap-3 px-5 py-2.5 transition hover:bg-red-100/60">
            <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $urg === 'atrasada' ? 'bg-red-500' : 'bg-amber-500' }}"></span>
            <span class="font-mono text-[12px] font-bold {{ $urg === 'atrasada' ? 'text-red-600' : 'text-amber-700' }}">{{ $os->numero }}</span>
            <span class="min-w-0 flex-1 truncate text-[12.5px] font-medium text-red-800">{{ $os->cliente?->nome ?? '—' }}</span>
            @if($os->equipamento)
            <span class="hidden shrink-0 text-[11.5px] text-red-500 sm:block">{{ $os->equipamento->tipo }} · {{ $os->equipamento->marca }}</span>
            @endif
            <span class="shrink-0 text-[11px] font-semibold {{ $urg === 'atrasada' ? 'text-red-600' : 'text-amber-600' }}">
                {{ $urg === 'atrasada' ? 'Atrasada' : 'Vence hoje' }}
            </span>
            <svg class="h-3 w-3 shrink-0 text-red-300 transition group-hover:translate-x-0.5 group-hover:text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
            </svg>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- ╔══════════════════════════════════════════════════════╗
     ║  PIPELINE DE STATUS                                 ║
     ╚══════════════════════════════════════════════════════╝ --}}
<div class="mb-5 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <div>
            <h2 class="text-[14px] font-bold text-slate-900">Pipeline de Status</h2>
            <p class="mt-0.5 text-[12px] text-slate-400">Distribuição das {{ $stats['total_ordens'] }} OS por estágio</p>
        </div>
        <a href="{{ route('app.os.index') }}"
           class="flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-[12px] font-semibold text-slate-600 transition hover:bg-slate-50">
            Ver todas
            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if($stats['total_ordens'] > 0)
    {{-- stacked bar --}}
    <div class="px-6 pt-5">
        <div class="flex h-2 overflow-hidden rounded-full">
            @foreach($statusCfg as $key => $cfg)
                @php $n = $contagemStatus[$key] ?? 0; $w = round(($n / $total) * 100); @endphp
                @if($n > 0)
                <div class="{{ $cfg['bar'] }} h-full transition-all duration-700 first:rounded-l-full last:rounded-r-full"
                     style="width:{{ $w }}%" title="{{ $cfg['label'] }}: {{ $n }}"></div>
                @endif
            @endforeach
        </div>
        <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1.5 pb-4 border-b border-slate-100">
            @foreach($statusCfg as $key => $cfg)
                @php $n = $contagemStatus[$key] ?? 0; @endphp
                @if($n > 0)
                <span class="flex items-center gap-1.5 text-[11.5px] text-slate-500">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $cfg['dot'] }}"></span>
                    {{ $cfg['label'] }}
                    <span class="font-bold text-slate-800 tabular-nums">{{ $n }}</span>
                    <span class="text-slate-400">{{ round(($n / $total) * 100) }}%</span>
                </span>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- status cells --}}
    <div class="grid grid-cols-4 divide-x divide-y divide-slate-100 sm:grid-cols-7">
        @foreach($statusCfg as $key => $cfg)
        @php $n = $contagemStatus[$key] ?? 0; $pct = $total > 0 ? min(100, round(($n / $total) * 100)) : 0; @endphp
        <a href="{{ route('app.os.index') }}?status={{ $key }}"
           class="group flex flex-col gap-2 px-4 py-4 transition-colors hover:bg-slate-50/70">
            <div class="flex min-w-0 items-center gap-1.5">
                <span class="h-[5px] w-[5px] shrink-0 rounded-full {{ $cfg['dot'] }}"></span>
                <span class="truncate text-[10.5px] font-semibold text-slate-500">{{ $cfg['label'] }}</span>
            </div>
            <p class="text-[28px] font-black leading-none tracking-tight tabular-nums {{ $n > 0 ? $cfg['num'] : 'text-slate-300' }}">{{ $n }}</p>
            <div class="h-1 w-full overflow-hidden rounded-full bg-slate-100">
                <div class="{{ $cfg['bar'] }} h-full rounded-full transition-all duration-700" style="width:{{ $pct }}%"></div>
            </div>
        </a>
        @endforeach
    </div>
</div>

{{-- ╔══════════════════════════════════════════════════════╗
     ║  EQUIPAMENTOS EM SERVIÇO                            ║
     ╚══════════════════════════════════════════════════════╝ --}}
<div class="mb-5 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <div>
            <h2 class="text-[14px] font-bold text-slate-900">Equipamentos em Serviço</h2>
            <p class="mt-0.5 text-[12px] text-slate-400">Equipamentos com OS abertas no momento</p>
        </div>
        <a href="{{ route('app.os.index') }}"
           class="flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-[12px] font-semibold text-slate-600 transition hover:bg-slate-50">
            Ver OS
            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if($equipamentosEmServico->isEmpty())
    <div class="flex flex-col items-center justify-center py-14">
        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">
            <svg class="h-6 w-6 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="2" y="7" width="20" height="14" rx="2"/>
                <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
            </svg>
        </div>
        <p class="text-[13.5px] font-semibold text-slate-500">Nenhum equipamento em serviço</p>
        <p class="mt-1 text-[12.5px] text-slate-400">Todas as OS estão finalizadas ou sem equipamento vinculado.</p>
    </div>
    @else

    {{-- Desktop table --}}
    <div class="hidden overflow-x-auto sm:block">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/60">
                    <th class="px-5 py-2.5 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Equipamento</th>
                    <th class="px-5 py-2.5 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Cliente</th>
                    <th class="px-5 py-2.5 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">OS</th>
                    <th class="px-5 py-2.5 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Status</th>
                    <th class="px-5 py-2.5 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Técnico</th>
                    <th class="px-5 py-2.5 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Prazo</th>
                    <th class="px-5 py-2.5 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Entrada</th>
                    <th class="px-2 py-2.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($equipamentosEmServico as $os)
                @php
                    $eq    = $os->equipamento;
                    $urg   = $urgencia($os);
                    $dias  = $os->created_at->diffInDays(now());
                    $diasStr = match(true) { $dias === 0 => 'hoje', $dias === 1 => '1 dia', default => $dias.' dias' };
                @endphp
                <tr class="group relative transition-colors hover:bg-slate-50/70">
                    {{-- urgência: borda esquerda --}}
                    <td class="relative px-5 py-3.5">
                        <div class="absolute left-0 top-1/2 h-[60%] w-0.5 -translate-y-1/2 rounded-r
                            {{ $urg === 'atrasada' ? 'bg-red-500' : ($urg === 'hoje' ? 'bg-amber-400' : 'bg-transparent group-hover:bg-blue-400') }}
                            transition-all"></div>
                        <div class="flex items-center gap-3">
                            {{-- device icon --}}
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl
                                {{ $urg === 'atrasada' ? 'bg-red-100' : 'bg-slate-100' }}">
                                <svg class="h-4 w-4 {{ $urg === 'atrasada' ? 'text-red-500' : 'text-slate-500' }}"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    {!! $deviceIcon($eq->tipo ?? 'Outro') !!}
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[13px] font-semibold text-slate-900">
                                    {{ $eq->marca ?? '' }} {{ $eq->modelo ?? $eq->tipo ?? '—' }}
                                </p>
                                <p class="text-[11px] text-slate-400">{{ $eq->tipo ?? '—' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <p class="max-w-[160px] truncate text-[13px] font-medium text-slate-700">{{ $os->cliente?->nome ?? '—' }}</p>
                    </td>
                    <td class="px-5 py-3.5">
                        <a href="{{ route('app.os.show', $os) }}"
                           class="font-mono text-[12.5px] font-bold text-blue-600 transition hover:text-blue-700">
                            {{ $os->numero }}
                        </a>
                    </td>
                    <td class="px-5 py-3.5">
                        @php $sc = $statusCfg[$os->status] ?? null; @endphp
                        @if($sc)
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $sc['badge'] }}">
                            <span class="h-1 w-1 rounded-full {{ $sc['dot'] }}
                                {{ in_array($os->status, ['execucao','analise']) ? 'animate-pulse' : '' }}"></span>
                            {{ $sc['label'] }}
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        @if($os->tecnico)
                        <div class="flex items-center gap-2">
                            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 text-[9px] font-bold text-blue-700">
                                {{ strtoupper(substr($os->tecnico->name, 0, 2)) }}
                            </div>
                            <span class="text-[12.5px] text-slate-600">{{ explode(' ', $os->tecnico->name)[0] }}</span>
                        </div>
                        @else
                        <span class="text-[12px] text-slate-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        @if($os->previsao_entrega)
                        <span class="text-[12.5px] tabular-nums font-medium
                            {{ $urg === 'atrasada' ? 'text-red-600' : ($urg === 'hoje' ? 'text-amber-600' : 'text-slate-500') }}">
                            {{ $os->previsao_entrega->format('d/m/Y') }}
                        </span>
                        @else
                        <span class="text-[12px] text-slate-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="text-[11.5px] tabular-nums text-slate-400">{{ $diasStr }}</span>
                    </td>
                    <td class="pr-4 py-3.5 text-right">
                        <a href="{{ route('app.os.show', $os) }}"
                           class="inline-flex h-7 items-center gap-1 rounded-lg border border-slate-200 px-2.5 text-[11.5px] font-semibold text-slate-500 opacity-0 transition-all group-hover:opacity-100 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600">
                            Abrir
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="divide-y divide-slate-100 sm:hidden">
        @foreach($equipamentosEmServico as $os)
        @php $eq = $os->equipamento; $urg = $urgencia($os); $dias = $os->created_at->diffInDays(now()); @endphp
        <a href="{{ route('app.os.show', $os) }}"
           class="group flex items-center gap-3 px-4 py-3.5 transition-colors hover:bg-slate-50">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $urg === 'atrasada' ? 'bg-red-100' : 'bg-slate-100' }}">
                <svg class="h-4 w-4 {{ $urg === 'atrasada' ? 'text-red-500' : 'text-slate-500' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    {!! $deviceIcon($eq->tipo ?? 'Outro') !!}
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-[13px] font-semibold text-slate-900">{{ $eq->marca ?? '' }} {{ $eq->modelo ?? $eq->tipo ?? '—' }}</p>
                <p class="truncate text-[11.5px] text-slate-400">{{ $os->cliente?->nome ?? '—' }} · {{ $os->numero }}</p>
            </div>
            @php $sc = $statusCfg[$os->status] ?? null; @endphp
            @if($sc)
            <span class="shrink-0 rounded-full px-2 py-0.5 text-[10.5px] font-semibold {{ $sc['badge'] }}">{{ $sc['label'] }}</span>
            @endif
            <svg class="h-3 w-3 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
        </a>
        @endforeach
    </div>

    @endif
</div>

{{-- ╔══════════════════════════════════════════════════════╗
     ║  TÉCNICOS  ·  OS RECENTES                          ║
     ╚══════════════════════════════════════════════════════╝ --}}
<div class="grid gap-5 lg:grid-cols-5">

    {{-- Por técnico (2 cols) --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06] lg:col-span-2">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-[14px] font-bold text-slate-900">Por Técnico</h2>
            <p class="mt-0.5 text-[12px] text-slate-400">Carga de OS abertas por responsável</p>
        </div>
        @if($porTecnico->isEmpty())
        <div class="flex flex-col items-center justify-center py-12">
            <svg class="mb-2 h-8 w-8 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            </svg>
            <p class="text-[13px] font-medium text-slate-400">Nenhuma OS atribuída</p>
        </div>
        @else
        <div class="divide-y divide-slate-50 px-6">
            @php $maxTec = $porTecnico->max('total') ?: 1; @endphp
            @foreach($porTecnico as $item)
            <div class="flex items-center gap-3 py-3.5">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 text-[10.5px] font-bold text-blue-700">
                    {{ strtoupper(substr($item->tecnico->name ?? '?', 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="mb-1.5 flex items-center justify-between">
                        <span class="truncate text-[12.5px] font-semibold text-slate-800">
                            {{ explode(' ', $item->tecnico->name ?? 'Sem nome')[0] }}
                        </span>
                        <span class="ml-2 shrink-0 font-mono text-[13px] font-black text-slate-900 tabular-nums">{{ $item->total }}</span>
                    </div>
                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-blue-500 transition-all duration-500"
                             style="width:{{ round(($item->total / $maxTec) * 100) }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- OS Recentes (3 cols) --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06] lg:col-span-3">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div>
                <h2 class="text-[14px] font-bold text-slate-900">OS Recentes</h2>
                <p class="mt-0.5 text-[12px] text-slate-400">Últimas ordens registradas</p>
            </div>
            <a href="{{ route('app.os.create') }}"
               class="flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-[12px] font-semibold text-slate-600 transition hover:bg-slate-50">+ Nova</a>
        </div>
        @if($recentes->isEmpty())
        <div class="flex flex-col items-center justify-center py-12">
            <svg class="mb-2 h-8 w-8 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/>
            </svg>
            <p class="text-[13px] font-medium text-slate-400">Nenhuma OS registrada ainda</p>
        </div>
        @else
        <div class="divide-y divide-slate-50">
            @foreach($recentes as $os)
            @php $sc = $statusCfg[$os->status] ?? null; $urg = $urgencia($os); @endphp
            <a href="{{ route('app.os.show', $os) }}"
               class="group flex items-center gap-3 px-5 py-3 transition-colors hover:bg-slate-50/80">
                {{-- urgency dot --}}
                <span class="h-1.5 w-1.5 shrink-0 rounded-full
                    {{ $urg === 'atrasada' ? 'bg-red-500' : ($urg === 'hoje' ? 'bg-amber-400' : 'bg-slate-200') }}"></span>
                <span class="w-[70px] shrink-0 font-mono text-[12px] font-bold text-blue-600 tabular-nums">{{ $os->numero }}</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[13px] font-medium text-slate-800">{{ $os->cliente?->nome ?? '—' }}</p>
                    @if($os->equipamento)
                    <p class="truncate text-[11px] text-slate-400">{{ $os->equipamento->tipo }}@if($os->equipamento->marca) · {{ $os->equipamento->marca }}@endif</p>
                    @endif
                </div>
                @if($sc)
                <span class="shrink-0 rounded-full px-2.5 py-1 text-[10.5px] font-semibold {{ $sc['badge'] }}">{{ $sc['label'] }}</span>
                @endif
                <svg class="h-3 w-3 shrink-0 text-slate-300 transition-transform group-hover:translate-x-0.5 group-hover:text-slate-400"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                </svg>
            </a>
            @endforeach
        </div>
        @endif
    </div>

</div>

@endsection
