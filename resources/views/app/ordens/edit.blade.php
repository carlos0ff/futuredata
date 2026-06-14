@extends('layouts.app')
@section('title', 'Editar OS ' . $ordem->numero)

@section('breadcrumbs')
@include('layouts.partials.breadcrumbs', ['items' => [
    ['label' => 'Ordens de Serviço', 'href' => route('app.os.index')],
    ['label' => $ordem->numero, 'href' => route('app.os.show', $ordem)],
    ['label' => 'Editar'],
]])
@endsection

@php
$statusCores = [
    'entrada'            => 'bg-slate-100 text-slate-700',
    'analise'            => 'bg-amber-100 text-amber-700',
    'execucao'           => 'bg-blue-100 text-blue-700',
    'aguardando_cliente' => 'bg-violet-100 text-violet-700',
    'em_teste'           => 'bg-cyan-100 text-cyan-700',
    'finalizado'         => 'bg-emerald-100 text-emerald-700',
    'cancelado'          => 'bg-red-100 text-red-600',
];
$statusIcons = [
    'entrada'            => '<path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/>',
    'analise'            => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>',
    'execucao'           => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
    'aguardando_cliente' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
    'em_teste'           => '<path d="M9 2v6l-5 9a2 2 0 0 0 1.8 3h12.4a2 2 0 0 0 1.8-3l-5-9V2"/><path d="M9 2h6"/>',
    'finalizado'         => '<path d="M5 13l4 4L19 7"/>',
    'cancelado'          => '<path d="M18 6 6 18M6 6l12 12"/>',
];
$statusIconColor = [
    'entrada'            => 'text-slate-400',
    'analise'            => 'text-amber-500',
    'execucao'           => 'text-blue-500',
    'aguardando_cliente' => 'text-violet-500',
    'em_teste'           => 'text-cyan-500',
    'finalizado'         => 'text-emerald-500',
    'cancelado'          => 'text-red-400',
];
@endphp

@section('content')

<div
    x-data="{
        servico:  {{ (float) old('valor_servico', $ordem->valor_servico) }},
        desconto: {{ (float) old('desconto', $ordem->desconto) }},
        get total() { return Math.max(0, this.servico - this.desconto); },
        fmt(v) { return 'R$ ' + v.toFixed(2).replace('.',',').replace(/\B(?=(\d{3})+(?!\d))/g,'.'); },
        busy: false,
    }"
    class="mx-auto w-full max-w-[1180px]"
>

{{-- HEADER --}}
<div class="mb-7 flex items-center gap-4">
    <a href="{{ route('app.os.show', $ordem) }}"
       class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m12 19-7-7 7-7M19 12H5"/></svg>
    </a>
    <div class="flex-1 min-w-0">
        <div class="flex flex-wrap items-center gap-2.5">
            <h1 class="text-[20px] font-extrabold tracking-tight text-slate-900">Editar OS</h1>
            <span class="font-mono text-[16px] font-bold text-slate-400">{{ $ordem->numero }}</span>
            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $statusCores[$ordem->status] ?? 'bg-slate-100 text-slate-600' }}">
                <svg class="h-3 w-3 {{ $statusIconColor[$ordem->status] ?? 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">{!! $statusIcons[$ordem->status] ?? '' !!}</svg>
                {{ $status[$ordem->status]['label'] ?? $ordem->status }}
            </span>
        </div>
        <p class="mt-0.5 text-[12.5px] text-slate-400">
            {{ $ordem->cliente?->nome }} ·
            {{ $ordem->equipamento?->tipo }}{{ $ordem->equipamento?->marca ? ' · '.$ordem->equipamento->marca : '' }}
        </p>
    </div>
    <a href="{{ route('app.os.show', $ordem) }}"
       class="hidden sm:flex h-9 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 text-[12.5px] font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        Ver OS
    </a>
</div>

@if($errors->any())
<div class="mb-5 flex gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4">
    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
    <div>
        <p class="text-[13px] font-bold text-red-800">Corrija os erros antes de salvar</p>
        <ul class="mt-1 space-y-0.5 text-[12.5px] text-red-700">
            @foreach($errors->all() as $e)<li>· {{ $e }}</li>@endforeach
        </ul>
    </div>
</div>
@endif

