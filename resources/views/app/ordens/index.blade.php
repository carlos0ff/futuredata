@extends('layouts.app')
@section('title', 'Ordens de Serviço')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', ['items' => [['label' => 'Ordens de Serviço']]])
@endsection

@push('styles')
<style>
@keyframes slide-in {
  from { opacity: 0; transform: translateY(5px); }
  to   { opacity: 1; transform: translateY(0); }
}
.os-card {
  animation: slide-in 200ms ease both;
  transition: box-shadow 200ms ease, transform 200ms ease, border-color 200ms ease;
}
.os-card:hover {
  box-shadow: 0 12px 28px -8px rgba(0,0,0,.16);
  transform: translateY(-2px);
  border-color: #bfdbfe;
}
.os-card-icon {
  transition: transform 300ms ease;
}
.os-card:hover .os-card-icon {
  transform: scale(1.12);
}
@keyframes badge-pulse { 0%,100%{opacity:1} 50%{opacity:.65} }
.badge-atrasada { animation: badge-pulse 2.2s ease-in-out infinite; }
</style>
@endpush

@section('content')

@php
$statusMeta = [
    'entrada'            => ['label'=>'Entrada',          'dot'=>'bg-slate-400',   'badge'=>'bg-slate-100 text-slate-700',    'border'=>'border-l-slate-300'],
    'analise'            => ['label'=>'Em análise',       'dot'=>'bg-amber-400',   'badge'=>'bg-amber-100 text-amber-800',    'border'=>'border-l-amber-400'],
    'execucao'           => ['label'=>'Em execução',      'dot'=>'bg-blue-500',    'badge'=>'bg-blue-100 text-blue-800',      'border'=>'border-l-blue-500'],
    'aguardando_cliente' => ['label'=>'Aguard. cliente',  'dot'=>'bg-violet-500',  'badge'=>'bg-violet-100 text-violet-800',  'border'=>'border-l-violet-500'],
    'em_teste'           => ['label'=>'Em teste',         'dot'=>'bg-cyan-500',    'badge'=>'bg-cyan-100 text-cyan-800',      'border'=>'border-l-cyan-500'],
    'finalizado'         => ['label'=>'Finalizado',       'dot'=>'bg-emerald-500', 'badge'=>'bg-emerald-100 text-emerald-800','border'=>'border-l-emerald-500'],
    'cancelado'          => ['label'=>'Cancelado',        'dot'=>'bg-red-400',     'badge'=>'bg-red-100 text-red-700',        'border'=>'border-l-red-400'],
];
@endphp

{{-- ═══ HEADER ════════════════════════════════════════════════════════════ --}}
<div class="mb-7 flex items-center justify-between gap-4">
    <div>
        <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Ordens de Serviço</h1>
        <p class="mt-0.5 text-[12.5px] text-slate-400">
            <span class="font-semibold text-slate-600">{{ $ordens->total() }}</span> OS
            @if(request('busca') || request('status')) · com filtros @endif
        </p>
    </div>
    <a href="{{ route('app.os.create') }}"
       class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5
              text-[13px] font-semibold text-white shadow-lg shadow-blue-600/25
              transition hover:bg-blue-700 active:scale-[0.97]">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
        </svg>
        Nova OS
    </a>
</div>

