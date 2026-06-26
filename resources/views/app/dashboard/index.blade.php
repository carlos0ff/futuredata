@extends('layouts.app')
@section('title', 'Dashboard')

@section('breadcrumbs')
<span class="font-semibold text-slate-900">Dashboard</span>
@endsection

@section('content')

@php
$hour     = now()->hour;
$greeting = $hour < 12 ? 'Bom dia' : ($hour < 18 ? 'Boa tarde' : 'Boa noite');
$meses    = ['jan','fev','mar','abr','mai','jun','jul','ago','set','out','nov','dez'];
$diasSem  = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
$dataStr  = $diasSem[now()->dayOfWeek].', '.now()->day.' de '.$meses[now()->month-1];

$statusCfg = [
    'entrada'            => ['label'=>'Entrada',       'bar'=>'bg-slate-400',   'dot'=>'bg-slate-400',   'badge'=>'bg-slate-100 text-slate-700'],
    'analise'            => ['label'=>'Em análise',    'bar'=>'bg-amber-400',   'dot'=>'bg-amber-400',   'badge'=>'bg-amber-100 text-amber-700'],
    'execucao'           => ['label'=>'Em execução',   'bar'=>'bg-blue-500',    'dot'=>'bg-blue-500',    'badge'=>'bg-blue-100 text-blue-700'],
    'aguardando_cliente' => ['label'=>'Ag. cliente',   'bar'=>'bg-violet-500',  'dot'=>'bg-violet-500',  'badge'=>'bg-violet-100 text-violet-700'],
    'em_teste'           => ['label'=>'Em teste',      'bar'=>'bg-cyan-500',    'dot'=>'bg-cyan-500',    'badge'=>'bg-cyan-100 text-cyan-700'],
    'finalizado'         => ['label'=>'Finalizado',    'bar'=>'bg-emerald-500', 'dot'=>'bg-emerald-500', 'badge'=>'bg-emerald-100 text-emerald-700'],
    'cancelado'          => ['label'=>'Cancelado',     'bar'=>'bg-red-400',     'dot'=>'bg-red-400',     'badge'=>'bg-red-100 text-red-600'],
];

$total  = max(1, $stats['total_ordens']);
$urgFn  = fn($os) => ($os->previsao_entrega && $os->previsao_entrega->isPast())
    ? 'atrasada'
    : (($os->previsao_entrega && $os->previsao_entrega->isToday()) ? 'hoje' : 'normal');
$maxDia = max(1, $ultimos7dias->max());
@endphp

{{-- ── HERO BANNER ──────────────────────────────────────────────────────── --}}
@php
$pctAberto    = $stats['total_ordens'] > 0 ? round(($stats['em_aberto'] / $stats['total_ordens']) * 100) : 0;
$tendSinal    = $stats['tendencia'] >= 0 ? '↑' : '↓';
$tendCor      = $stats['tendencia'] >= 0 ? 'text-emerald-600' : 'text-red-500';
$hasAtrasadas = $stats['atrasadas'] > 0;
$atBorder     = $hasAtrasadas ? 'border-red-200 bg-red-50 hover:border-red-300' : 'border-slate-200 bg-slate-50 hover:border-slate-300';
$atLabel      = $hasAtrasadas ? 'text-red-500' : 'text-slate-400';
$atIcon       = $hasAtrasadas ? 'bg-red-100 text-red-500' : 'bg-slate-200 text-slate-400';
$atNum        = $hasAtrasadas ? 'text-red-700' : 'text-slate-900';
$atFoot       = $hasAtrasadas
    ? '<span class="font-semibold text-red-600">requer atenção</span>'
    : '<span class="font-semibold text-emerald-600">tudo em dia</span>';
