@extends('layouts.app')
@section('title', 'Agenda de Visitas')

@section('breadcrumbs')
<span class="font-semibold text-slate-900">Agenda de Visitas</span>
@endsection

@section('content')
@php
    $statusCfg = [
        'entrada'            => ['label' => 'Entrada',       'badge' => 'bg-slate-100 text-slate-700',    'bar' => 'bg-slate-400',    'dot' => 'bg-slate-400'],
        'analise'            => ['label' => 'Em Análise',    'badge' => 'bg-amber-100 text-amber-700',    'bar' => 'bg-amber-400',    'dot' => 'bg-amber-400'],
        'execucao'           => ['label' => 'Em Execução',   'badge' => 'bg-blue-100 text-blue-700',      'bar' => 'bg-blue-500',     'dot' => 'bg-blue-500'],
        'aguardando_cliente' => ['label' => 'Aguard. Cliente','badge' => 'bg-violet-100 text-violet-700', 'bar' => 'bg-violet-500',   'dot' => 'bg-violet-500'],
        'em_teste'           => ['label' => 'Em Teste',      'badge' => 'bg-cyan-100 text-cyan-700',      'bar' => 'bg-cyan-500',     'dot' => 'bg-cyan-500'],
        'finalizado'         => ['label' => 'Finalizado',    'badge' => 'bg-emerald-100 text-emerald-700','bar' => 'bg-emerald-500',  'dot' => 'bg-emerald-500'],
        'cancelado'          => ['label' => 'Cancelado',     'badge' => 'bg-red-100 text-red-600',        'bar' => 'bg-red-400',      'dot' => 'bg-red-400'],
    ];

    $deviceIcon = function(string $tipo): string {
        return match(strtolower($tipo ?? '')) {
            'notebook','laptop' => 'M2 6h20v12H2z M1 18h22 M8 21h8 M12 18v3',
            'celular','smartphone' => 'M7 2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z M12 18h.01',
            'tablet' => 'M6 2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z M12 18h.01',
            'impressora' => 'M6 9V2h12v7 M4 9h16a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1h-2v4H6v-4H4a1 1 0 0 1-1-1v-6a1 1 0 0 1 1-1z',
            default => 'M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z',
        };
    };

    $dataNav  = $data;
    $isHoje   = $data->isToday();
@endphp

<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    {{-- Navegação de data --}}
    <div class="flex items-center gap-2" x-data>
        <a href="{{ route('app.agenda.index', array_merge(request()->query(), ['data' => $modo === 'semana' ? $data->clone()->subWeek()->format('Y-m-d') : $data->clone()->subDay()->format('Y-m-d'), 'modo' => $modo])) }}"
           class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm hover:border-slate-300 hover:bg-slate-50 transition">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
        </a>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                    class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13.5px] font-semibold text-slate-800 shadow-sm hover:border-slate-300 hover:bg-slate-50 transition min-w-[180px] justify-center">
                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                @if($modo === 'semana')
                    {{ $data->clone()->startOfWeek()->format('d/m') }} — {{ $data->clone()->endOfWeek()->format('d/m/Y') }}
                @else
                    {{ $isHoje ? 'Hoje, ' : '' }}{{ $data->translatedFormat('d \d\e M \d\e Y') }}
                @endif
            </button>

            <div x-show="open" @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 class="absolute left-0 top-full z-20 mt-1.5 w-72 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl" style="display:none">
                <form method="GET" action="{{ route('app.agenda.index') }}" class="p-4">
                    <input type="hidden" name="modo" value="{{ $modo }}">
                    @if(request('tecnico'))<input type="hidden" name="tecnico" value="{{ request('tecnico') }}">@endif
                    <label class="block text-[11.5px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Ir para data</label>
                    <div class="flex gap-2">
                        <input type="date" name="data" value="{{ $data->format('Y-m-d') }}"
                               class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13px] text-slate-800 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition">
                        <button type="submit" class="flex items-center justify-center rounded-xl bg-blue-600 px-3.5 text-white hover:bg-blue-700 transition">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                    <a href="{{ route('app.agenda.index', ['modo' => $modo]) }}"
                       class="mt-2.5 flex items-center justify-center gap-1.5 rounded-xl bg-slate-100 py-2 text-[12.5px] font-semibold text-slate-600 hover:bg-slate-200 transition">
                        Voltar para hoje
                    </a>
                </form>
            </div>
        </div>

        <a href="{{ route('app.agenda.index', array_merge(request()->query(), ['data' => $modo === 'semana' ? $data->clone()->addWeek()->format('Y-m-d') : $data->clone()->addDay()->format('Y-m-d'), 'modo' => $modo])) }}"
           class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm hover:border-slate-300 hover:bg-slate-50 transition">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
        </a>

        @if(!$isHoje)
        <a href="{{ route('app.agenda.index', ['modo' => $modo]) }}"
           class="flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-[12.5px] font-semibold text-blue-700 hover:bg-blue-100 transition">
            Hoje
        </a>
        @endif
    </div>

    {{-- Modo dia / semana + Filtro técnico --}}
    <div class="flex items-center gap-2 flex-wrap">
        {{-- Modo --}}
        <div class="flex rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <a href="{{ route('app.agenda.index', array_merge(request()->query(), ['modo' => 'dia'])) }}"
               class="px-3.5 py-2 text-[12.5px] font-semibold transition {{ $modo === 'dia' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                Dia
            </a>
            <a href="{{ route('app.agenda.index', array_merge(request()->query(), ['modo' => 'semana'])) }}"
               class="px-3.5 py-2 text-[12.5px] font-semibold transition {{ $modo === 'semana' ? 'bg-blue-600 text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                Semana
            </a>
        </div>

        {{-- Filtro técnico --}}
        <form method="GET" action="{{ route('app.agenda.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="data" value="{{ $data->format('Y-m-d') }}">
            <input type="hidden" name="modo" value="{{ $modo }}">
            <select name="tecnico" onchange="this.form.submit()"
                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-[12.5px] font-medium text-slate-700 shadow-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition">
                <option value="">Todos os técnicos</option>
                @foreach($tecnicos as $tec)
                <option value="{{ $tec->id }}" {{ request('tecnico') == $tec->id ? 'selected' : '' }}>
                    {{ $tec->name }}
                </option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('app.os.create') }}"
           class="flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-4 py-2 text-[12.5px] font-semibold text-white shadow-sm shadow-blue-600/20 transition">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
            Nova OS
        </a>
    </div>