{{-- ═══ STATS ══════════════════════════════════════════════════════════════ --}}
<div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-4">

    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-black/[0.06] border-l-4 border-l-slate-200">
        <p class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Total</p>
        <p class="mt-1.5 text-[30px] font-black leading-none tracking-tight text-slate-900">{{ $stats['total'] }}</p>
        <p class="mt-1.5 text-[11.5px] text-slate-400">ordens cadastradas</p>
    </div>

    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-black/[0.06] border-l-4 border-l-blue-400">
        <p class="text-[11px] font-semibold uppercase tracking-widest text-blue-500">Em aberto</p>
        <p class="mt-1.5 text-[30px] font-black leading-none tracking-tight text-slate-900">{{ $stats['abertas'] }}</p>
        <p class="mt-1.5 text-[11.5px] text-slate-400">aguardando conclusão</p>
    </div>

    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-black/[0.06] border-l-4 border-l-indigo-400">
        <p class="text-[11px] font-semibold uppercase tracking-widest text-indigo-500">Em execução</p>
        <p class="mt-1.5 text-[30px] font-black leading-none tracking-tight text-slate-900">{{ $stats['execucao'] }}</p>
        <p class="mt-1.5 text-[11.5px] text-slate-400">em andamento agora</p>
    </div>

    <a href="{{ route('app.os.index', ['atrasadas' => '1']) }}"
       class="group rounded-2xl p-5 shadow-sm ring-1 transition border-l-4
           {{ $stats['atrasadas'] > 0
               ? 'bg-red-600 ring-red-600/30 hover:bg-red-700 border-l-red-800'
               : 'bg-white ring-black/[0.06] border-l-slate-200 hover:shadow-md' }}">
        <p class="text-[11px] font-semibold uppercase tracking-widest {{ $stats['atrasadas'] > 0 ? 'text-red-200' : 'text-slate-400' }}">
            Atrasadas
        </p>
        <p class="mt-1.5 text-[30px] font-black leading-none tracking-tight {{ $stats['atrasadas'] > 0 ? 'text-white' : 'text-slate-900' }}">
            {{ $stats['atrasadas'] }}
        </p>
        @if($stats['atrasadas'] > 0)
        <p class="mt-1.5 inline-flex items-center gap-1 text-[11.5px] font-semibold text-red-200 transition group-hover:text-white">
            Ver agora
            <svg class="h-3 w-3 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
            </svg>
        </p>
        @else
        <p class="mt-1.5 text-[11.5px] text-slate-400">tudo em dia</p>
        @endif
    </a>

</div>

{{-- ═══ FILTROS ════════════════════════════════════════════════════════════ --}}
<div class="mb-5 space-y-3">

    <form method="GET" action="{{ route('app.os.index') }}" class="flex gap-2">
        <div class="relative flex-1">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
            <input type="text" name="busca" id="search-input"
                   value="{{ $current['busca'] ?? '' }}"
                   placeholder="Buscar por número, cliente ou equipamento…"
                   class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-[13.5px]
                          text-slate-800 placeholder-slate-400 shadow-sm outline-none transition
                          focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20">
        </div>
        <div class="relative">
            <select name="status" onchange="this.form.submit()"
                    class="h-10 appearance-none cursor-pointer rounded-xl border bg-white pl-3.5 pr-9
                           text-[13px] shadow-sm outline-none transition hover:border-slate-300
                           {{ ($current['status'] ?? '') ? 'border-blue-300 text-blue-700 bg-blue-50' : 'border-slate-200 text-slate-700' }}">
                <option value="">Todos os status</option>
                @foreach($status ?? [] as $key => $cfg)
                    <option value="{{ $key }}" @selected(($current['status'] ?? '') === $key)>{{ $cfg['label'] }}</option>
                @endforeach
            </select>
            <svg class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </div>
        <button type="submit"
                class="h-10 inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-4
                       text-[13px] font-semibold text-white shadow-sm transition hover:bg-slate-800 active:scale-[0.97]">
            Buscar
        </button>
        @if(request('busca') || request('status') || request('atrasadas'))
        <a href="{{ route('app.os.index') }}" title="Limpar filtros"
           class="h-10 inline-flex items-center justify-center rounded-xl border border-red-200 bg-white px-3
                  text-red-400 shadow-sm transition hover:bg-red-50 hover:text-red-500">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
        </a>
        @endif
    </form>

    {{-- Status chips --}}
    <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5">
        @php
        $activeStatus = $current['status'] ?? '';
        $chipDefs = [
            ''                   => ['label' => 'Todas',           'cls' => 'border-slate-200 text-slate-600 hover:border-slate-300'],
            'entrada'            => ['label' => 'Entrada',         'cls' => 'border-slate-200 text-slate-600 hover:border-slate-300'],
            'analise'            => ['label' => 'Em análise',      'cls' => 'border-amber-200  text-amber-700  hover:border-amber-400'],
            'execucao'           => ['label' => 'Em execução',     'cls' => 'border-blue-200   text-blue-700   hover:border-blue-400'],
            'aguardando_cliente' => ['label' => 'Aguard. cliente', 'cls' => 'border-violet-200 text-violet-700 hover:border-violet-400'],
            'em_teste'           => ['label' => 'Em teste',        'cls' => 'border-cyan-200   text-cyan-700   hover:border-cyan-400'],
            'finalizado'         => ['label' => 'Finalizado',      'cls' => 'border-emerald-200 text-emerald-700 hover:border-emerald-400'],
            'cancelado'          => ['label' => 'Cancelado',       'cls' => 'border-red-200    text-red-600    hover:border-red-400'],
        ];
        @endphp
        @foreach($chipDefs as $val => $chip)
        @php $isActive = ($activeStatus === $val); @endphp
        <a href="{{ route('app.os.index', array_merge(request()->except(['status','page']), $val === '' ? [] : ['status' => $val])) }}"
           class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-3.5 py-1.5 text-[12px] font-semibold transition-all
               {{ $isActive ? 'bg-slate-900 border-slate-900 text-white shadow-sm' : 'bg-white '.$chip['cls'] }}">
            @if($val && isset($statusMeta[$val]))
            <span class="h-1.5 w-1.5 rounded-full {{ $isActive ? 'bg-white/70' : $statusMeta[$val]['dot'] }}"></span>
            @endif
            {{ $chip['label'] }}
        </a>
        @endforeach
    </div>

