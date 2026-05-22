@extends('layouts.app')
@section('title', 'OS ' . $ordem->numero)

@section('breadcrumbs')
@include('layouts.partials.breadcrumbs', ['items' => [
    ['label' => 'Ordens de Serviço', 'href' => route('app.os.index')],
    ['label' => $ordem->numero],
]])
@endsection

@section('content')

@php
$diasAberta = $ordem->created_at->diffInDays(now());
$diasStr = match(true) {
    $diasAberta === 0 => 'Hoje',
    $diasAberta === 1 => '1 dia',
    default           => "{$diasAberta} dias",
};
$isAtrasada = $ordem->previsao_entrega
    && !in_array($ordem->status, ['finalizado','cancelado'])
    && $ordem->previsao_entrega->isPast();

$statusCores = [
    'entrada'            => ['dot'=>'bg-slate-400',   'badge'=>'bg-slate-100/15 text-slate-200',   'ring'=>'ring-slate-500/20'],
    'analise'            => ['dot'=>'bg-amber-400',   'badge'=>'bg-amber-100/15 text-amber-200',   'ring'=>'ring-amber-500/20'],
    'execucao'           => ['dot'=>'bg-blue-400',    'badge'=>'bg-blue-100/15 text-blue-200',     'ring'=>'ring-blue-500/20'],
    'aguardando_cliente' => ['dot'=>'bg-violet-400',  'badge'=>'bg-violet-100/15 text-violet-200', 'ring'=>'ring-violet-500/20'],
    'em_teste'           => ['dot'=>'bg-cyan-400',    'badge'=>'bg-cyan-100/15 text-cyan-200',     'ring'=>'ring-cyan-500/20'],
    'finalizado'         => ['dot'=>'bg-emerald-400', 'badge'=>'bg-emerald-100/15 text-emerald-200','ring'=>'ring-emerald-500/20'],
    'cancelado'          => ['dot'=>'bg-red-400',     'badge'=>'bg-red-100/15 text-red-200',       'ring'=>'ring-red-500/20'],
];
$sc = $statusCores[$ordem->status] ?? $statusCores['entrada'];

// Pipeline de status (excluindo cancelado)
$pipeline = ['entrada','analise','execucao','em_teste','finalizado'];
$pipelineLabels = ['Entrada','Análise','Execução','Em teste','Finalizado'];
$currentPipelineIdx = array_search($ordem->status, $pipeline);
@endphp

{{-- ═══ FLASH: entrada registrada ══════════════════════════════════════════ --}}
@if(session('entrada_sucesso'))
@php $es = session('entrada_sucesso'); @endphp
<div class="mb-5 overflow-hidden rounded-2xl border border-emerald-200 bg-emerald-50" x-data="{ show: true }" x-show="show" x-transition.opacity>
    <div class="flex items-start gap-4 px-5 py-4">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 shadow-sm shadow-emerald-600/25">
            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-[14px] font-bold text-emerald-900">Equipamento registrado com sucesso!</p>
            <p class="mt-0.5 text-[12.5px] text-emerald-700">OS <span class="font-mono font-bold">{{ $es['os'] }}</span> aberta · Status: Equipamento recebido.</p>
            <div class="mt-3.5 grid gap-2.5 sm:grid-cols-3">
                @if($es['wa_link'])
                <a href="{{ $es['wa_link'] }}" target="_blank"
                   class="flex items-center gap-2.5 rounded-xl border border-[#25d366]/30 bg-white px-3 py-2.5 transition hover:bg-[#25d366]/5">
                    <svg class="h-6 w-6 shrink-0 text-[#25d366]" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    <div class="min-w-0"><p class="text-[12px] font-bold text-slate-800">WhatsApp</p><p class="text-[10.5px] text-slate-500">{{ $es['telefone'] ?? '' }}</p></div>
                </a>
                @endif
                <a href="{{ $es['portal'] }}" target="_blank"
                   class="flex items-center gap-2.5 rounded-xl border border-blue-200 bg-white px-3 py-2.5 transition hover:bg-blue-50">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-blue-100"><svg class="h-3.5 w-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></div>
                    <div class="min-w-0"><p class="text-[12px] font-bold text-slate-800">Portal</p><p class="font-mono text-[10.5px] text-slate-500">{{ $es['token'] ?? '' }}</p></div>
                </a>
                <a href="{{ route('app.os.print', $ordem) }}" target="_blank"
                   class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-white px-3 py-2.5 transition hover:bg-slate-50">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-slate-100"><svg class="h-3.5 w-3.5 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg></div>
                    <div><p class="text-[12px] font-bold text-slate-800">Comprovante</p><p class="text-[10.5px] text-slate-500">Imprimir</p></div>
                </a>
            </div>
        </div>
        <button @click="show=false" class="shrink-0 text-emerald-400 hover:text-emerald-600 transition">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </div>
</div>
@endif

@if(session('success'))
<div class="mb-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-[13px] font-medium text-emerald-800">
    <svg class="h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- ═══ HERO CARD ══════════════════════════════════════════════════════════ --}}