@endphp
<div class="mb-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-black/[0.06]">
    <div class="h-0.5 bg-gradient-to-r from-blue-500 via-violet-500 to-blue-400"></div>

    <div class="px-6 pb-6 pt-5">
        {{-- Header --}}
        <div class="mb-5 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                    {{ $greeting }}, {{ explode(' ', auth()->user()->name)[0] }}
                </h1>
                <p class="mt-0.5 text-xs text-slate-400">
                    {{ $dataStr }}
                    @if($hasAtrasadas)
                    &nbsp;·&nbsp;<span class="font-semibold text-red-500">{{ $stats['atrasadas'] }} atrasada{{ $stats['atrasadas'] > 1 ? 's' : '' }}</span>
                    @endif
                </p>
            </div>
            <a href="{{ route('app.os.create') }}"
               class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 active:scale-[0.97] sm:w-auto">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                </svg>
                Nova OS
            </a>
        </div>

        {{-- KPI + chart — flex flex-col + mt-auto garante altura igual --}}
        <div id="dashboard-kpis" data-live-refresh="20" class="grid grid-cols-2 items-stretch gap-3 sm:grid-cols-4 lg:grid-cols-[1fr_1fr_1fr_1fr_minmax(160px,1fr)]">

            {{-- Total de OS --}}
            <div class="flex flex-col rounded-lg border border-slate-200 bg-slate-50 p-4 transition hover:border-slate-300 hover:bg-white hover:shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Total de OS</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-md bg-slate-200 text-slate-500">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                            <path d="M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v0a2 2 0 0 1-2 2H11a2 2 0 0 1-2-2z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-4xl font-black leading-none tabular-nums text-slate-900">{{ $stats['total_ordens'] }}</p>
                <p class="mt-auto pt-3 text-[11px] text-slate-400">
                    <span class="font-semibold text-slate-600">{{ $stats['semana_atual'] }}</span> esta semana
                </p>
            </div>

            {{-- Em aberto --}}
            <div class="flex flex-col rounded-lg border border-blue-200 bg-blue-50 p-4 transition hover:border-blue-300 hover:bg-white hover:shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-blue-500">Em aberto</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-md bg-blue-100 text-blue-500">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-4xl font-black leading-none tabular-nums text-blue-700">{{ $stats['em_aberto'] }}</p>
                <p class="mt-auto pt-3 text-[11px] text-blue-400">
                    <span class="font-semibold text-blue-600">{{ $pctAberto }}%</span> do total
                </p>
            </div>

            {{-- Finalizadas --}}
            <div class="flex flex-col rounded-lg border border-emerald-200 bg-emerald-50 p-4 transition hover:border-emerald-300 hover:bg-white hover:shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-emerald-600">Finalizadas</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-100 text-emerald-600">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-4xl font-black leading-none tabular-nums text-emerald-700">{{ $stats['finalizadas'] }}</p>
                <p class="mt-auto pt-3 text-[11px] text-emerald-500">
                    <span class="font-semibold text-emerald-700">{{ $stats['taxa_conclusao'] }}%</span> de conclusão
                </p>
            </div>

            {{-- Atrasadas --}}
            <div class="flex flex-col rounded-lg border {{ $atBorder }} p-4 transition hover:bg-white hover:shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-semibold uppercase tracking-widest {{ $atLabel }}">Atrasadas</p>
                    <div class="flex h-7 w-7 items-center justify-center rounded-md {{ $atIcon }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-4xl font-black leading-none tabular-nums {{ $atNum }}">{{ $stats['atrasadas'] }}</p>
                <p class="mt-auto pt-3 text-[11px]">{!! $atFoot !!}</p>
            </div>

            {{-- Gráfico 7 dias --}}
            <div class="col-span-2 flex flex-col rounded-lg border border-slate-200 bg-slate-50 p-4 transition hover:border-slate-300 hover:bg-white hover:shadow-sm sm:col-span-4 lg:col-span-1">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">OS · 7 dias</p>
                    <span class="text-[10px] font-bold {{ $tendCor }}">{{ $tendSinal }} {{ abs($stats['tendencia']) }}%</span>
                </div>
                <div class="mt-auto flex items-end justify-between gap-1 pt-3" style="height:56px;">
                    @foreach($ultimos7dias as $label => $qty)
                    @php $barH = max(3, (int)round(($qty / $maxDia) * 44)); @endphp
                    <div class="flex flex-1 flex-col items-center gap-0.5">
                        <div class="w-full rounded-sm bg-gradient-to-t from-blue-500 to-blue-400 transition-all hover:from-blue-400 hover:to-blue-300"
                             style="height:{{ $barH }}px;"></div>
                        <span class="text-[9px] font-medium text-slate-400 tabular-nums">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── OS URGENTES ──────────────────────────────────────────── --}}
