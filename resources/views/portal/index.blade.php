@extends('layouts.portal')
@section('title', 'Minhas Ordens')
@section('transparent-nav', '1')

@section('content')
@php
    $primeiroNome = explode(' ', $cliente->nome)[0];
@endphp

{{-- ───── Hero ───── --}}
<div class="relative overflow-hidden bg-[#0d1117]">
    {{-- Imagem de fundo + overlays --}}
    <div class="absolute inset-0 bg-cover bg-center"
         style="background-image:url('https://thumbs.dreamstime.com/b/processo-de-reparo-do-dispositivo-da-tabuleta-do-pc-perto-da-chave-de-fenda-e-mordido-no-fundo-de-madeira-preto-desmontado-o-vidro-82189381.jpg?w=992')"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-[#0d1117] via-[#0d1117]/90 to-[#0d1117]/55"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#0d1117] via-transparent to-transparent"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 pt-28 pb-14 sm:pt-32 sm:pb-16">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-10">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/15 px-3.5 py-1.5 text-[11px] font-semibold text-slate-300 uppercase tracking-widest mb-5">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Portal do Cliente
                </div>
                <h1 class="text-3xl sm:text-[40px] font-bold text-white leading-tight tracking-tight">
                    Olá, {{ $primeiroNome }}! 👋
                </h1>
                <p class="mt-3 max-w-md text-slate-300 text-[15px] leading-relaxed">
                    Acompanhe todas as suas ordens de serviço num só lugar,
                    com atualizações em tempo real.
                </p>
            </div>

            {{-- Stats (vidro, estilo dashboard) --}}
            <div class="flex flex-col sm:flex-row gap-4">
                {{-- Total --}}
                <div class="relative flex-1 sm:min-w-[180px]">
                    <div class="absolute -inset-1.5 rounded-[22px] bg-gradient-to-br from-slate-400/30 via-slate-400/5 to-transparent blur-xl"></div>
                    <div class="relative flex items-center gap-4 rounded-2xl bg-white/[0.07] backdrop-blur-xl border border-white/15 shadow-2xl shadow-black/50 px-5 py-5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white/10">
                            <svg class="h-5 w-5 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[28px] font-bold text-white leading-none tabular-nums">{{ $stats['total'] }}</p>
                            <p class="mt-1.5 text-[12px] text-slate-400 font-medium">Total de OS</p>
                        </div>
                    </div>
                </div>
                {{-- Abertas --}}
                <div class="relative flex-1 sm:min-w-[180px]">
                    <div class="absolute -inset-1.5 rounded-[22px] bg-gradient-to-br from-blue-500/40 via-cyan-400/10 to-transparent blur-xl"></div>
                    <div class="relative flex items-center gap-4 rounded-2xl bg-white/[0.07] backdrop-blur-xl border border-white/15 shadow-2xl shadow-black/50 px-5 py-5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-500/15">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[28px] font-bold text-blue-400 leading-none tabular-nums">{{ $stats['abertas'] }}</p>
                            <p class="mt-1.5 text-[12px] text-slate-400 font-medium">Em andamento</p>
                        </div>
                    </div>
                </div>
                {{-- Prontas --}}
                <div class="relative flex-1 sm:min-w-[180px]">
                    <div class="absolute -inset-1.5 rounded-[22px] bg-gradient-to-br from-emerald-500/40 via-emerald-400/10 to-transparent blur-xl"></div>
                    <div class="relative flex items-center gap-4 rounded-2xl bg-white/[0.07] backdrop-blur-xl border border-white/15 shadow-2xl shadow-black/50 px-5 py-5">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-500/15">
                            <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[28px] font-bold text-emerald-400 leading-none tabular-nums">{{ $stats['finalizadas'] }}</p>
                            <p class="mt-1.5 text-[12px] text-slate-400 font-medium">Prontas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ───── Lista de OS ───── --}}