</div>

{{-- ═══ LISTA DE OS ════════════════════════════════════════════════════════ --}}
@if($ordens->isEmpty())

<div class="flex flex-col items-center justify-center rounded-3xl bg-white py-24 text-center shadow-sm ring-1 ring-black/[0.06]">
    <div class="mb-5 flex h-[72px] w-[72px] items-center justify-center rounded-3xl bg-slate-100">
        <svg class="h-9 w-9 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/>
        </svg>
    </div>
    <p class="text-[16px] font-bold text-slate-700">Nenhuma OS encontrada</p>
    <p class="mt-1.5 max-w-xs text-[13px] text-slate-400 leading-relaxed">
        @if(request('busca') || request('status') || request('atrasadas'))
            Nenhum resultado para os filtros aplicados.
            <a href="{{ route('app.os.index') }}" class="font-semibold text-blue-600 hover:underline">Limpar filtros</a>
        @else
            Crie a primeira OS para começar a gerenciar os equipamentos.
        @endif
    </p>
    @if(!request('busca') && !request('status') && !request('atrasadas'))
    <a href="{{ route('app.os.create') }}"
       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5
              text-[13px] font-semibold text-white shadow-md shadow-blue-600/25 transition hover:bg-blue-700">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
        </svg>
        Criar primeira OS
    </a>
    @endif
</div>

@else