<form action="{{ route('app.os.update', $ordem) }}" method="POST"
      @submit="busy=true" class="grid gap-5 lg:grid-cols-[1fr_300px]">
@csrf @method('PUT')

{{-- COLUNA ESQUERDA --}}
<div class="space-y-4">

{{-- ── DIAGNÓSTICO E SERVIÇO ──────────────────────────── --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
    <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-orange-50">
            <svg class="h-4.5 w-4.5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        </span>
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Etapa 1</p>
            <h2 class="text-[14.5px] font-bold text-slate-900">Diagnóstico e Serviço</h2>
        </div>
    </div>
    <div class="space-y-4 p-6">

        {{-- Problema --}}
        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label class="text-[12.5px] font-semibold text-slate-700">Problema relatado <span class="text-red-500">*</span></label>
                <span class="text-[11px] text-slate-400">conforme cliente</span>
            </div>
            <div class="flex gap-3 rounded-xl border-l-4 border-red-300 bg-red-50/40 px-4 py-3">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                <p class="text-[12px] leading-relaxed text-slate-500 italic">{{ $ordem->problema_relatado }}</p>
            </div>
            <input type="hidden" name="problema_relatado" value="{{ $ordem->problema_relatado }}">
        </div>

        {{-- Diagnóstico + Solução --}}
        <div class="grid gap-4 xl:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Diagnóstico técnico</label>
                <textarea name="diagnostico" rows="4"
                          placeholder="O que foi identificado pelo técnico…"
                          class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[13px] leading-relaxed placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">{{ old('diagnostico', $ordem->diagnostico) }}</textarea>
            </div>
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Solução aplicada</label>
                <textarea name="solucao" rows="4"
                          placeholder="O que foi feito para resolver…"
                          class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[13px] leading-relaxed placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">{{ old('solucao', $ordem->solucao) }}</textarea>
            </div>
        </div>

        {{-- Observações --}}
        <div>
            <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">
                Observações internas
                <span class="ml-1 text-[10.5px] font-normal text-slate-400">não visível ao cliente</span>
            </label>
            <textarea name="observacoes" rows="2"
                      placeholder="Notas para a equipe…"
                      class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[13px] leading-relaxed placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">{{ old('observacoes', $ordem->observacoes) }}</textarea>
        </div>
    </div>
</div>

{{-- ── STATUS E TÉCNICO ────────────────────────────────── --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
    <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-50">
            <svg class="h-4.5 w-4.5 text-violet-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </span>
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Etapa 2</p>
            <h2 class="text-[14.5px] font-bold text-slate-900">Status e Atribuição</h2>
        </div>
    </div>
    <div class="space-y-5 p-6">
        <div>
            <label class="mb-2 block text-[12.5px] font-semibold text-slate-700">Status <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 lg:grid-cols-7">
                @foreach($status as $key => $s)
                @php
                $sBg = match($key) {
                    'finalizado' => 'has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:text-emerald-800',
                    'cancelado'  => 'has-[:checked]:border-red-400 has-[:checked]:bg-red-50 has-[:checked]:text-red-700',
                    'execucao'   => 'has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-800',
                    'analise'    => 'has-[:checked]:border-amber-400 has-[:checked]:bg-amber-50 has-[:checked]:text-amber-800',
                    'em_teste'   => 'has-[:checked]:border-cyan-500 has-[:checked]:bg-cyan-50 has-[:checked]:text-cyan-800',
                    'aguardando_cliente' => 'has-[:checked]:border-violet-500 has-[:checked]:bg-violet-50 has-[:checked]:text-violet-800',
                    default      => 'has-[:checked]:border-slate-700 has-[:checked]:bg-slate-900 has-[:checked]:text-white',
                };
                $sIconBg = match($key) {
                    'finalizado' => 'has-[:checked]:bg-emerald-500',
                    'cancelado'  => 'has-[:checked]:bg-red-400',
                    'execucao'   => 'has-[:checked]:bg-blue-500',
                    'analise'    => 'has-[:checked]:bg-amber-400',
                    'em_teste'   => 'has-[:checked]:bg-cyan-500',
                    'aguardando_cliente' => 'has-[:checked]:bg-violet-500',
                    default      => 'has-[:checked]:bg-slate-700',
                };
                @endphp
                <label class="group flex cursor-pointer flex-col items-center gap-1.5 rounded-xl border-2 border-slate-200 bg-slate-50/50 px-2 py-3 text-center transition hover:border-slate-300 {{ $sBg }}">
                    <input type="radio" name="status" value="{{ $key }}" class="sr-only"
                           @checked(old('status', $ordem->status) === $key)>
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-slate-400 transition {{ $sIconBg }} group-has-[:checked]:text-white">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">{!! $statusIcons[$key] ?? '' !!}</svg>
                    </span>
                    <span class="text-[11px] font-semibold leading-tight">{{ $s['label'] }}</span>
                </label>
                @endforeach
            </div>
            @error('status')<p class="mt-1.5 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Técnico responsável</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <select name="tecnico_id"
                            class="h-10 w-full appearance-none rounded-xl border border-slate-200 bg-white pl-9 pr-8 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                        <option value="">Sem técnico</option>
                        @foreach($tecnicos as $t)
                        <option value="{{ $t->id }}" @selected(old('tecnico_id', $ordem->tecnico_id) == $t->id)>{{ $t->name }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Previsão de entrega</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <input type="date" name="previsao_entrega"
                           value="{{ old('previsao_entrega', $ordem->previsao_entrega?->format('Y-m-d')) }}"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">
                    Nota para o técnico
                    <span class="ml-1 text-[10.5px] font-normal text-slate-400">opcional</span>
                </label>
                <input type="text" name="observacao_status"
                       value="{{ old('observacao_status') }}"
                       placeholder="Ex.: Aguardar peça chegar"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
            </div>
        </div>
    </div>
</div>

{{-- ── VALORES ─────────────────────────────────────────── --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
    <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-50">
            <svg class="h-4.5 w-4.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </span>
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Etapa 3</p>
            <h2 class="text-[14.5px] font-bold text-slate-900">Valores do Orçamento</h2>
        </div>
    </div>
    <div class="p-6 space-y-4">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Valor do serviço</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[12.5px] font-semibold text-slate-400">R$</span>
                    <input type="number" name="valor_servico" step="0.01" min="0"
                           value="{{ old('valor_servico', $ordem->valor_servico) }}"
                           x-model.number="servico"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-[13px] tabular-nums font-semibold outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Desconto</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[12.5px] font-semibold text-slate-400">R$</span>
                    <input type="number" name="desconto" step="0.01" min="0"
                           value="{{ old('desconto', $ordem->desconto) }}"
                           x-model.number="desconto"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-[13px] tabular-nums font-semibold outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                </div>
            </div>
            <input type="hidden" name="valor_pecas" value="0">
        </div>

        {{-- Total preview --}}
        <div class="flex items-center justify-between rounded-2xl bg-slate-900 px-5 py-4">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/10">
                    <svg class="h-4.5 w-4.5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </span>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Total a cobrar</p>
                    <p class="mt-0.5 text-[11px] text-slate-500" x-show="desconto > 0"
                       x-text="fmt(servico) + ' − R$ ' + desconto.toFixed(2).replace('.',',')"></p>
                </div>
            </div>
            <span class="text-[24px] font-black tabular-nums text-white" x-text="fmt(total)">
                R$ {{ number_format($ordem->valor_servico - $ordem->desconto, 2, ',', '.') }}
            </span>
        </div>

        {{-- Status orçamento (somente leitura — aprovação é feita pelo cliente no portal) --}}
        <div>
            <label class="mb-2 block text-[12.5px] font-semibold text-slate-700">Status do orçamento</label>
            <input type="hidden" name="status_orcamento" value="{{ $ordem->status_orcamento ?? 'pendente' }}">
            <div class="grid grid-cols-3 gap-2">
                @php $orcAtual = $ordem->status_orcamento ?? 'pendente'; @endphp
                @foreach(['pendente'=>['Aguardando','bg-amber-400','border-amber-300 bg-amber-50 text-amber-900'],'aprovado'=>['Aprovado','bg-emerald-500','border-emerald-300 bg-emerald-50 text-emerald-900'],'recusado'=>['Recusado','bg-red-400','border-red-300 bg-red-50 text-red-900']] as $val=>[$lbl,$dot,$activeCls])
                <div class="flex items-center justify-center gap-2 rounded-xl border-2 py-2.5 text-[12px] font-semibold transition {{ $orcAtual === $val ? $activeCls : 'border-slate-100 bg-slate-50/50 text-slate-400' }}">
                    <span class="inline-block h-2 w-2 rounded-full {{ $orcAtual === $val ? $dot : 'bg-slate-300' }}"></span>
                    {{ $lbl }}
                </div>
                @endforeach
            </div>
            <p class="mt-2 flex items-start gap-1.5 text-[11px] leading-relaxed text-slate-400">
                <svg class="mt-0.5 h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                A aprovação ou recusa do orçamento é feita pelo cliente no portal.
            </p>
        </div>
    </div>
</div>

{{-- Submit mobile --}}
<div class="flex gap-3 pb-2 lg:hidden">
    <a href="{{ route('app.os.show', $ordem) }}"
       class="flex h-11 flex-1 items-center justify-center rounded-xl border border-slate-200 bg-white text-[13px] font-semibold text-slate-700 transition hover:bg-slate-50">
        Cancelar
    </a>
    <button type="submit" :disabled="busy"
            class="flex h-11 flex-[2] items-center justify-center gap-2 rounded-xl bg-blue-600 text-[13px] font-bold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700 disabled:opacity-60">
        <span x-text="busy ? 'Salvando…' : 'Salvar alterações'">Salvar alterações</span>
    </button>
</div>

</div>{{-- /esquerda --}}

{{-- COLUNA DIREITA (sticky) --}}
<div class="hidden lg:block">
<div class="sticky top-[74px] space-y-4">

    {{-- Info da OS --}}
    <div class="overflow-hidden rounded-2xl bg-[#0d0f16] shadow-sm">
        <div class="px-5 py-4">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Ordem de Serviço</p>
            <p class="mt-1 font-mono text-[22px] font-black tracking-tight text-white">{{ $ordem->numero }}</p>
            <div class="mt-3 space-y-1.5 text-[12px]">
                <div class="flex items-center gap-2 text-slate-400">
                    <svg class="h-3.5 w-3.5 shrink-0 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="truncate">{{ $ordem->cliente?->nome ?? '—' }}</span>
                </div>
                <div class="flex items-center gap-2 text-slate-400">
                    <svg class="h-3.5 w-3.5 shrink-0 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                    <span class="truncate">{{ $ordem->equipamento?->tipo }}{{ $ordem->equipamento?->marca ? ' · '.$ordem->equipamento->marca : '' }}</span>
                </div>
                <div class="flex items-center gap-2 text-slate-400">
                    <svg class="h-3.5 w-3.5 shrink-0 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Aberta {{ $ordem->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Total ao vivo --}}
    <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
        <p class="text-[10.5px] font-black uppercase tracking-widest text-slate-400">Valor total</p>
        <p class="mt-2 text-[28px] font-black tabular-nums text-slate-900" x-text="fmt(total)">
            R$ {{ number_format($ordem->valor_servico - $ordem->desconto, 2, ',', '.') }}
        </p>
        <p class="mt-0.5 text-[11.5px] text-slate-400" x-show="desconto > 0"
           x-text="'Serviço: ' + fmt(servico) + ' com desconto de R$ ' + desconto.toFixed(2).replace('.',',')"></p>
    </div>

    {{-- Salvar --}}
    <button type="submit" :disabled="busy"
            class="flex h-12 w-full items-center justify-center gap-2.5 rounded-2xl bg-blue-600 font-bold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 disabled:opacity-60 active:scale-[0.98]">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <span x-text="busy ? 'Salvando…' : 'Salvar alterações'">Salvar alterações</span>
    </button>

    <a href="{{ route('app.os.show', $ordem) }}"
       class="flex h-10 w-full items-center justify-center rounded-xl border border-slate-200 text-[13px] font-semibold text-slate-600 transition hover:bg-slate-50">
        Cancelar
    </a>

    <a href="{{ route('app.os.print', $ordem) }}" target="_blank"
       class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[12.5px] font-semibold text-slate-600 transition hover:bg-slate-50 w-full justify-center">
        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
        Imprimir OS
    </a>

</div>
</div>{{-- /direita --}}

</form>
</div>

@endsection