<div class="relative mb-5 overflow-hidden rounded-2xl bg-[#0d0f16] px-6 py-5">
    {{-- glow --}}
    <div class="pointer-events-none absolute -right-20 -top-20 h-72 w-72 rounded-full bg-indigo-600/[0.08] blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-12 -left-10 h-48 w-48 rounded-full bg-blue-500/[0.06] blur-2xl"></div>

    {{-- Top row: info + actions --}}
    <div class="relative flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="flex items-start gap-3">
            <a href="{{ route('app.os.index') }}"
               class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-white/10 text-slate-400 transition hover:border-white/20 hover:text-white">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m12 19-7-7 7-7M19 12H5"/></svg>
            </a>
            <div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="font-mono text-[22px] font-black tracking-tight text-white">{{ $ordem->numero }}</span>

                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11.5px] font-semibold ring-1 {{ $sc['badge'] }} {{ $sc['ring'] }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $sc['dot'] }} {{ in_array($ordem->status, ['execucao','analise']) ? 'animate-pulse' : '' }}"></span>
                        {{ $status[$ordem->status]['label'] ?? $ordem->status }}
                    </span>

                    @if($isAtrasada)
                    <span class="inline-flex items-center gap-1 rounded-full bg-red-500/20 px-2.5 py-1 text-[11px] font-semibold text-red-300 ring-1 ring-red-500/30">
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                        Atrasada
                    </span>
                    @endif
                </div>
                <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-[13px]">
                    @if($ordem->cliente)
                    <span class="flex items-center gap-1.5 text-slate-300">
                        <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        {{ $ordem->cliente->nome }}
                    </span>
                    @endif
                    @if($ordem->equipamento)
                    <span class="flex items-center gap-1.5 text-slate-400">
                        <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                        {{ $ordem->equipamento->tipo }}@if($ordem->equipamento->marca) · {{ $ordem->equipamento->marca }}@endif
                    </span>
                    @endif
                    @if($ordem->tecnico)
                    <span class="flex items-center gap-1.5 text-slate-400">
                        <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                        {{ explode(' ', $ordem->tecnico->name)[0] }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 sm:flex-nowrap">
            <a href="{{ route('app.os.print', $ordem) }}" target="_blank"
               class="flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/[0.06] px-3 py-2 text-[12.5px] font-semibold text-slate-300 transition hover:border-white/20 hover:bg-white/[0.1] hover:text-white">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
                Imprimir
            </a>
            <a href="{{ route('app.os.edit', $ordem) }}"
               class="flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/[0.06] px-3 py-2 text-[12.5px] font-semibold text-slate-300 transition hover:border-white/20 hover:bg-white/[0.1] hover:text-white">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editar
            </a>
            @if($ordem->token)
            <a href="{{ route('portal.token', $ordem->token) }}" target="_blank"
               class="flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-[12.5px] font-semibold text-white shadow-md shadow-blue-900/40 transition hover:bg-blue-500">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Portal
            </a>
            @endif
        </div>
    </div>

    {{-- Status pipeline --}}
    @if($ordem->status !== 'cancelado')
    <div class="relative mt-5 pt-4">
        <div class="absolute inset-x-0 top-[1.35rem] h-px bg-white/[0.07]"></div>
        <div class="relative flex items-start justify-between">
            @foreach($pipeline as $pi => $pStep)
            @php
            $isDone    = $currentPipelineIdx !== false && $pi < $currentPipelineIdx;
            $isCurrent = $pi === $currentPipelineIdx;
            @endphp
            <div class="flex flex-1 flex-col items-center gap-1.5 px-0.5">
                <div class="relative z-10 flex h-6 w-6 items-center justify-center rounded-full transition
                    {{ $isCurrent ? 'bg-white shadow-sm shadow-white/20 ring-4 ring-white/10' : ($isDone ? 'bg-white/20' : 'bg-white/[0.06]') }}">
                    @if($isDone)
                    <svg class="h-3 w-3 text-white/70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                    @elseif($isCurrent)
                    <span class="h-2 w-2 rounded-full bg-slate-900 {{ in_array($ordem->status, ['execucao','analise']) ? 'animate-pulse' : '' }}"></span>
                    @else
                    <span class="h-1.5 w-1.5 rounded-full bg-white/20"></span>
                    @endif
                </div>
                <span class="text-[10px] font-semibold
                    {{ $isCurrent ? 'text-white' : ($isDone ? 'text-white/40' : 'text-white/20') }}">
                    {{ $pipelineLabels[$pi] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Métricas rápidas --}}
    <div class="relative mt-4 grid grid-cols-2 gap-3 border-t border-white/[0.06] pt-4 sm:grid-cols-4">
        <div>
            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Entrada</p>
            <p class="mt-1 text-[13px] font-semibold text-slate-300">{{ $ordem->created_at->format('d/m/Y') }}</p>
            <p class="text-[11px] text-slate-500">{{ $diasStr }} atrás</p>
        </div>
        @if($ordem->previsao_entrega)
        <div>
            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Previsão</p>
            <p class="mt-1 text-[13px] font-semibold {{ $isAtrasada ? 'text-red-400' : 'text-slate-300' }}">
                {{ $ordem->previsao_entrega->format('d/m/Y') }}
            </p>
            <p class="text-[11px] {{ $isAtrasada ? 'text-red-500' : 'text-slate-500' }}">
                {{ $isAtrasada ? 'Vencida' : $ordem->previsao_entrega->diffForHumans() }}
            </p>
        </div>
        @endif
        @if($ordem->total > 0)
        <div>
            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Total</p>
            <p class="mt-1 text-[13px] font-semibold tabular-nums text-slate-300">R$ {{ number_format($ordem->total, 2, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500">Orçamento</p>
        </div>
        @endif
        <div>
            <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Arquivos</p>
            <p class="mt-1 text-[13px] font-semibold text-slate-300">{{ $ordem->arquivos->count() ?: '—' }}</p>
            <p class="text-[11px] text-slate-500">{{ $ordem->arquivos->count() === 1 ? 'arquivo' : 'anexos' }}</p>
        </div>
    </div>
</div>

{{-- ═══ TABS ════════════════════════════════════════════════════════════════ --}}
<div x-data="{ tab: window.location.hash === '#orcamento' ? 'orcamento' : (window.location.hash === '#arquivos' ? 'arquivos' : 'detalhes') }">

{{-- Tab bar --}}
<div class="mb-5 flex items-center gap-1 rounded-2xl bg-white p-1 shadow-sm ring-1 ring-black/[0.06] w-fit">
    <button @click="tab='detalhes'; history.pushState(null,'','#detalhes')"
            :class="tab==='detalhes' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'"
            class="flex items-center gap-2 rounded-xl px-4 py-2 text-[13px] font-semibold transition-all duration-150">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>
        Detalhes
    </button>
    <button @click="tab='orcamento'; history.pushState(null,'','#orcamento')"
            :class="tab==='orcamento' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'"
            class="flex items-center gap-2 rounded-xl px-4 py-2 text-[13px] font-semibold transition-all duration-150">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        Orçamento
        @if($ordem->status_orcamento === 'pendente' || (!$ordem->status_orcamento && $ordem->total > 0))
        <span class="h-1.5 w-1.5 rounded-full bg-amber-400" :class="tab==='orcamento' ? 'bg-amber-300' : 'bg-amber-400'"></span>
        @endif
    </button>
    <button @click="tab='arquivos'; history.pushState(null,'','#arquivos')"
            :class="tab==='arquivos' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'"
            class="flex items-center gap-2 rounded-xl px-4 py-2 text-[13px] font-semibold transition-all duration-150">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15.5 2H8.6c-.4 0-.8.2-1.1.5-.3.3-.5.7-.5 1.1v12.8c0 .4.2.8.5 1.1.3.3.7.5 1.1.5h9.8c.4 0 .8-.2 1.1-.5.3-.3.5-.7.5-1.1V6.5L15.5 2z"/><polyline points="15 2 15 7 20 7"/></svg>
        Arquivos
        @if($ordem->arquivos->count() > 0)
        <span class="flex h-[18px] min-w-[18px] items-center justify-center rounded-full px-1 text-[10px] font-bold tabular-nums"
              :class="tab==='arquivos' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">
            {{ $ordem->arquivos->count() }}
        </span>
        @endif
    </button>
</div>

{{-- ══════════════════════ TAB: DETALHES ═══════════════════════════════════ --}}
<div x-show="tab==='detalhes'" x-cloak>
<div class="grid gap-5 xl:grid-cols-[1fr_300px]">

{{-- Coluna esquerda --}}
<div class="space-y-4">

    {{-- CLIENTE --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
            <h2 class="flex items-center gap-2 text-[13.5px] font-bold text-slate-900">
                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-50"><svg class="h-3.5 w-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                Cliente
            </h2>
            @if($ordem->cliente)
            <a href="{{ route('app.clientes.show', $ordem->cliente) }}"
               class="flex items-center gap-1 text-[12px] font-semibold text-blue-600 transition hover:text-blue-700">
                Ver perfil <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
            </a>
            @endif
        </div>
        @if($ordem->cliente)
        <div class="p-5">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-indigo-200 text-[13px] font-bold text-blue-700">
                    {{ $ordem->cliente->iniciais }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[15px] font-bold text-slate-900">{{ $ordem->cliente->nome }}</p>
                    @if($ordem->cliente->cpf_cnpj)
                    <p class="font-mono text-[12px] text-slate-400">{{ $ordem->cliente->cpf_cnpj }}</p>
                    @endif
                </div>
                @if($ordem->cliente->telefone)
                @php $waLink = 'https://wa.me/55'.preg_replace('/\D/','',$ordem->cliente->telefone); @endphp
                <a href="{{ $waLink }}" target="_blank"
                   class="flex items-center gap-1.5 rounded-lg border border-[#25d366]/30 bg-[#25d366]/8 px-3 py-1.5 text-[12px] font-semibold text-[#128c4e] transition hover:bg-[#25d366]/15 shrink-0">
                    <svg class="h-3.5 w-3.5 text-[#25d366]" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    {{ $ordem->cliente->telefone }}
                </a>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 text-[13px]">
                @if($ordem->cliente->email)
                <div class="col-span-2 rounded-xl bg-slate-50 px-3.5 py-2.5">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">E-mail</p>
                    <p class="mt-0.5 text-slate-700 truncate">{{ $ordem->cliente->email }}</p>
                </div>
                @endif
                @if($ordem->cliente->cidade)
                <div class="rounded-xl bg-slate-50 px-3.5 py-2.5">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Cidade</p>
                    <p class="mt-0.5 text-slate-700">{{ $ordem->cliente->cidade }}{{ $ordem->cliente->estado ? '/'.$ordem->cliente->estado : '' }}</p>
                </div>
                @endif
                @if($ordem->cliente->data_nascimento)
                <div class="rounded-xl bg-slate-50 px-3.5 py-2.5">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Nascimento</p>
                    <p class="mt-0.5 tabular-nums text-slate-700">{{ $ordem->cliente->data_nascimento->format('d/m/Y') }}</p>
                </div>
                @endif
                @if($ordem->cliente->endereco)
                <div class="col-span-2 rounded-xl bg-slate-50 px-3.5 py-2.5">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Endereço</p>
                    <p class="mt-0.5 text-slate-700">{{ $ordem->cliente->endereco }}{{ $ordem->cliente->numero ? ', '.$ordem->cliente->numero : '' }}{{ $ordem->cliente->bairro ? ' — '.$ordem->cliente->bairro : '' }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- EQUIPAMENTO --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
            <h2 class="flex items-center gap-2 text-[13.5px] font-bold text-slate-900">
                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100"><svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span>
                Equipamento
            </h2>
            @if($ordem->equipamento?->em_garantia)
            <span class="flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Em garantia
            </span>
            @endif
        </div>
        @if($ordem->equipamento)
        <div class="p-5">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-900">
                    <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                </div>
                <div>
                    <p class="text-[15px] font-bold text-slate-900">
                        {{ $ordem->equipamento->tipo }}@if($ordem->equipamento->marca) · {{ $ordem->equipamento->marca }}@endif
                    </p>
                    @if($ordem->equipamento->modelo)
                    <p class="text-[13px] text-slate-500">{{ $ordem->equipamento->modelo }}</p>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 text-[13px]">
                @foreach([
                    'Nº Série'   => $ordem->equipamento->numero_serie,
                    'Estado'     => $ordem->equipamento->estado_fisico ?? $ordem->equipamento->condicao_entrada,
                    'Acessórios' => $ordem->equipamento->acessorios,
                    'Entrada'    => $ordem->equipamento->forma_entrada ? ucfirst($ordem->equipamento->forma_entrada) : null,
                ] as $lbl => $val)
                @if($val)
                <div class="rounded-xl bg-slate-50 px-3.5 py-2.5">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $lbl }}</p>
                    <p class="mt-0.5 font-medium text-slate-700 {{ $lbl === 'Nº Série' ? 'font-mono tracking-wider' : '' }}">{{ $val }}</p>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- PROBLEMA E DIAGNÓSTICO --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
            <h2 class="flex items-center gap-2 text-[13.5px] font-bold text-slate-900">
                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-red-50"><svg class="h-3.5 w-3.5 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg></span>
                Problema e Diagnóstico
            </h2>
        </div>
        <div class="space-y-3 p-5">
            <div class="rounded-xl border-l-4 border-red-400 bg-red-50/50 px-4 py-3.5">
                <p class="mb-1.5 text-[10px] font-black uppercase tracking-widest text-red-500">Relatado pelo cliente</p>
                <p class="text-[13px] leading-relaxed text-slate-800 whitespace-pre-line">{{ $ordem->problema_relatado ?: '—' }}</p>
            </div>
            <div class="rounded-xl border-l-4 border-blue-400 bg-blue-50/50 px-4 py-3.5">
                <p class="mb-1.5 text-[10px] font-black uppercase tracking-widest text-blue-500">Diagnóstico técnico</p>
                <p class="text-[13px] leading-relaxed {{ $ordem->diagnostico ? 'text-slate-800' : 'italic text-slate-400' }} whitespace-pre-line">
                    {{ $ordem->diagnostico ?: 'Aguardando diagnóstico.' }}
                </p>
            </div>
            @if($ordem->solucao)
            <div class="rounded-xl border-l-4 border-emerald-400 bg-emerald-50/50 px-4 py-3.5">
                <p class="mb-1.5 text-[10px] font-black uppercase tracking-widest text-emerald-600">Solução aplicada</p>
                <p class="text-[13px] leading-relaxed text-slate-800 whitespace-pre-line">{{ $ordem->solucao }}</p>
            </div>
            @endif
            @if($ordem->observacoes)
            <div class="rounded-xl bg-slate-50 px-4 py-3.5">
                <p class="mb-1.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Observações internas</p>
                <p class="text-[13px] leading-relaxed text-slate-600 whitespace-pre-line">{{ $ordem->observacoes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- HISTÓRICO --}}
    @if($ordem->historico->isNotEmpty())
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
        <div class="border-b border-slate-100 px-5 py-3.5">
            <h2 class="flex items-center gap-2 text-[13.5px] font-bold text-slate-900">
                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100"><svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
                Histórico
            </h2>
        </div>
        <div class="p-5">
            <ol class="relative space-y-3 border-l-2 border-slate-100 pl-5">
                @foreach($ordem->historico as $h)
                @php
                $hColor = match($h->status_novo) {
                    'finalizado'         => 'bg-emerald-500 ring-emerald-100',
                    'cancelado'          => 'bg-red-500 ring-red-100',
                    'em_teste'           => 'bg-cyan-500 ring-cyan-100',
                    'execucao'           => 'bg-blue-500 ring-blue-100',
                    'aguardando_cliente' => 'bg-violet-500 ring-violet-100',
                    'analise'            => 'bg-amber-500 ring-amber-100',
                    default              => 'bg-slate-400 ring-slate-100',
                };
                @endphp
                <li class="relative">
                    <span class="absolute -left-[22px] top-2 h-3 w-3 rounded-full ring-4 {{ $hColor }}"></span>
                    <div class="rounded-xl bg-slate-50 p-3.5">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="text-[13px] font-bold text-slate-900">{{ $status[$h->status_novo]['label'] ?? $h->status_novo }}</p>
                            <time class="text-[11px] tabular-nums text-slate-400">{{ $h->created_at->format('d/m/Y \à\s H:i') }}</time>
                        </div>
                        @if($h->observacao)
                        <p class="mt-1.5 text-[12.5px] leading-relaxed text-slate-500">{{ $h->observacao }}</p>
                        @endif
                        @if($h->usuario)
                        <p class="mt-1 text-[11px] text-slate-400">por {{ $h->usuario->name }}</p>
                        @endif
                    </div>
                </li>
                @endforeach
            </ol>
        </div>
    </div>
    @endif

</div>{{-- /esquerda --}}

{{-- Coluna direita --}}
<div class="space-y-4">

    {{-- ATUALIZAR STATUS --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
        <div class="border-b border-slate-100 px-5 py-3.5">
            <h2 class="text-[13.5px] font-bold text-slate-900">Atualizar Status</h2>
        </div>
        <form action="{{ route('app.os.status.update', $ordem) }}" method="POST" class="p-4 space-y-3">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-2">
                @foreach($status as $key => $s)
                @php
                $sBorder = match($key) {
                    'finalizado' => 'has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:text-emerald-800',
                    'cancelado'  => 'has-[:checked]:border-red-400 has-[:checked]:bg-red-50 has-[:checked]:text-red-700',
                    'execucao'   => 'has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-800',
                    'analise'    => 'has-[:checked]:border-amber-400 has-[:checked]:bg-amber-50 has-[:checked]:text-amber-800',
                    'em_teste'   => 'has-[:checked]:border-cyan-500 has-[:checked]:bg-cyan-50 has-[:checked]:text-cyan-800',
                    'aguardando_cliente' => 'has-[:checked]:border-violet-500 has-[:checked]:bg-violet-50 has-[:checked]:text-violet-800',
                    default      => 'has-[:checked]:border-slate-700 has-[:checked]:bg-slate-900 has-[:checked]:text-white',
                };
                $dotColor = match($key) {
                    'finalizado'         => 'bg-emerald-500',
                    'cancelado'          => 'bg-red-400',
                    'execucao'           => 'bg-blue-500',
                    'analise'            => 'bg-amber-400',
                    'em_teste'           => 'bg-cyan-500',
                    'aguardando_cliente' => 'bg-violet-500',
                    default              => 'bg-slate-400',
                };
                @endphp
                <label class="flex cursor-pointer items-center gap-2 rounded-xl border-2 border-slate-200 bg-slate-50/50 px-3 py-2.5 text-slate-500 transition hover:border-slate-300 {{ $sBorder }}">
                    <input type="radio" name="status" value="{{ $key }}" class="sr-only" @checked($ordem->status === $key)>
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $dotColor }}"></span>
                    <span class="text-[12px] font-semibold leading-tight">{{ $s['label'] }}</span>
                </label>
                @endforeach
            </div>
            <textarea name="observacao" rows="2"
                      placeholder="Observação sobre a mudança…"
                      class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15"></textarea>
            <button type="submit" class="w-full rounded-xl bg-slate-900 py-2.5 text-[13px] font-bold text-white transition hover:bg-slate-800 active:scale-[0.99]">
                Salvar alteração
            </button>
        </form>
    </div>

    {{-- RESUMO FINANCEIRO --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
            <h2 class="text-[13.5px] font-bold text-slate-900">Financeiro</h2>
            <button @click="tab='orcamento'; history.pushState(null,'','#orcamento')"
                    class="text-[12px] font-semibold text-blue-600 hover:text-blue-700 transition">
                Editar
            </button>
        </div>
        <div class="p-4 space-y-2 text-[13px]">
            <div class="flex justify-between items-center rounded-xl bg-slate-50 px-3.5 py-2.5">
                <span class="text-slate-500">Valor cobrado</span>
                <span class="tabular-nums font-semibold text-slate-800">R$ {{ number_format($ordem->valor_servico, 2, ',', '.') }}</span>
            </div>
            @if($ordem->desconto > 0)
            <div class="flex justify-between items-center rounded-xl bg-red-50 px-3.5 py-2.5">
                <span class="text-red-500">Desconto</span>
                <span class="tabular-nums font-semibold text-red-600">− R$ {{ number_format($ordem->desconto, 2, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex items-center justify-between rounded-2xl bg-slate-900 px-4 py-3.5">
                <span class="text-[13px] font-bold text-white">Total</span>
                <span class="text-[18px] font-black tabular-nums text-white">R$ {{ number_format($ordem->valor_servico - $ordem->desconto, 2, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- PORTAL DO CLIENTE --}}
    @if($ordem->token)
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]" x-data="{ copied: false }">
        <div class="border-b border-slate-100 px-5 py-3.5">
            <h2 class="text-[13.5px] font-bold text-slate-900">Portal do Cliente</h2>
        </div>
        <div class="space-y-3 p-4">
            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Token</p>
                    <span class="font-mono text-[15px] font-black tracking-[0.15em] text-slate-900">{{ $ordem->token }}</span>
                </div>
                <button @click="navigator.clipboard.writeText('{{ $ordem->token }}'); copied=true; setTimeout(()=>copied=false,2000)"
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600">
                    <svg x-show="!copied" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    <svg x-show="copied" class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none"><path d="M20 6 9 17l-5-5"/></svg>
                </button>
            </div>
            <a href="{{ route('portal.token', $ordem->token) }}" target="_blank"
               class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-2.5 text-[13px] font-bold text-white transition hover:bg-blue-700">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Abrir portal
            </a>
            @if($ordem->cliente?->telefone)
            @php $wa = 'https://wa.me/55'.preg_replace('/\D/','',$ordem->cliente->telefone).'?text='.urlencode('Olá! Acesse o portal para acompanhar sua OS: '.route('portal.token', $ordem->token)); @endphp
            <a href="{{ $wa }}" target="_blank"
               class="flex w-full items-center justify-center gap-2 rounded-xl border border-[#25d366]/30 bg-[#25d366]/8 py-2.5 text-[13px] font-semibold text-[#128c4e] transition hover:bg-[#25d366]/15">
                <svg class="h-4 w-4 text-[#25d366]" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                Enviar via WhatsApp
            </a>
            @endif
        </div>
    </div>
    @endif

</div>{{-- /direita --}}
</div>{{-- /grid --}}
</div>{{-- /tab detalhes --}}

{{-- ══════════════════════ TAB: ORÇAMENTO ══════════════════════════════════ --}}
<div x-show="tab==='orcamento'" x-cloak>
<div class="grid gap-5 xl:grid-cols-[1fr_300px]">

{{-- Esquerda: valores + status --}}
<div class="space-y-4">

    {{-- Banner status orçamento --}}
    @php
    $orcCfg = match($ordem->status_orcamento ?? 'pendente') {
        'aprovado' => ['bg'=>'bg-emerald-50','border'=>'border-emerald-200','text'=>'text-emerald-900','sub'=>'text-emerald-600','iconBg'=>'bg-emerald-100','icon'=>'text-emerald-600','label'=>'Orçamento aprovado','desc'=>'Cliente autorizou a execução do serviço.'],
        'recusado' => ['bg'=>'bg-red-50','border'=>'border-red-200','text'=>'text-red-900','sub'=>'text-red-600','iconBg'=>'bg-red-100','icon'=>'text-red-500','label'=>'Orçamento recusado','desc'=>'Cliente não autorizou. Entre em contato para alinhar.'],
        default    => ['bg'=>'bg-amber-50','border'=>'border-amber-200','text'=>'text-amber-900','sub'=>'text-amber-700','iconBg'=>'bg-amber-100','icon'=>'text-amber-600','label'=>'Aguardando aprovação','desc'=>'Envie o orçamento ao cliente pelo portal.'],
    };
    @endphp
    <div class="flex items-start gap-4 rounded-2xl border {{ $orcCfg['border'] }} {{ $orcCfg['bg'] }} px-5 py-4">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $orcCfg['iconBg'] }}">
            @if(($ordem->status_orcamento ?? '') === 'aprovado')
            <svg class="h-5 w-5 {{ $orcCfg['icon'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
            @elseif($ordem->status_orcamento === 'recusado')
            <svg class="h-5 w-5 {{ $orcCfg['icon'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 18 18 6M6 6l12 12"/></svg>
            @else
            <svg class="h-5 w-5 {{ $orcCfg['icon'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            @endif
        </div>
        <div>
            <p class="text-[15px] font-bold {{ $orcCfg['text'] }}">{{ $orcCfg['label'] }}</p>
            <p class="mt-0.5 text-[12.5px] {{ $orcCfg['sub'] }}">{{ $orcCfg['desc'] }}</p>
        </div>
    </div>

    {{-- Formulário de valores --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]"
         x-data="{
             servico: {{ (float) $ordem->valor_servico }},
             desconto:{{ (float) $ordem->desconto }},
             get total() { return Math.max(0, this.servico - this.desconto); },
             fmt(v) { return v.toFixed(2).replace('.',',').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
         }">
        <div class="border-b border-slate-100 px-5 py-3.5">
            <h2 class="text-[13.5px] font-bold text-slate-900">Valores do Orçamento</h2>
        </div>
        <form action="{{ route('app.os.update', $ordem) }}" method="POST" class="p-5 space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="{{ $ordem->status }}">
            <input type="hidden" name="problema_relatado" value="{{ $ordem->problema_relatado }}">
            <input type="hidden" name="status_orcamento" value="{{ $ordem->status_orcamento ?? 'pendente' }}">
            <input type="hidden" name="valor_pecas" value="0">

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-[12px] font-bold text-slate-600">Valor cobrado ao cliente</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[13px] font-semibold text-slate-400">R$</span>
                        <input type="number" name="valor_servico" step="0.01" min="0"
                               value="{{ $ordem->valor_servico }}" x-model.number="servico"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-3 text-[13px] tabular-nums font-semibold outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/15">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-[12px] font-bold text-slate-600">Desconto <span class="text-[11px] font-normal text-slate-400">(opcional)</span></label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[13px] font-semibold text-slate-400">R$</span>
                        <input type="number" name="desconto" step="0.01" min="0"
                               value="{{ $ordem->desconto }}" x-model.number="desconto"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-3 text-[13px] tabular-nums font-semibold outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/15">
                    </div>
                </div>
            </div>

            {{-- Preview total --}}
            <div class="flex items-center justify-between rounded-2xl bg-slate-900 px-5 py-4">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Total a cobrar</p>
                    <p class="mt-0.5 text-[11px] text-slate-500" x-show="desconto > 0"
                       x-text="'R$ ' + fmt(servico) + ' − R$ ' + fmt(desconto)"></p>
                </div>
                <span class="text-[24px] font-black tabular-nums text-white" x-text="'R$ ' + fmt(total)"></span>
            </div>

            <button type="submit" class="w-full rounded-xl bg-blue-600 py-2.5 text-[13px] font-bold text-white transition hover:bg-blue-700 active:scale-[0.99]">
                Salvar valores
            </button>
        </form>
    </div>

</div>

{{-- Direita: status orçamento --}}
<div class="space-y-4">
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
        <div class="border-b border-slate-100 px-5 py-3.5">
            <h2 class="text-[13.5px] font-bold text-slate-900">Status do orçamento</h2>
        </div>
        <form action="{{ route('app.os.update', $ordem) }}" method="POST" class="space-y-3 p-4">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="{{ $ordem->status }}">
            <input type="hidden" name="problema_relatado" value="{{ $ordem->problema_relatado }}">
            <div class="space-y-2">
                @foreach([
                    'pendente' => ['Aguardando cliente', 'has-[:checked]:border-amber-400 has-[:checked]:bg-amber-50 has-[:checked]:text-amber-800', 'bg-amber-400'],
                    'aprovado' => ['Aprovado pelo cliente', 'has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:text-emerald-800', 'bg-emerald-500'],
                    'recusado' => ['Recusado pelo cliente', 'has-[:checked]:border-red-400 has-[:checked]:bg-red-50 has-[:checked]:text-red-700', 'bg-red-400'],
                ] as $val => [$lbl, $cls, $dot])
                <label class="flex cursor-pointer items-center gap-3 rounded-xl border-2 border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-500 transition hover:border-slate-300 {{ $cls }}">
                    <input type="radio" name="status_orcamento" value="{{ $val }}"
                           @checked($ordem->status_orcamento === $val || (!$ordem->status_orcamento && $val === 'pendente'))
                           class="sr-only">
                    <span class="h-2 w-2 shrink-0 rounded-full {{ $dot }}"></span>
                    <span class="text-[13px] font-semibold">{{ $lbl }}</span>
                </label>
                @endforeach
            </div>
            <button type="submit" class="w-full rounded-xl bg-slate-900 py-2.5 text-[13px] font-bold text-white transition hover:bg-slate-800">
                Salvar
            </button>
        </form>
    </div>

    @if($ordem->token)
    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4" x-data="{ copied: false }">
        <p class="mb-1 text-[12px] font-bold text-blue-900">Link de aprovação</p>
        <p class="mb-3 text-[11.5px] leading-relaxed text-blue-700">O cliente pode aprovar ou recusar diretamente no portal.</p>
        <div class="flex items-center gap-2 rounded-xl border border-blue-200 bg-white px-3 py-2.5">
            <span class="flex-1 font-mono text-[12px] font-bold tracking-widest text-slate-900 truncate">{{ $ordem->token }}</span>
            <button @click="navigator.clipboard.writeText('{{ route('portal.token', $ordem->token) }}'); copied=true; setTimeout(()=>copied=false,2000)"
                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-slate-400 transition hover:bg-blue-100 hover:text-blue-600">
                <svg x-show="!copied" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                <svg x-show="copied" class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none"><path d="M20 6 9 17l-5-5"/></svg>
            </button>
        </div>
    </div>
    @endif
</div>

</div>
</div>{{-- /tab orcamento --}}

{{-- ══════════════════════ TAB: ARQUIVOS ═══════════════════════════════════ --}}
<div x-show="tab==='arquivos'" x-cloak>
<div class="grid gap-5 xl:grid-cols-[1fr_300px]">

{{-- Lista --}}
<div>
    @if($ordem->arquivos->isEmpty())
    <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-white py-16 text-center">
        <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">
            <svg class="h-7 w-7 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15.5 2H8.6c-.4 0-.8.2-1.1.5-.3.3-.5.7-.5 1.1v12.8c0 .4.2.8.5 1.1.3.3.7.5 1.1.5h9.8c.4 0 .8-.2 1.1-.5.3-.3.5-.7.5-1.1V6.5L15.5 2z"/><polyline points="15 2 15 7 20 7"/></svg>
        </div>
        <p class="text-[14px] font-semibold text-slate-600">Nenhum arquivo anexado</p>
        <p class="mt-1 text-[13px] text-slate-400">Use o painel ao lado para enviar documentos.</p>
    </div>
    @else
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
        <div class="border-b border-slate-100 px-5 py-3.5">
            <h2 class="text-[13.5px] font-bold text-slate-900">
                {{ $ordem->arquivos->count() }} {{ $ordem->arquivos->count() === 1 ? 'arquivo' : 'arquivos' }}
            </h2>
        </div>
        <ul class="divide-y divide-slate-100">
            @foreach($ordem->arquivos as $arquivo)
            @php
            $iconCls = match($arquivo->tipo) {
                'os_assinada'  => 'bg-emerald-100 text-emerald-600',
                'foto_entrada','foto_saida' => 'bg-blue-100 text-blue-600',
                'orcamento'    => 'bg-amber-100 text-amber-600',
                'laudo'        => 'bg-indigo-100 text-indigo-600',
                'nota_fiscal'  => 'bg-purple-100 text-purple-600',
                default        => 'bg-slate-100 text-slate-500',
            };
            @endphp
            <li class="group flex items-center gap-3.5 px-5 py-3.5 transition hover:bg-slate-50/60">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $iconCls }}">
                    @if($arquivo->isImagem())
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    @elseif($arquivo->isPdf())
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    @else
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15.5 2H8.6c-.4 0-.8.2-1.1.5-.3.3-.5.7-.5 1.1v12.8c0 .4.2.8.5 1.1.3.3.7.5 1.1.5h9.8c.4 0 .8-.2 1.1-.5.3-.3.5-.7.5-1.1V6.5L15.5 2z"/><polyline points="15 2 15 7 20 7"/></svg>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[13px] font-semibold text-slate-800">{{ $arquivo->nome_original }}</p>
                    <div class="mt-0.5 flex flex-wrap items-center gap-2 text-[11px]">
                        <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10.5px] font-semibold text-slate-500">{{ $tipos[$arquivo->tipo]['label'] ?? $arquivo->tipo }}</span>
                        <span class="text-slate-400 tabular-nums">{{ $arquivo->tamanho_formatado }}</span>
                        <span class="text-slate-400">{{ $arquivo->created_at->format('d/m/Y H:i') }}</span>
                        @if($arquivo->usuario)<span class="text-slate-400">· {{ $arquivo->usuario->name }}</span>@endif
                    </div>
                    @if($arquivo->descricao)<p class="mt-0.5 text-[11.5px] text-slate-500">{{ $arquivo->descricao }}</p>@endif
                </div>
                <div class="flex items-center gap-0.5 opacity-0 transition group-hover:opacity-100">
                    <a href="{{ route('app.os.arquivos.download', [$ordem, $arquivo]) }}"
                       class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition hover:bg-blue-100 hover:text-blue-600">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    </a>
                    <form method="POST" action="{{ route('app.os.arquivos.destroy', [$ordem, $arquivo]) }}" onsubmit="return confirm('Remover arquivo?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition hover:bg-red-100 hover:text-red-600">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                        </button>
                    </form>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

{{-- Upload --}}
<div class="space-y-4">
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
        <div class="border-b border-slate-100 px-5 py-3.5">
            <h2 class="text-[13.5px] font-bold text-slate-900">Adicionar arquivo</h2>
            <p class="mt-0.5 text-[11.5px] text-slate-400">Máx. 20 MB · PDF, imagens, documentos</p>
        </div>
        <form action="{{ route('app.os.arquivos.store', $ordem) }}" method="POST" enctype="multipart/form-data" class="space-y-4 p-4">
            @csrf
            <div>
                <label class="mb-1.5 block text-[12px] font-semibold text-slate-600">Tipo <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="tipo" required class="h-9 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 pl-3 pr-8 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                        @foreach($tipos as $key => $cfg)
                        <option value="{{ $key }}">{{ $cfg['label'] }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                </div>
            </div>
            <div x-data="{ name: '' }">
                <label class="mb-1.5 block text-[12px] font-semibold text-slate-600">Arquivo <span class="text-red-500">*</span></label>
                <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center transition hover:border-blue-300 hover:bg-blue-50/30"
                       :class="name ? 'border-blue-400 bg-blue-50/30' : ''">
                    <svg class="h-7 w-7" :class="name ? 'text-blue-500' : 'text-slate-300'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                    <span class="text-[12.5px] font-medium" :class="name ? 'text-blue-700' : 'text-slate-500'"
                          x-text="name || 'Clique ou arraste aqui'"></span>
                    <input type="file" name="arquivo" class="hidden" required @change="name = $event.target.files[0]?.name ?? ''">
                </label>
            </div>
            <div>
                <label class="mb-1.5 block text-[12px] font-semibold text-slate-600">Descrição <span class="text-[11px] font-normal text-slate-400">(opcional)</span></label>
                <input type="text" name="descricao" maxlength="255" placeholder="Ex.: OS assinada pelo cliente"
                       class="h-9 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
            </div>
            <button type="submit" class="w-full rounded-xl bg-blue-600 py-2.5 text-[13px] font-bold text-white transition hover:bg-blue-700">
                Enviar arquivo
            </button>
        </form>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
        <div class="flex gap-3">
            <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
            <div>
                <p class="text-[12.5px] font-semibold text-slate-700">Armazenamento seguro</p>
                <p class="mt-1 text-[11.5px] leading-relaxed text-slate-500">Os arquivos ficam em armazenamento privado e servem como prova documental.</p>
            </div>
        </div>
    </div>
</div>

</div>
</div>{{-- /tab arquivos --}}

</div>{{-- /x-data tabs --}}

@endsection
