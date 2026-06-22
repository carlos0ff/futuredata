@extends('layouts.portal')
@section('title', 'Minhas Ordens')
@section('transparent-nav', '1')

@section('content')
@php
    $primeiroNome = explode(' ', $cliente->nome)[0];
@endphp

{{-- ───── Hero ───── --}}
<div class="relative overflow-hidden bg-[#0d1117]">
    {{-- Gradiente decorativo (sem dependência externa) --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[#0d1117] via-[#0f1623] to-[#0d1117]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(59,130,246,0.15),transparent_60%)]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,rgba(16,185,129,0.08),transparent_60%)]"></div>
    {{-- Grid pattern decorativo --}}
    <div class="absolute inset-0 opacity-[0.03]" style="background-image:url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='1'%3E%3Cpath d='M0 0h1v40H0zM40 0h-1v40h1zM0 0v1h40V0zM0 40v-1h40v1z'/%3E%3C/g%3E%3C/svg%3E\")"></div>

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
        <div id="portal-os-list" data-live-refresh="20" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($ordens as $ordem)
            @php
                $statusConfig = \App\Models\Ordem::STATUS[$ordem->status] ?? ['label' => $ordem->status, 'color' => 'default'];
                $badgeClass = match($statusConfig['color']) {
                    'success' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
                    'warning' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
                    'info'    => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
                    'danger'  => 'bg-red-50 text-red-700 ring-1 ring-red-200',
                    'purple'  => 'bg-purple-50 text-purple-700 ring-1 ring-purple-200',
                    'primary' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
                    default   => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
                };
                $isOpen = !in_array($ordem->status, ['finalizado', 'cancelado']);

                $barColor = match($ordem->status) {
                    'finalizado'         => 'from-emerald-400 to-emerald-500',
                    'cancelado'          => 'from-red-400 to-red-500',
                    'aguardando_cliente' => 'from-purple-400 to-purple-500',
                    'em_teste'           => 'from-cyan-400 to-cyan-500',
                    'analise'            => 'from-amber-400 to-amber-500',
                    default              => 'from-blue-400 to-blue-500',
                };

                $progresso = match($ordem->status) {
                    'entrada'            => 12,
                    'analise'            => 32,
                    'execucao'           => 58,
                    'aguardando_cliente' => 75,
                    'em_teste'           => 88,
                    'finalizado'         => 100,
                    default              => 5,
                };

                // Ícone por tipo de equipamento
                $tipo = strtolower($ordem->equipamento?->tipo ?? '');
                $equipIcon = match(true) {
                    str_contains($tipo, 'celular') || str_contains($tipo, 'smartphone') || str_contains($tipo, 'iphone') => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3"/>',
                    str_contains($tipo, 'notebook') || str_contains($tipo, 'laptop') || str_contains($tipo, 'macbook') => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25A2.25 2.25 0 0 1 5.25 3h13.5A2.25 2.25 0 0 1 21 5.25z"/>',
                    str_contains($tipo, 'tablet') || str_contains($tipo, 'ipad') => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5h3m-6.75 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-15a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 4.5v15a2.25 2.25 0 0 0 2.25 2.25z"/>',
                    str_contains($tipo, 'desktop') || str_contains($tipo, 'computador') || str_contains($tipo, 'pc') => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 7.409A2.25 2.25 0 0 1 2.25 5.493V5.25"/>',
                    str_contains($tipo, 'relógio') || str_contains($tipo, 'smartwatch') || str_contains($tipo, 'watch') => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.75h4.5v2.25h-4.5V3.75zm0 14.25h4.5v2.25h-4.5V18z"/>',
                    str_contains($tipo, 'impressora') => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5z"/>',
                    default => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
                };

                $iconBg = match($statusConfig['color']) {
                    'success' => 'bg-emerald-50 text-emerald-500',
                    'warning' => 'bg-amber-50 text-amber-500',
                    'purple'  => 'bg-purple-50 text-purple-500',
                    'danger'  => 'bg-red-50 text-red-500',
                    default   => 'bg-blue-50 text-blue-500',
                };
            @endphp

            <a href="{{ route('portal.show', $ordem->id) }}"
               class="group flex flex-col bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-lg hover:shadow-slate-900/8 hover:-translate-y-0.5 hover:border-slate-300 transition-all duration-200 overflow-hidden">

                {{-- Topo colorido com gradiente --}}
                <div class="h-1.5 w-full bg-gradient-to-r {{ $barColor }}"></div>

                <div class="flex flex-col flex-1 p-5">

                    {{-- Header: número OS + badge --}}
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-slate-400 mb-0.5">Ordem de serviço</p>
                            <p class="text-[17px] font-black text-slate-900 leading-none font-mono tracking-tight">
                                {{ $ordem->codigo_publico }}
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $badgeClass }} shrink-0 mt-0.5">
                            @if($isOpen)
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-current opacity-60"></span>
                                <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-current"></span>
                            </span>
                            @else
                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                            @endif
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>

                    {{-- Equipamento --}}
                    <div class="flex items-center gap-3 mb-4 min-w-0">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $iconBg }} shrink-0 transition-transform duration-200 group-hover:scale-105">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">{!! $equipIcon !!}</svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[13.5px] font-semibold text-slate-800 truncate leading-tight">
                                {{ $ordem->equipamento?->marca }} {{ $ordem->equipamento?->modelo ?? '—' }}
                            </p>
                            <p class="text-[11.5px] text-slate-400 mt-0.5">{{ $ordem->equipamento?->tipo ?? 'Equipamento' }}</p>
                        </div>
                        @if($ordem->tecnico)
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-slate-700 to-slate-900 text-[11px] font-bold text-white shadow-sm"
                             title="Técnico: {{ $ordem->tecnico->name }}">
                            {{ Str::of($ordem->tecnico->name)->explode(' ')->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('') }}
                        </div>
                        @endif
                    </div>

                    {{-- Barra de progresso --}}
                    @if($ordem->status !== 'cancelado')
                    <div class="mb-3">
                        <div class="flex items-center justify-between mb-1.5">
                            <p class="text-[10.5px] font-medium text-slate-400 uppercase tracking-wide">Progresso</p>
                            <p class="text-[10.5px] font-bold text-slate-500">{{ $progresso }}%</p>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $barColor }} transition-all duration-500" style="width: {{ $progresso }}%"></div>
                        </div>
                    </div>
                    @endif

                    {{-- Problema relatado --}}
                    @if($ordem->problema_relatado)
                    <p class="text-[12px] text-slate-500 leading-relaxed line-clamp-2 mb-3 flex-1 italic">
                        "{{ $ordem->problema_relatado }}"
                    </p>
                    @else
                    <div class="flex-1"></div>
                    @endif

                    {{-- Footer --}}
                    <div class="flex items-center justify-between border-t border-slate-100 pt-3.5 mt-auto">
                        <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                            </svg>
                            {{ $ordem->updated_at->diffForHumans() }}
                        </div>
                        <span class="flex items-center gap-1 text-[12px] font-bold text-blue-600 group-hover:gap-2 transition-all duration-150">
                            Ver detalhes
                            <svg class="h-3.5 w-3.5 transition-transform duration-150 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
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