@if($urgentes->isNotEmpty())
@php $totalUrgentes = $urgentes->count(); @endphp
<div class="mb-5 flex flex-wrap items-center gap-x-3 gap-y-2 overflow-hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3">
    <div class="flex shrink-0 items-center gap-2">
        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500">
            <svg class="h-3 w-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            </svg>
        </span>
        <span class="text-[12px] font-bold text-red-700">{{ $totalUrgentes }} OS urgente{{ $totalUrgentes > 1 ? 's' : '' }}:</span>
    </div>
    @foreach($urgentes->take(5) as $urg)
    @php $isAtrasada = $urg->previsao_entrega && $urg->previsao_entrega->isPast(); @endphp
    <a href="{{ route('app.os.show', $urg) }}"
       class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-[11.5px] font-semibold transition
              {{ $isAtrasada ? 'bg-red-200 text-red-800 hover:bg-red-300' : 'bg-amber-100 text-amber-800 hover:bg-amber-200' }}">
        {{ $urg->numero }}
        @if($urg->cliente)<span class="font-normal opacity-70">· {{ explode(' ', $urg->cliente->nome)[0] }}</span>@endif
    </a>
    @endforeach
    @if($totalUrgentes > 5)
    <a href="{{ route('app.os.index') }}"
       class="inline-flex items-center rounded-lg bg-red-500 px-2.5 py-1 text-[11.5px] font-bold text-white transition hover:bg-red-600">
        + {{ $totalUrgentes - 5 }} outras →
    </a>
    @endif
    <a href="{{ route('app.os.index') }}" class="ml-auto hidden text-[12px] font-semibold text-red-500 hover:text-red-700 sm:inline">Ver lista →</a>
</div>
@endif