<div id="os-list" data-live-refresh="20" class="space-y-2">
    @foreach($ordens as $os)
    @php
        $sm = $statusMeta[$os->status] ?? $statusMeta['entrada'];
        $isAtrasada = $os->previsao_entrega
            && !in_array($os->status, ['finalizado','cancelado'])
            && \Carbon\Carbon::parse($os->previsao_entrega)->isPast();
        $isHoje = $os->previsao_entrega
            && \Carbon\Carbon::parse($os->previsao_entrega)->isToday();
        $isFinalizado = $os->status === 'finalizado';
        $isCancelado  = $os->status === 'cancelado';
        $total = (float)$os->valor_servico + (float)$os->valor_pecas - (float)$os->desconto;
        $delay = min($loop->index * 40, 400);

        $eqType = strtolower($os->equipamento?->tipo ?? '');
        $eqIconPath = match($eqType) {
            'notebook','laptop'    => 'M2 6h20v12H2zM1 18h22M8 22h8M12 18v4',
            'celular','smartphone' => 'M7 2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm5 16h.01',
            'tablet'               => 'M6 2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm6 16h.01',
            'impressora'           => 'M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 9V3h12v6M6 14h12v8H6z',
            'monitor'              => 'M2 3h20v14H2zM8 21h8M12 17v4',
            default                => 'M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2',
        };

        $borderClass = $isAtrasada ? 'border-l-red-400'
            : ($isFinalizado ? 'border-l-emerald-400'
            : ($isCancelado  ? 'border-l-slate-200'
            : $sm['border']));
    @endphp

    <div class="os-card group relative overflow-hidden rounded-2xl bg-white shadow-sm border-l-4
                {{ $borderClass }} {{ $isAtrasada ? 'ring-1 ring-red-200/60' : 'ring-1 ring-black/[0.06]' }}"
         style="animation-delay: {{ $delay }}ms">

        @if($isAtrasada)
        <div class="absolute right-0 top-0 flex items-center gap-1 rounded-bl-xl bg-red-500 px-2.5 py-1">
            <svg class="h-3 w-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            </svg>
            <span class="text-[10.5px] font-bold text-white badge-atrasada">Atrasada</span>
        </div>
        @endif

        <div class="flex items-center gap-4 px-5 py-4">

            {{-- Área clicável --}}
            <a href="{{ route('app.os.show', $os) }}" class="flex flex-1 min-w-0 items-center gap-4">

                {{-- Número + data --}}
                <div class="w-28 shrink-0">
                    <span class="font-mono text-[14.5px] font-black tracking-tight text-slate-900">{{ $os->numero }}</span>
                    <p class="mt-0.5 text-[11px] tabular-nums text-slate-400">{{ $os->created_at->format('d/m/Y') }}</p>
                </div>

                {{-- Cliente --}}
                <div class="flex-1 min-w-0">
                    @if($os->cliente)
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                                    bg-gradient-to-br from-blue-100 to-indigo-200
                                    text-[11.5px] font-bold text-blue-700">
                            {{ $os->cliente->iniciais }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-[13.5px] font-semibold text-slate-800">{{ $os->cliente->nome }}</p>
                            @if($os->cliente->telefone)
                            <p class="text-[11.5px] text-slate-400">{{ $os->cliente->telefone }}</p>
                            @endif
                        </div>
                    </div>
                    @else
                    <p class="text-[13px] text-slate-300">Cliente não informado</p>
                    @endif
                </div>

                {{-- Equipamento --}}
                <div class="hidden md:flex w-44 shrink-0 items-center gap-2.5">
                    @if($os->equipamento)
                    <div class="os-card-icon flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100">
                        <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="{{ $eqIconPath }}"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[12.5px] font-medium text-slate-700 truncate">
                            {{ ucfirst($os->equipamento->tipo) }}
                            @if($os->equipamento->marca)<span class="text-slate-400"> · {{ $os->equipamento->marca }}</span>@endif
                        </p>
                        @if($os->equipamento->modelo)
                        <p class="text-[11px] text-slate-400 truncate">{{ $os->equipamento->modelo }}</p>
                        @endif
                    </div>
                    @else
                    <span class="text-[12.5px] text-slate-300">—</span>
                    @endif
                </div>

                {{-- Status + Técnico --}}
                <div class="w-40 shrink-0 space-y-1.5">
                    <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-0.5 text-[11.5px] font-semibold {{ $sm['badge'] }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $sm['dot'] }} {{ in_array($os->status,['analise','execucao']) ? 'animate-pulse' : '' }}"></span>
                        {{ $sm['label'] }}
                    </span>
                    @if($os->tecnico)
                    <div class="flex items-center gap-1.5">
                        <div class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-200 text-[8.5px] font-bold text-slate-600">
                            {{ strtoupper(substr($os->tecnico->name, 0, 2)) }}
                        </div>
                        <span class="text-[11.5px] text-slate-500">{{ explode(' ', $os->tecnico->name)[0] }}</span>
                    </div>
                    @endif
                </div>

                {{-- Previsão --}}
                <div class="hidden lg:block w-32 shrink-0">
                    @if($os->previsao_entrega)
                    <p class="text-[10px] font-bold uppercase tracking-widest {{ $isAtrasada ? 'text-red-400' : ($isHoje ? 'text-amber-500' : 'text-slate-400') }}">
                        Previsão
                    </p>
                    <p class="mt-0.5 text-[12.5px] tabular-nums font-semibold {{ $isAtrasada ? 'text-red-600' : ($isHoje ? 'text-amber-600' : 'text-slate-600') }}">
                        {{ \Carbon\Carbon::parse($os->previsao_entrega)->format('d/m/Y') }}
                    </p>
                    @if($isAtrasada)
                    <p class="text-[10.5px] font-semibold text-red-500">
                        {{ abs(\Carbon\Carbon::parse($os->previsao_entrega)->diffInDays(now())) }}d de atraso
                    </p>
                    @elseif($isHoje)
                    <p class="text-[10.5px] font-semibold text-amber-500">Vence hoje</p>
                    @else
                    <p class="text-[10.5px] text-slate-400">{{ \Carbon\Carbon::parse($os->previsao_entrega)->diffForHumans() }}</p>
                    @endif
                    @else
                    <p class="text-[12px] text-slate-300">—</p>
                    @endif
                </div>

                {{-- Total --}}
                <div class="hidden sm:block w-28 shrink-0 text-right">
                    @if($total > 0)
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total</p>
                    <p class="mt-0.5 text-[14px] font-bold tabular-nums text-slate-900">
                        R$&nbsp;{{ number_format($total, 2, ',', '.') }}
                    </p>
                    @else
                    <span class="text-[12px] text-slate-300">—</span>
                    @endif
                </div>

            </a>

            {{-- ── Ações (reveal on hover) ── --}}
            <div class="shrink-0 flex items-center gap-0.5 rounded-[14px] bg-white px-1 py-1
                        shadow-md shadow-black/[0.08] ring-1 ring-black/[0.06]
                        opacity-0 translate-x-1
                        group-hover:opacity-100 group-hover:translate-x-0
                        transition-all duration-150 ease-out">
                <a href="{{ route('app.os.show', $os) }}"
                   class="flex h-7 w-7 items-center justify-center rounded-[10px] text-slate-400
                          transition hover:bg-slate-900 hover:text-white hover:shadow-sm hover:shadow-slate-500/30"
                   title="Ver OS">
                    <svg class="h-[14px] w-[14px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </a>
                @unless($os->bloqueada_para_edicao)
                <a href="{{ route('app.os.edit', $os) }}"
                   class="flex h-7 w-7 items-center justify-center rounded-[10px] text-amber-500
                          transition hover:bg-amber-500 hover:text-white hover:shadow-sm hover:shadow-amber-500/30"
                   title="Editar">
                    <svg class="h-[14px] w-[14px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </a>
                @endunless
                <span class="h-4 w-px bg-slate-100 mx-0.5"></span>
                <form method="POST" action="{{ route('app.os.destroy', $os) }}"
                      onsubmit="return confirm('Excluir a OS {{ $os->numero }}?')" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" title="Excluir"
                            class="flex h-7 w-7 items-center justify-center rounded-[10px] text-slate-300
                                   transition hover:bg-red-500 hover:text-white hover:shadow-sm hover:shadow-red-500/30">
                        <svg class="h-[14px] w-[14px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </div>
    @endforeach