</div>

{{-- ════════════════════════════════════════════
     CARDS DE STATS (Hoje)
════════════════════════════════════════════ --}}
<div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
    @foreach([
        ['label' => 'Previstas hoje',  'value' => $statsHoje['total'],       'color' => 'blue',    'icon' => 'M3 4h18v16H3z M8 2v4M16 2v4M3 10h18'],
        ['label' => 'Finalizadas',     'value' => $statsHoje['finalizadas'], 'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'Em andamento',    'value' => $statsHoje['em_andamento'],'color' => 'amber',   'icon' => 'M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83'],
        ['label' => 'Atrasadas',       'value' => $statsHoje['atrasadas'],   'color' => 'red',     'icon' => 'M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z'],
    ] as $s)
    @php
        $colors = [
            'blue'    => ['bg' => 'bg-blue-50',    'icon' => 'text-blue-600',    'num' => 'text-blue-700',    'ring' => 'ring-blue-100'],
            'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-600', 'num' => 'text-emerald-700', 'ring' => 'ring-emerald-100'],
            'amber'   => ['bg' => 'bg-amber-50',   'icon' => 'text-amber-600',   'num' => 'text-amber-700',   'ring' => 'ring-amber-100'],
            'red'     => ['bg' => 'bg-red-50',     'icon' => 'text-red-500',     'num' => 'text-red-700',     'ring' => 'ring-red-100'],
        ][$s['color']];
    @endphp
    <div class="flex items-center gap-4 rounded-2xl bg-white border border-slate-200 px-5 py-4 shadow-sm ring-1 ring-black/[0.04]">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $colors['bg'] }} ring-1 {{ $colors['ring'] }}">
            <svg class="h-5 w-5 {{ $colors['icon'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/></svg>
        </div>
        <div>
            <p class="text-[22px] font-black leading-none {{ $colors['num'] }}">{{ $s['value'] }}</p>
            <p class="mt-0.5 text-[11.5px] font-medium text-slate-500">{{ $s['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ════════════════════════════════════════════
     VISÃO DIA
════════════════════════════════════════════ --}}
@if($modo === 'dia')

@if($ordens->isEmpty())
<div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-white py-20 text-center shadow-sm">
    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 ring-1 ring-slate-200">
        <svg class="h-8 w-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
    </div>
    <p class="text-[15px] font-bold text-slate-700">
        Nenhuma OS prevista para {{ $isHoje ? 'hoje' : $data->translatedFormat('d \d\e M') }}
    </p>
    <p class="mt-1.5 text-[13px] text-slate-400">As ordens com previsão de entrega nesta data aparecerão aqui.</p>
    <a href="{{ route('app.os.create') }}"
       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 text-[13px] font-semibold text-white shadow-md shadow-blue-600/20 transition">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
        Registrar nova entrada
    </a>
</div>

@else

<div class="grid grid-cols-1 gap-4 lg:grid-cols-[280px_1fr]">

    {{-- Coluna lateral: técnicos do dia --}}
    <div class="space-y-3">
        <p class="text-[11.5px] font-bold uppercase tracking-widest text-slate-400">Técnicos escalados</p>

        @php $tecnicosNaDia = $ordens->groupBy('tecnico_id'); @endphp

        @foreach($tecnicosNaDia as $tecId => $ordensDoTec)
        @php
            $tec      = $ordensDoTec->first()->tecnico;
            $total    = $ordensDoTec->count();
            $concl    = $ordensDoTec->where('status', 'finalizado')->count();
            $pct      = $total > 0 ? (int) round($concl / $total * 100) : 0;
        @endphp
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-[12px] font-bold text-white shadow-md shadow-blue-500/20">
                    {{ strtoupper(substr($tec?->name ?? 'T', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-slate-900 truncate">{{ $tec?->name ?? 'Sem técnico' }}</p>
                    <p class="text-[11.5px] text-slate-400">{{ $total }} OS · {{ $concl }} finalizadas</p>
                </div>
            </div>
            <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ $pct }}%"></div>
            </div>
            <div class="mt-1.5 flex justify-between text-[11px] text-slate-400">
                <span>{{ $pct }}% concluído</span>
                <span>{{ $total - $concl }} restantes</span>
            </div>
        </div>
        @endforeach

        @if($ordens->whereNull('tecnico_id')->isNotEmpty())
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
            <div class="flex items-center gap-2 mb-1">
                <svg class="h-4 w-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                <p class="text-[12.5px] font-bold text-amber-800">Sem técnico</p>
            </div>
            <p class="text-[12px] text-amber-700">{{ $ordens->whereNull('tecnico_id')->count() }} OS sem técnico atribuído</p>
        </div>
        @endif
    </div>

    {{-- Coluna principal: lista de OS --}}
    <div class="space-y-3">
        @foreach($ordens as $os)
        @php
            $sc  = $statusCfg[$os->status] ?? $statusCfg['entrada'];
            $tip = $os->equipamento?->tipo ?? '';
            $atrasado = $os->previsao_entrega && $os->previsao_entrega->isPast() && $os->status !== 'finalizado';
        @endphp
        <div class="group relative flex overflow-hidden rounded-2xl border {{ $atrasado ? 'border-red-200 bg-red-50/30' : 'border-slate-200 bg-white' }} shadow-sm hover:shadow-md hover:border-slate-300 transition-all">

            {{-- Barra lateral de status --}}
            <div class="w-1.5 shrink-0 {{ $sc['bar'] }}"></div>

            <div class="flex flex-1 flex-col sm:flex-row sm:items-start gap-4 p-4">

                {{-- Ícone do equipamento --}}
                <div class="hidden sm:flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 ring-1 ring-slate-200">
                    <svg class="h-5 w-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $deviceIcon($tip) }}"/>
                    </svg>
                </div>

                {{-- Dados principais --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-start gap-2 mb-1">
                        <span class="font-mono text-[12.5px] font-bold text-slate-800">{{ $os->codigo_publico ?? '#'.$os->id }}</span>
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $sc['badge'] }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $sc['dot'] }}"></span>
                            {{ $sc['label'] }}
                        </span>
                        @if($atrasado)
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-0.5 text-[11px] font-bold text-red-700 ring-1 ring-red-200">
                            Atrasada
                        </span>
                        @endif
                    </div>

                    <p class="text-[14px] font-semibold text-slate-900">{{ $os->cliente?->nome ?? '—' }}</p>

                    <div class="mt-1.5 flex flex-wrap gap-x-4 gap-y-1">
                        @if($os->equipamento)
                        <span class="flex items-center gap-1.5 text-[12px] text-slate-500">
                            <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $deviceIcon($tip) }}"/></svg>
                            {{ $os->equipamento->marca }} {{ $os->equipamento->modelo }}
                        </span>
                        @endif

                        @if($os->tecnico)
                        <span class="flex items-center gap-1.5 text-[12px] text-slate-500">
                            <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            {{ $os->tecnico->name }}
                        </span>
                        @else
                        <span class="flex items-center gap-1.5 text-[12px] text-amber-600 font-medium">
                            <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                            Sem técnico
                        </span>
                        @endif

                        <span class="flex items-center gap-1.5 text-[12px] text-slate-500">
                            <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Previsão: {{ $os->previsao_entrega?->format('d/m/Y') ?? '—' }}
                        </span>
                    </div>

                    @if($os->problema_relatado)
                    <p class="mt-2 line-clamp-1 text-[12px] text-slate-400">{{ $os->problema_relatado }}</p>
                    @endif
                </div>

                {{-- Ações --}}
                <div class="flex shrink-0 items-center gap-2 sm:flex-col sm:items-end">
                    <a href="{{ route('app.os.show', $os) }}"
                       class="flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-[12px] font-semibold text-slate-700 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 transition-all">
                        Ver OS
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    </a>

                    @if($os->cliente?->telefone)
                    @php $tel = preg_replace('/\D/', '', $os->cliente->telefone); @endphp
                    <a href="https://wa.me/55{{ $tel }}" target="_blank" rel="noopener"
                       class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#22c55e]/10 text-[#16a34a] hover:bg-[#22c55e]/20 transition-colors border border-[#22c55e]/20">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════
     VISÃO SEMANA