<div class="mx-auto max-w-7xl px-4 sm:px-6 py-8">

    {{-- Tabs de filtro --}}
    @php
        $tabAtual = request('status', 'todas');
        $tabs = [
            ['key' => 'todas',      'label' => 'Todas',          'count' => $stats['total']],
            ['key' => 'abertas',    'label' => 'Em andamento',   'count' => $stats['abertas']],
            ['key' => 'finalizadas','label' => 'Finalizadas',    'count' => $stats['finalizadas']],
        ];
    @endphp
    <div class="flex items-center gap-1.5 mb-6 overflow-x-auto pb-1">
        @foreach($tabs as $tab)
        @php
            $isActive = $tabAtual === $tab['key'];
            $url = $tab['key'] === 'todas' ? route('portal.index') : route('portal.index', ['status' => $tab['key']]);
        @endphp
        <a href="{{ $url }}"
           class="flex items-center gap-2 rounded-xl px-4 py-2 text-[13px] font-semibold whitespace-nowrap transition-colors
                  {{ $isActive
                      ? 'bg-slate-900 text-white shadow-sm'
                      : 'bg-white border border-slate-200 text-slate-500 hover:border-slate-300 hover:text-slate-700' }}">
            {{ $tab['label'] }}
            @if($tab['count'] > 0)
            <span class="rounded-full px-2 py-0.5 text-[11px] font-bold
                         {{ $isActive ? 'bg-white/15 text-white' : 'bg-slate-100 text-slate-500' }}">
                {{ $tab['count'] }}
            </span>
            @endif
        </a>
        @endforeach
    </div>

    @if($ordens->isEmpty())
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 border border-slate-200">
                <svg class="h-8 w-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2"/>
                </svg>
            </div>
            <p class="text-[15px] font-bold text-slate-700">Nenhuma ordem de serviço</p>
            <p class="text-[13px] text-slate-500 mt-1">Quando você trazer um equipamento, ele aparecerá aqui.</p>
        </div>

    @else
        <div id="portal-os-list" data-live-refresh="20" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($ordens as $ordem)
            @php
                $statusConfig = \App\Models\Ordem::STATUS[$ordem->status] ?? ['label' => $ordem->status, 'color' => 'default'];
                $badgeClass = match($statusConfig['color']) {
                    'success' => 'bg-green-50 text-green-700 ring-1 ring-green-200',
                    'warning' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
                    'info'    => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
                    'danger'  => 'bg-red-50 text-red-700 ring-1 ring-red-200',
                    'purple'  => 'bg-purple-50 text-purple-700 ring-1 ring-purple-200',
                    'primary' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
                    default   => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
                };
                $isOpen = !in_array($ordem->status, ['finalizado', 'cancelado']);
            @endphp

            <a href="{{ route('portal.show', $ordem->id) }}"
               class="group flex flex-col bg-white rounded-2xl border border-slate-200 hover:border-slate-300 hover:shadow-xl hover:shadow-slate-900/10 hover:-translate-y-1 hover:ring-1 hover:ring-blue-100 transition-all duration-300 overflow-hidden">

                {{-- Barra de cor do status --}}
                @php
                    $barColor = match($ordem->status) {
                        'finalizado'         => 'bg-emerald-500',
                        'cancelado'          => 'bg-red-400',
                        'aguardando_cliente' => 'bg-purple-500',
                        'em_teste'           => 'bg-cyan-500',
                        default              => 'bg-blue-500',
                    };
                @endphp
                <div class="h-1 w-full {{ $barColor }} transition-all duration-300 group-hover:h-1.5"></div>

                <div class="flex flex-col flex-1 p-5">
                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-3 mb-3.5">
                        <div>
                            <p class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400 mb-0.5">Ordem de serviço</p>
                            <p class="text-[18px] font-bold text-slate-900 leading-tight font-mono">
                                {{ $ordem->codigo_publico }}
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $badgeClass }} shrink-0">
                            <span class="h-1.5 w-1.5 rounded-full bg-current {{ $isOpen ? 'animate-pulse' : '' }}"></span>
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>

                    {{-- Equipamento --}}
                    <div class="flex items-center gap-2.5 mb-3.5 min-w-0">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 shrink-0 transition-transform duration-300 group-hover:scale-110">
                            <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="2" y="3" width="20" height="14" rx="2"/>
                                <path d="M8 21h8M12 17v4"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[13px] font-semibold text-slate-800 truncate">
                                {{ $ordem->equipamento?->marca }} {{ $ordem->equipamento?->modelo ?? '—' }}
                            </p>
                            @if($ordem->equipamento?->tipo)
                            <p class="text-[11.5px] text-slate-400">{{ $ordem->equipamento->tipo }}</p>
                            @endif
                        </div>
                        @if($ordem->tecnico)
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-800 text-[11px] font-bold text-white"
                             title="Técnico responsável: {{ $ordem->tecnico->name }}">
                            {{ Str::of($ordem->tecnico->name)->explode(' ')->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('') }}
                        </div>
                        @endif
                    </div>

                    {{-- Progresso --}}
                    @if($ordem->status !== 'cancelado')
                    @php
                        $progresso = match($ordem->status) {
                            'entrada'            => 12,
                            'analise'            => 32,
                            'execucao'           => 58,
                            'aguardando_cliente' => 75,
                            'em_teste'           => 88,
                            'finalizado'         => 100,
                            default              => 5,
                        };
                    @endphp
                    <div class="mb-3.5 h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full {{ $barColor }} transition-all duration-300" style="width: {{ $progresso }}%"></div>
                    </div>
                    @endif

                    {{-- Problema relatado (preview) --}}
                    @if($ordem->problema_relatado)
                    <p class="text-[12px] text-slate-500 leading-relaxed line-clamp-2 mb-3.5 flex-1">
                        {{ $ordem->problema_relatado }}
                    </p>
                    @endif

                    {{-- Footer --}}
                    <div class="flex items-center justify-between border-t border-slate-100 pt-3.5 mt-auto">
                        <div class="flex items-center gap-1.5 text-[11.5px] text-slate-400" title="Aberta em {{ $ordem->created_at->format('d/m/Y H:i') }}">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                            </svg>
                            Atualizado {{ $ordem->updated_at->diffForHumans() }}
                        </div>
                        <span class="flex items-center gap-1 text-[12px] font-semibold text-blue-600 group-hover:gap-2 transition-all duration-150">
                            Ver detalhes
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Paginação --}}
        @if($ordens->hasPages())
        <div class="mt-6 flex items-center justify-between border-t border-slate-200 pt-5">
            <p class="text-[12.5px] text-slate-400">
                Mostrando {{ $ordens->firstItem() }}–{{ $ordens->lastItem() }} de {{ $ordens->total() }}
            </p>
            <div class="flex gap-1.5">
                @if(!$ordens->onFirstPage())
                <a href="{{ $ordens->previousPageUrl() }}"
                   class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                @endif
                @if($ordens->hasMorePages())
                <a href="{{ $ordens->nextPageUrl() }}"
                   class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                </a>
                @endif
            </div>
        </div>
        @endif
    @endif

</div>

@endsection