</div>

{{-- Paginação --}}
@if($ordens->hasPages())
<div class="mt-5 flex items-center justify-between gap-4">
    <p class="text-[12.5px] text-slate-400">
        Exibindo <strong class="text-slate-600">{{ $ordens->firstItem() }}–{{ $ordens->lastItem() }}</strong>
        de <strong class="text-slate-600">{{ $ordens->total() }}</strong> OS
    </p>
    <div class="[&_.pagination]:flex [&_.pagination]:items-center [&_.pagination]:gap-1
                [&_a]:inline-flex [&_a]:h-8 [&_a]:min-w-[2rem] [&_a]:items-center [&_a]:justify-center [&_a]:rounded-xl [&_a]:border [&_a]:border-slate-200 [&_a]:bg-white [&_a]:px-2.5 [&_a]:text-[12.5px] [&_a]:font-semibold [&_a]:text-slate-600 [&_a]:shadow-sm [&_a]:transition [&_a]:hover:bg-slate-50 [&_a]:hover:border-slate-300
                [&_span.page-link]:inline-flex [&_span.page-link]:h-8 [&_span.page-link]:min-w-[2rem] [&_span.page-link]:items-center [&_span.page-link]:justify-center [&_span.page-link]:rounded-xl [&_span.page-link]:px-2.5 [&_span.page-link]:text-[12.5px] [&_span.page-link]:font-bold [&_span.page-link]:bg-slate-900 [&_span.page-link]:text-white [&_span.page-link]:border [&_span.page-link]:border-slate-900
                [&_.disabled]:opacity-40 [&_.disabled_a]:pointer-events-none">
        {{ $ordens->withQueryString()->links() }}
    </div>
</div>
@endif

@endif

@push('scripts')
<script>
    document.addEventListener('keydown', e => {
        if (e.key === '/' && !['INPUT','TEXTAREA','SELECT'].includes(document.activeElement.tagName)) {
            e.preventDefault();
            document.getElementById('search-input')?.focus();
        }
    });
</script>
@endpush

@endsection