════════════════════════════════════════════ --}}
@else
@php
    $inicioSemana = $data->clone()->startOfWeek();
    $dias = collect(range(0, 6))->map(fn($i) => $inicioSemana->clone()->addDays($i));
@endphp

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    {{-- Header dos dias --}}
    <div class="grid grid-cols-7 border-b border-slate-100">
        @foreach($dias as $dia)
        @php $isHojeDia = $dia->isToday(); @endphp
        <div class="px-2 py-3 text-center border-r border-slate-100 last:border-r-0 {{ $isHojeDia ? 'bg-blue-50' : '' }}">
            <p class="text-[10.5px] font-semibold uppercase tracking-wider {{ $isHojeDia ? 'text-blue-600' : 'text-slate-400' }}">
                {{ $dia->translatedFormat('D') }}
            </p>
            <div class="mt-0.5 flex h-7 w-7 mx-auto items-center justify-center rounded-full text-[13px] font-bold {{ $isHojeDia ? 'bg-blue-600 text-white' : 'text-slate-700' }}">
                {{ $dia->format('d') }}
            </div>
            @php $qtd = $ordens->filter(fn($o) => $o->previsao_entrega?->isSameDay($dia))->count(); @endphp
            @if($qtd > 0)
            <div class="mt-1 flex justify-center">
                <span class="h-1.5 w-1.5 rounded-full {{ $isHojeDia ? 'bg-blue-500' : 'bg-slate-400' }}"></span>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Cells dos dias --}}
    <div class="grid grid-cols-7 min-h-[400px]">
        @foreach($dias as $dia)
        @php
            $osNoDia = $ordens->filter(fn($o) => $o->previsao_entrega?->isSameDay($dia));
            $isHojeDia = $dia->isToday();
        @endphp
        <div class="border-r border-slate-100 last:border-r-0 p-2 space-y-1 {{ $isHojeDia ? 'bg-blue-50/30' : '' }} min-h-[120px]">
            @forelse($osNoDia as $os)
            @php $sc = $statusCfg[$os->status] ?? $statusCfg['entrada']; @endphp
            <a href="{{ route('app.os.show', $os) }}"
               class="group block rounded-lg p-1.5 hover:shadow-sm transition-all ring-1 ring-black/[0.04] {{ str_replace('text-', 'bg-', explode(' ', $sc['badge'])[0]) }}/40 border-l-2 {{ str_replace('bg-', 'border-', explode(' ', $sc['bar'])[0]) }}">
                <p class="font-mono text-[10px] font-bold text-slate-700 truncate">{{ $os->codigo_publico ?? '#'.$os->id }}</p>
                <p class="text-[10.5px] font-medium text-slate-600 truncate">{{ $os->cliente?->nome }}</p>
                <span class="inline-block mt-0.5 rounded text-[9.5px] font-semibold {{ $sc['badge'] }} px-1.5 py-0.5">{{ $sc['label'] }}</span>
            </a>
            @empty
            <div class="flex h-full min-h-[80px] items-center justify-center">
                <p class="text-[11px] text-slate-300">—</p>
            </div>
            @endforelse
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