{{-- ── GRID PRINCIPAL ────────────────────────────────────────── --}}
<div class="grid gap-5 lg:grid-cols-3">

    {{-- ── LEFT: OS Recentes ────────────────────────────────── --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06] lg:col-span-2 lg:self-start">
        <div class="h-0.5 bg-gradient-to-r from-blue-500 via-violet-500 to-blue-400"></div>

        <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50">
                    <svg class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-[13.5px] font-bold text-slate-900">Ordens de Serviço</h2>
                    @if($recentes->isNotEmpty())
                    <p class="text-[11px] text-slate-400">{{ $stats['em_aberto'] }} em aberto · {{ $stats['atrasadas'] }} atrasada{{ $stats['atrasadas'] != 1 ? 's' : '' }}</p>
                    @endif
                </div>
            </div>
            <a href="{{ route('app.os.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-[12px] font-semibold text-white transition hover:bg-blue-700 active:scale-95">
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                </svg>
                Nova OS
            </a>
        </div>

        <div id="dashboard-recentes" data-live-refresh="20">
        @if($recentes->isEmpty())
        <div class="flex flex-col items-center justify-center py-14 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">
                <svg class="h-6 w-6 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                </svg>
            </div>
            <p class="text-[13px] font-medium text-slate-400">Nenhuma OS registrada ainda</p>
            <a href="{{ route('app.os.create') }}" class="mt-2 text-[12.5px] font-semibold text-blue-600 hover:text-blue-700">Criar primeira OS →</a>
        </div>
        @else
        <div class="grid grid-cols-[auto_1fr_auto_auto_auto] border-b border-slate-100 bg-slate-50/60 px-5 py-2">
            <span class="w-32 text-[10px] font-semibold uppercase tracking-wider text-slate-400">OS</span>
            <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Cliente</span>
            <span class="w-28 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Status</span>
            <span class="hidden w-32 text-[10px] font-semibold uppercase tracking-wider text-slate-400 md:block">Técnico</span>
            <span class="w-8"></span>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach($recentes as $os)
            @php
                $sc  = $statusCfg[$os->status] ?? null;
                $urg = $urgFn($os);
                $avatarColors = ['bg-blue-100 text-blue-700','bg-violet-100 text-violet-700','bg-emerald-100 text-emerald-700','bg-amber-100 text-amber-700','bg-pink-100 text-pink-700','bg-cyan-100 text-cyan-700','bg-orange-100 text-orange-700','bg-indigo-100 text-indigo-700'];
                $avatarClr = $avatarColors[$loop->index % count($avatarColors)];
            @endphp
            <a href="{{ route('app.os.show', $os) }}"
               class="group grid grid-cols-[auto_1fr_auto_auto_auto] items-center px-5 py-3 transition hover:bg-blue-50/40
                      {{ $urg === 'atrasada' ? 'border-l-[3px] border-l-red-400' : ($urg === 'hoje' ? 'border-l-[3px] border-l-amber-400' : 'border-l-[3px] border-l-transparent') }}">
                <div class="w-32 shrink-0">
                    <span class="font-mono text-[12px] font-bold text-blue-600 tabular-nums group-hover:text-blue-700">{{ $os->numero }}</span>
                    @if($urg === 'atrasada')
                    <span class="ml-1.5 inline-flex items-center gap-0.5 rounded-full bg-red-100 px-1.5 py-0.5 text-[9.5px] font-bold text-red-600">
                        <span class="h-1 w-1 rounded-full bg-red-500"></span>Atrasada
                    </span>
                    @elseif($urg === 'hoje')
                    <span class="ml-1.5 inline-flex items-center gap-0.5 rounded-full bg-amber-100 px-1.5 py-0.5 text-[9.5px] font-bold text-amber-600">
                        <span class="h-1 w-1 rounded-full bg-amber-400"></span>Hoje
                    </span>
                    @endif
                </div>
                <div class="flex min-w-0 items-center gap-2.5">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-[11px] font-bold {{ $avatarClr }}">
                        {{ strtoupper(substr($os->cliente?->nome ?? '?', 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-[12.5px] font-semibold text-slate-800">{{ $os->cliente?->nome ?? '—' }}</p>
                        <p class="truncate text-[11px] text-slate-400">
                            {{ $os->equipamento ? trim($os->equipamento->tipo.' '.($os->equipamento->marca ?? '')) : 'Sem equipamento' }}
                        </p>
                    </div>
                </div>
                <div class="w-28 shrink-0 px-2">
                    @if($sc)
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10.5px] font-semibold {{ $sc['badge'] }}">
                        <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $sc['dot'] }} {{ in_array($os->status, ['execucao','analise']) ? 'animate-pulse' : '' }}"></span>
                        {{ $sc['label'] }}
                    </span>
                    @endif
                </div>
                <div class="hidden w-32 shrink-0 md:block">
                    @if($os->tecnico)
                    <div class="flex items-center gap-1.5">
                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[9px] font-bold text-slate-600">
                            {{ strtoupper(substr($os->tecnico->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-[11px] font-medium text-slate-600">{{ explode(' ', $os->tecnico->name)[0] }}</p>
                            @if($os->previsao_entrega)
                            <p class="text-[10px] tabular-nums {{ $urg === 'atrasada' ? 'font-semibold text-red-500' : ($urg === 'hoje' ? 'font-semibold text-amber-500' : 'text-slate-400') }}">
                                {{ $os->previsao_entrega->format('d/m') }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @elseif($os->previsao_entrega)
                    <p class="text-[11px] tabular-nums {{ $urg === 'atrasada' ? 'font-semibold text-red-500' : ($urg === 'hoje' ? 'font-semibold text-amber-500' : 'text-slate-400') }}">
                        {{ $os->previsao_entrega->format('d/m/Y') }}
                    </p>
                    @else
                    <span class="text-[11px] text-slate-300">—</span>
                    @endif
                </div>
                <div class="flex w-8 justify-end">
                    <span class="flex h-6 w-6 items-center justify-center rounded-md text-slate-300 transition group-hover:bg-blue-100 group-hover:text-blue-600">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                        </svg>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
        <div class="border-t border-slate-100 bg-slate-50/40 px-5 py-2.5">
            <a href="{{ route('app.os.index') }}" class="group inline-flex items-center gap-1 text-[12px] font-semibold text-slate-400 transition hover:text-blue-600">
                Ver todas as OS
                <svg class="h-3 w-3 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                </svg>
            </a>
        </div>
        @endif
        </div>
    </div>

    {{-- ── RIGHT SIDEBAR ────────────────────────────────────── --}}
    <div class="flex flex-col gap-5">

        {{-- Situação das OS --}}
        @php
        $emAndamento = ($contagemStatus['entrada'] ?? 0) + ($contagemStatus['analise'] ?? 0) + ($contagemStatus['execucao'] ?? 0) + ($contagemStatus['em_teste'] ?? 0);
        $aguardando  = $contagemStatus['aguardando_cliente'] ?? 0;
        $concluidas  = $contagemStatus['finalizado'] ?? 0;
        @endphp
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
            <div class="h-0.5 bg-gradient-to-r from-violet-500 via-blue-500 to-cyan-400"></div>
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-violet-50">
                        <svg class="h-3.5 w-3.5 text-violet-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-[13px] font-bold text-slate-900">Situação das OS</h2>
                        <p class="text-[11px] text-slate-400">{{ $stats['total_ordens'] }} no total</p>
                    </div>
                </div>
                <a href="{{ route('app.os.index') }}" class="text-[11px] font-semibold text-slate-400 transition hover:text-blue-600">Ver todas →</a>
            </div>
            <div class="grid grid-cols-3 divide-x divide-slate-100 border-b border-slate-100">
                <div class="px-3 py-2.5 text-center">
                    <p class="text-xl font-black tabular-nums text-blue-700">{{ $emAndamento }}</p>
                    <p class="mt-0.5 text-[9.5px] font-medium text-slate-400">Em andamento</p>
                </div>
                <div class="px-3 py-2.5 text-center">
                    <p class="text-xl font-black tabular-nums text-violet-700">{{ $aguardando }}</p>
                    <p class="mt-0.5 text-[9.5px] font-medium text-slate-400">Aguardando</p>
                </div>
                <div class="px-3 py-2.5 text-center">
                    <p class="text-xl font-black tabular-nums text-emerald-700">{{ $concluidas }}</p>
                    <p class="mt-0.5 text-[9.5px] font-medium text-slate-400">Concluídas</p>
                </div>
            </div>
            <div class="px-5 pt-3 pb-2">
                <div class="flex h-2 gap-px overflow-hidden rounded-full bg-slate-100">
                    @foreach($statusCfg as $key => $cfg)
                    @php $n = $contagemStatus[$key] ?? 0; $w = $total > 1 ? round(($n / $total) * 100) : 0; @endphp
                    @if($n > 0)
                    <div class="{{ $cfg['bar'] }} h-full first:rounded-l-full last:rounded-r-full"
                         style="width:{{ $w }}%" title="{{ $cfg['label'] }}: {{ $n }}"></div>
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="px-3 pb-2">
                @foreach($statusCfg as $key => $cfg)
                @php $n = $contagemStatus[$key] ?? 0; $pct = $total > 1 ? round(($n / $total) * 100) : 0; @endphp
                <a href="{{ route('app.os.index') }}?status={{ $key }}"
                   class="group flex items-center gap-3 rounded-xl px-3 py-1.5 transition {{ $n > 0 ? 'hover:bg-slate-50' : 'pointer-events-none opacity-30' }}">
                    <span class="h-2 w-2 shrink-0 rounded-full {{ $cfg['dot'] }} {{ $n > 0 && in_array($key, ['execucao','analise']) ? 'animate-pulse' : '' }}"></span>
                    <span class="flex-1 text-[12px] font-medium text-slate-700">{{ $cfg['label'] }}</span>
                    <div class="flex items-center gap-2">
                        <div class="h-1 w-14 overflow-hidden rounded-full bg-slate-100">
                            <div class="{{ $cfg['bar'] }} h-full rounded-full" style="width:{{ $pct }}%"></div>
                        </div>
                        <span class="w-5 text-right text-[12px] font-bold tabular-nums {{ $n > 0 ? 'text-slate-700' : 'text-slate-300' }}">{{ $n }}</span>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="border-t border-slate-100 bg-slate-50/40 px-5 py-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] text-slate-400">Taxa de conclusão</span>
                    <span class="text-[12px] font-black {{ $stats['taxa_conclusao'] >= 70 ? 'text-emerald-600' : ($stats['taxa_conclusao'] >= 40 ? 'text-amber-600' : 'text-slate-500') }}">{{ $stats['taxa_conclusao'] }}%</span>
                </div>
                <div class="mt-1.5 h-1 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-emerald-500" style="width:{{ $stats['taxa_conclusao'] }}%"></div>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]" x-data="{ enabled: {{ $botEnabled ? 'true' : 'false' }}, saving: false }">
            <div class="h-0.5 {{ $botEnabled ? 'bg-gradient-to-r from-emerald-400 to-emerald-500' : 'bg-slate-200' }}"></div>
            <div class="flex items-center gap-3 px-5 py-3.5">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg" :class="enabled ? 'bg-emerald-50' : 'bg-slate-100'">
                    <svg class="h-4 w-4" :class="enabled ? 'text-emerald-600' : 'text-slate-400'" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-slate-900">Chatbot WhatsApp</p>
                    <p class="text-[11px]" :class="enabled ? 'text-emerald-600' : 'text-slate-400'" x-text="enabled ? 'Ativo — respondendo clientes' : 'Desativado'"></p>
                </div>
                <button @click="enabled = !enabled; aving = true;
                    fetch('{{ route('app.whatsapp.bot-toggle') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                        body: JSON.stringify({ enabled })
                    }).finally(() => saving = false);
                "
                    :disabled="saving"
                    :class="enabled ? 'bg-emerald-500 hover:bg-emerald-600' : 'bg-slate-300 hover:bg-slate-400'"
                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full transition-colors duration-200 disabled:opacity-60">
                    <span :class="enabled ? 'translate-x-6' : 'translate-x-1'"
                          class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform duration-200"></span>
                </button>
            </div>
            <div class="border-t border-slate-100 bg-slate-50/40 px-5 py-2">
                <a href="{{ route('app.whatsapp.index') }}" class="text-[11px] font-semibold text-slate-400 transition hover:text-emerald-600">
                    Configurações do WhatsApp →
                </a>
            </div>
        </div>

        {{-- Mensagens de Clientes --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
            <div class="h-0.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-400"></div>
            <div class="flex items-center gap-2.5 border-b border-slate-100 px-5 py-3">
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-50">
                    <svg class="h-3.5 w-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <h2 class="flex-1 text-[13px] font-bold text-slate-900">Mensagens de Clientes</h2>
                @if($totalMensagensNaoLidas > 0)
                <span class="flex h-5 min-w-[20px] items-center justify-center rounded-full bg-blue-600 px-1.5 text-[10px] font-bold text-white">
                    {{ $totalMensagensNaoLidas }}
                </span>
                @endif
            </div>
            @if($mensagensClientes->isEmpty())
            <div class="flex flex-col items-center justify-center py-8 text-center">
                <svg class="h-8 w-8 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <p class="mt-2 text-[12px] text-slate-400">Nenhuma mensagem não lida</p>
            </div>
            @else
            <div class="divide-y divide-slate-50">
                @foreach($mensagensClientes->take(4) as $msg)
                <a href="{{ route('app.os.show', $msg->ordem) }}#mensagens"
                   class="group flex items-start gap-3 px-5 py-3 transition hover:bg-slate-50">
                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-100 text-[9px] font-bold text-blue-700">
                        {{ strtoupper(substr($msg->ordem?->cliente?->nome ?? '?', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-baseline justify-between gap-2">
                            <p class="truncate text-[12.5px] font-bold text-slate-800">{{ $msg->ordem?->cliente?->nome ?? '—' }}</p>
                            <span class="shrink-0 text-[10px] text-slate-400">{{ $msg->created_at->diffForHumans(null, true) }}</span>
                        </div>
                        <p class="text-[10px] font-mono font-semibold text-blue-600">{{ $msg->ordem?->numero }}</p>
                        <p class="mt-0.5 line-clamp-2 text-[11px] leading-relaxed text-slate-500">{{ $msg->conteudo }}</p>
                    </div>
                    <svg class="mt-1.5 h-3 w-3 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                    </svg>
                </a>
                @endforeach
            </div>
            @if($totalMensagensNaoLidas > $mensagensClientes->take(4)->count())
            <div class="border-t border-slate-50 px-5 py-2 text-center">
                <span class="text-[11px] text-slate-400">+ {{ $totalMensagensNaoLidas - $mensagensClientes->take(4)->count() }} outras não lidas</span>
            </div>
            @endif
            @endif
        </div>


    </div>{{-- /sidebar --}}
</div>{{-- /grid --}}

@endsection
