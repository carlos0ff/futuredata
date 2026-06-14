@extends('layouts.app')
@section('title', 'Financeiro')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [['label' => 'Financeiro']]
    ])
@endsection

@section('content')
@php
$meses = ['','Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
$nomeMes = $meses[$mes];
$fmt = fn(float $v) => 'R$ ' . number_format($v, 2, ',', '.');
$fmtK = function(float $v): string {
    if ($v >= 1000) return 'R$ ' . number_format($v / 1000, 1, ',', '') . 'k';
    return 'R$ ' . number_format($v, 2, ',', '.');
};
$maxMensal = max(1, $mensal->max('total'));
@endphp

{{-- ── HEADER ─────────────────────────────────────────────── --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Financeiro</h1>
        <p class="mt-0.5 text-sm text-slate-500">
            {{ $nomeMes }} de {{ $ano }}
            @if($variacaoMes !== null)
                · <span class="{{ $variacaoMes >= 0 ? 'text-emerald-600' : 'text-red-500' }} font-semibold">
                    {{ $variacaoMes >= 0 ? '↑' : '↓' }} {{ abs($variacaoMes) }}%
                </span> vs mês anterior
            @endif
        </p>
    </div>

    <form method="GET" action="{{ route('app.financeiro.index') }}"
          class="flex flex-wrap items-center gap-2">
        <select name="mes" onchange="this.form.submit()"
                class="h-9 appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" @selected($mes == $m)>{{ $meses[$m] }}</option>
            @endforeach
        </select>
        <select name="ano" onchange="this.form.submit()"
                class="h-9 appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            @foreach(range(now()->year - 2, now()->year) as $y)
                <option value="{{ $y }}" @selected($ano == $y)>{{ $y }}</option>
            @endforeach
        </select>
        <a href="{{ route('app.financeiro.receitas', ['mes' => $mes, 'ano' => $ano]) }}"
           class="h-9 inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
            <svg class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
            Receitas
        </a>
    </form>
</div>

{{-- ── KPI CARDS ──────────────────────────────────────────── --}}
<div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

    {{-- Faturamento do mês --}}
    <div class="relative overflow-hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
        <div class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100">
            <svg class="h-4.5 w-4.5 text-emerald-600" style="width:18px;height:18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-600/70">Faturamento do Mês</p>
        <p class="mt-2 text-3xl font-black leading-none tracking-tight text-emerald-800 tabular-nums">
            {{ $fmtK((float)$stats['faturamento']) }}
        </p>
        <p class="mt-2 text-xs text-emerald-600/70">
            @if($variacaoMes !== null)
                <span class="{{ $variacaoMes >= 0 ? 'text-emerald-700' : 'text-red-500' }} font-semibold">
                    {{ $variacaoMes >= 0 ? '↑' : '↓' }} {{ abs($variacaoMes) }}%
                </span>
                vs {{ $meses[$mes == 1 ? 12 : $mes-1] }}
            @else
                <span class="text-slate-400">Sem histórico anterior</span>
            @endif
        </p>
    </div>

    {{-- OS Finalizadas --}}
    <div class="relative overflow-hidden rounded-2xl border border-blue-200 bg-blue-50 p-5 shadow-sm">
        <div class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100">
            <svg style="width:18px;height:18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
            </svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-600/70">OS Finalizadas</p>
        <p class="mt-2 text-3xl font-black leading-none tracking-tight text-blue-800 tabular-nums">
            {{ $stats['finalizadas'] }}
        </p>
        <p class="mt-2 text-xs text-blue-600/70">no mês de {{ $nomeMes }}</p>
    </div>

    {{-- Ticket Médio --}}
    <div class="relative overflow-hidden rounded-2xl border border-violet-200 bg-violet-50 p-5 shadow-sm">
        <div class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-xl bg-violet-100">
            <svg style="width:18px;height:18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-violet-600">
                <path d="M9 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-3M9 14l5-5m0 0v4m0-4h-4"/>
                <path d="M21 3l-6 6"/>
            </svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-violet-600/70">Ticket Médio</p>
        <p class="mt-2 text-3xl font-black leading-none tracking-tight text-violet-800 tabular-nums">
            {{ $fmtK((float)$stats['ticket_medio']) }}
        </p>
        <p class="mt-2 text-xs text-violet-600/70">por OS finalizada</p>
    </div>

    {{-- Total no Ano --}}
    <div class="relative overflow-hidden rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
        <div class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100">
            <svg style="width:18px;height:18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-600">
                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/>
            </svg>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-amber-600/70">Total {{ $ano }}</p>
        <p class="mt-2 text-3xl font-black leading-none tracking-tight text-amber-800 tabular-nums">
            {{ $fmtK((float)$stats['total_ano']) }}
        </p>
        <p class="mt-2 text-xs text-amber-600/70">acumulado no ano</p>
    </div>
</div>

{{-- ── GRÁFICO MENSAL + TOP CLIENTES ─────────────────────── --}}
<div class="mb-6 grid gap-6 lg:grid-cols-3">

    {{-- Gráfico de barras (2/3) --}}
    <div class="lg:col-span-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Faturamento Mensal</h2>
                <p class="mt-0.5 text-xs text-slate-400">Receita por mês em {{ $ano }}</p>
            </div>
            <span class="text-xs font-semibold text-slate-400 tabular-nums">
                Acumulado: {{ $fmtK((float)$stats['total_ano']) }}
            </span>
        </div>
        <div class="px-6 py-5">
            <div class="flex items-end justify-between gap-2" style="height: 140px">
                @foreach(range(1,12) as $m)
                @php
                    $dado  = $mensal->get($m);
                    $val   = $dado ? (float)$dado->total : 0;
                    $h     = $val > 0 ? max(8, (int) round(($val / $maxMensal) * 120)) : 3;
                    $isAtual = $m === $mes;
                @endphp
                <div class="group relative flex flex-1 flex-col items-center gap-1.5">
                    {{-- Tooltip --}}
                    @if($val > 0)
                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-800 px-2 py-1 text-[10px] font-bold text-white opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-lg z-10">
                        {{ $fmtK($val) }}
                        @if($dado->qtd) <span class="text-slate-400"> · {{ $dado->qtd }} OS</span>@endif
                    </div>
                    @endif
                    {{-- Barra --}}
                    <div class="w-full rounded-t-md transition-all duration-300
                        {{ $isAtual
                            ? 'bg-emerald-500 shadow-md shadow-emerald-200'
                            : ($val > 0 ? 'bg-slate-200 group-hover:bg-emerald-300' : 'bg-slate-100') }}"
                         style="height: {{ $h }}px"></div>
                    {{-- Rótulo --}}
                    <span class="text-[9px] font-semibold {{ $isAtual ? 'text-emerald-600' : 'text-slate-400' }} tabular-nums">
                        {{ substr($meses[$m], 0, 3) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Top Clientes (1/3) --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-sm font-bold text-slate-900">Top Clientes</h2>
            <p class="mt-0.5 text-xs text-slate-400">Maiores receitas em {{ $nomeMes }}</p>
        </div>
        <div class="p-4">
            @forelse($topClientes as $i => $row)
            @php
                $pct = $stats['faturamento'] > 0 ? round(($row->total / $stats['faturamento']) * 100) : 0;
                $colors = ['bg-emerald-500','bg-blue-500','bg-violet-500','bg-amber-500','bg-slate-400'];
                $bar = $colors[$i] ?? 'bg-slate-400';
            @endphp
            <div class="mb-3 last:mb-0">
                <div class="mb-1 flex items-center justify-between gap-2">
                    <span class="truncate text-xs font-semibold text-slate-700">
                        {{ $row->cliente?->nome ?? 'Cliente #'.$row->cliente_id }}
                    </span>
                    <span class="shrink-0 text-xs font-bold text-slate-900 tabular-nums">{{ $fmtK((float)$row->total) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-slate-100">
                        <div class="{{ $bar }} h-full rounded-full transition-all duration-500"
                             style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="w-8 text-right text-[10px] text-slate-400 tabular-nums">{{ $pct }}%</span>
                </div>
            </div>
            @empty
            <div class="py-8 text-center text-xs text-slate-400">
                Nenhum dado para o período
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ── BREAKDOWN + OS ABERTAS ─────────────────────────────── --}}
<div class="mb-6 grid gap-6 lg:grid-cols-3">

    {{-- Composição da receita --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-sm font-bold text-slate-900">Composição</h2>
            <p class="mt-0.5 text-xs text-slate-400">Serviços · Peças · Descontos</p>
        </div>
        <div class="p-5 space-y-4">
            @php
                $totalBruto = ((float)($breakdown->servicos ?? 0)) + ((float)($breakdown->pecas ?? 0));
                $pctServ = $totalBruto > 0 ? round(((float)($breakdown->servicos ?? 0) / $totalBruto) * 100) : 0;
                $pctPecas = $totalBruto > 0 ? 100 - $pctServ : 0;
            @endphp

            {{-- Serviços --}}
            <div>
                <div class="mb-1.5 flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5 font-semibold text-slate-700">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        Mão de obra
                    </span>
                    <span class="font-bold text-slate-900 tabular-nums">{{ $fmt((float)($breakdown->servicos ?? 0)) }}</span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-blue-500 transition-all duration-500" style="width: {{ $pctServ }}%"></div>
                </div>
                <p class="mt-0.5 text-right text-[10px] text-slate-400">{{ $pctServ }}% da receita bruta</p>
            </div>

            {{-- Peças --}}
            <div>
                <div class="mb-1.5 flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5 font-semibold text-slate-700">
                        <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                        Peças e materiais
                    </span>
                    <span class="font-bold text-slate-900 tabular-nums">{{ $fmt((float)($breakdown->pecas ?? 0)) }}</span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-violet-500 transition-all duration-500" style="width: {{ $pctPecas }}%"></div>
                </div>
                <p class="mt-0.5 text-right text-[10px] text-slate-400">{{ $pctPecas }}% da receita bruta</p>
            </div>

            {{-- Descontos --}}
            @if(($breakdown->descontos ?? 0) > 0)
            <div class="rounded-xl border border-red-100 bg-red-50 p-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-1.5 font-semibold text-red-700">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 12V22H4V12M22 7H2v5h20V7zM12 22V7M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
                        </svg>
                        Descontos concedidos
                    </span>
                    <span class="font-bold text-red-600 tabular-nums">- {{ $fmt((float)$breakdown->descontos) }}</span>
                </div>
            </div>
            @endif

            {{-- Total líquido --}}
            <div class="border-t border-slate-100 pt-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700">Receita líquida</span>
                    <span class="text-base font-black text-emerald-700 tabular-nums">{{ $fmt((float)$stats['faturamento']) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- OS em aberto (potencial) --}}
    <div class="lg:col-span-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Receita Potencial</h2>
                <p class="mt-0.5 text-xs text-slate-400">OS em aberto com valor estimado</p>
            </div>
            <a href="{{ route('app.os.index') }}"
               class="text-xs font-medium text-blue-600 hover:text-blue-700 transition flex items-center gap-1">
                Ver todas
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @php
            $osAbertas = \App\Models\Ordem::with('cliente')
                ->whereNotIn('status', ['finalizado', 'cancelado'])
                ->where(fn($q) => $q->where('valor_servico', '>', 0)->orWhere('valor_pecas', '>', 0))
                ->orderByDesc(\Illuminate\Support\Facades\DB::raw('valor_servico + valor_pecas'))
                ->limit(6)
                ->get();
            $totalPotencial = \App\Models\Ordem::whereNotIn('status', ['finalizado', 'cancelado'])
                ->sum(\Illuminate\Support\Facades\DB::raw('valor_servico + valor_pecas - desconto'));
        @endphp
        <div class="divide-y divide-slate-50">
            @forelse($osAbertas as $os)
            @php
                $valOs = (float)$os->valor_servico + (float)$os->valor_pecas - (float)$os->desconto;
                $statusCores = [
                    'entrada' => 'bg-slate-100 text-slate-600',
                    'analise' => 'bg-amber-100 text-amber-700',
                    'execucao' => 'bg-blue-100 text-blue-700',
                    'aguardando_cliente' => 'bg-violet-100 text-violet-700',
                    'em_teste' => 'bg-cyan-100 text-cyan-700',
                ];
                $badgeOs = $statusCores[$os->status] ?? 'bg-slate-100 text-slate-600';
                $statusLabel = [
                    'entrada' => 'Entrada',
                    'analise' => 'Análise',
                    'execucao' => 'Execução',
                    'aguardando_cliente' => 'Aguardando',
                    'em_teste' => 'Em teste',
                ][$os->status] ?? $os->status;
            @endphp
            <div class="flex items-center gap-4 px-6 py-3">
                <a href="{{ route('app.os.show', $os) }}"
                   class="font-mono text-xs font-bold text-blue-600 hover:text-blue-700 shrink-0">
                    {{ $os->numero }}
                </a>
                <p class="flex-1 truncate text-sm text-slate-700">{{ $os->cliente?->nome ?? '—' }}</p>
                <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $badgeOs }}">
                    {{ $statusLabel }}
                </span>
                <span class="shrink-0 text-sm font-bold text-slate-900 tabular-nums">
                    {{ $valOs > 0 ? $fmt($valOs) : '—' }}
                </span>
            </div>
            @empty
            <div class="py-8 text-center text-xs text-slate-400">
                Nenhuma OS em aberto com valor estimado
            </div>
            @endforelse

            @if($totalPotencial > 0)
            <div class="flex items-center justify-between bg-slate-50 px-6 py-3">
                <span class="text-xs font-semibold text-slate-500">Total em aberto</span>
                <span class="text-sm font-black text-slate-900 tabular-nums">{{ $fmt((float)$totalPotencial) }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ── TABELA DE OS FINALIZADAS ───────────────────────────── --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <div>
            <h2 class="text-sm font-bold text-slate-900">OS Finalizadas — {{ $nomeMes }}/{{ $ano }}</h2>
            <p class="mt-0.5 text-xs text-slate-400">{{ $stats['finalizadas'] }} ordens · clique para abrir</p>
        </div>
        <a href="{{ route('app.financeiro.receitas', ['mes' => $mes, 'ano' => $ano]) }}"
           class="text-xs font-medium text-emerald-600 hover:text-emerald-700 transition flex items-center gap-1">
            Relatório completo →
        </a>
    </div>

    @if($recentes->isEmpty())
    <div class="flex flex-col items-center justify-center py-14 text-center">
        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
            <svg class="h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <p class="text-sm font-semibold text-slate-600">Nenhuma OS finalizada em {{ $nomeMes }}</p>
        <p class="mt-1 text-xs text-slate-400">Selecione outro período ou verifique as OS em andamento.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500">OS</th>
                    <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500">Cliente</th>
                    <th class="hidden px-5 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500 sm:table-cell">Técnico</th>
                    <th class="hidden px-5 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500 lg:table-cell">Finalizado em</th>
                    <th class="px-5 py-3 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500">Serviço</th>
                    <th class="hidden px-5 py-3 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500 md:table-cell">Peças</th>
                    <th class="hidden px-5 py-3 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500 md:table-cell">Desc.</th>
                    <th class="px-5 py-3 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @php $sumServ = $sumPecas = $sumDesc = $sumTotal = 0; @endphp
                @foreach($recentes as $os)
                @php
                    $s = (float)$os->valor_servico;
                    $p = (float)$os->valor_pecas;
                    $d = (float)$os->desconto;
                    $t = $s + $p - $d;
                    $sumServ += $s; $sumPecas += $p; $sumDesc += $d; $sumTotal += $t;
                @endphp
                <tr class="group transition-colors hover:bg-slate-50/70">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('app.os.show', $os) }}"
                           class="font-mono text-xs font-bold text-blue-600 hover:text-blue-700">
                            {{ $os->numero }}
                        </a>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-slate-700 max-w-[160px] truncate">
                        {{ $os->cliente?->nome ?? '—' }}
                    </td>
                    <td class="hidden px-5 py-3.5 text-xs text-slate-500 sm:table-cell">
                        {{ $os->tecnico?->name ?? '—' }}
                    </td>
                    <td class="hidden px-5 py-3.5 text-xs text-slate-500 lg:table-cell tabular-nums">
                        {{ $os->finalizado_em?->format('d/m/Y') ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5 text-right text-sm text-slate-700 tabular-nums">
                        {{ $fmt($s) }}
                    </td>
                    <td class="hidden px-5 py-3.5 text-right text-sm text-slate-700 tabular-nums md:table-cell">
                        {{ $p > 0 ? $fmt($p) : '—' }}
                    </td>
                    <td class="hidden px-5 py-3.5 text-right text-sm tabular-nums md:table-cell {{ $d > 0 ? 'text-red-500' : 'text-slate-300' }}">
                        {{ $d > 0 ? '- '.$fmt($d) : '—' }}
                    </td>
                    <td class="px-5 py-3.5 text-right text-sm font-bold text-slate-900 tabular-nums">
                        {{ $fmt($t) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-slate-200 bg-slate-50">
                    <td colspan="4" class="px-5 py-3 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        Total — {{ $stats['finalizadas'] }} OS
                    </td>
                    <td class="px-5 py-3 text-right text-sm font-bold text-slate-800 tabular-nums">{{ $fmt($sumServ) }}</td>
                    <td class="hidden px-5 py-3 text-right text-sm font-bold text-slate-800 tabular-nums md:table-cell">{{ $fmt($sumPecas) }}</td>
                    <td class="hidden px-5 py-3 text-right text-sm font-bold text-red-500 tabular-nums md:table-cell">
                        {{ $sumDesc > 0 ? '- '.$fmt($sumDesc) : '—' }}
                    </td>
                    <td class="px-5 py-3 text-right text-base font-black text-emerald-700 tabular-nums">{{ $fmt($sumTotal) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</div>

@endsection
