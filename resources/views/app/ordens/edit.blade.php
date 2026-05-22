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
    class="mx-auto max-w-[860px]"
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
            <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $statusCores[$ordem->status] ?? 'bg-slate-100 text-slate-600' }}">
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
      @submit="busy=true" class="grid gap-5 lg:grid-cols-[1fr_280px]">
@csrf @method('PUT')

{{-- COLUNA ESQUERDA --}}
<div class="space-y-4">

{{-- ── DIAGNÓSTICO E SERVIÇO ──────────────────────────── --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
    <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-orange-500 text-[11px] font-black text-white">1</span>
        <h2 class="text-[14px] font-bold text-slate-900">Diagnóstico e Serviço</h2>
    </div>
    <div class="space-y-4 p-6">

        {{-- Problema --}}
        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label class="text-[12.5px] font-semibold text-slate-700">Problema relatado <span class="text-red-500">*</span></label>
                <span class="text-[11px] text-slate-400">conforme cliente</span>
            </div>
            <div class="rounded-xl border-l-4 border-red-300 bg-red-50/40 px-4 py-3">
                <p class="text-[12px] text-slate-500 italic">{{ $ordem->problema_relatado }}</p>
            </div>
            <input type="hidden" name="problema_relatado" value="{{ $ordem->problema_relatado }}">
        </div>

        {{-- Diagnóstico --}}
        <div>
            <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Diagnóstico técnico</label>
            <textarea name="diagnostico" rows="3"
                      placeholder="O que foi identificado pelo técnico…"
                      class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[13px] leading-relaxed placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">{{ old('diagnostico', $ordem->diagnostico) }}</textarea>
        </div>

        {{-- Solução --}}
        <div>
            <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Solução aplicada</label>
            <textarea name="solucao" rows="2"
                      placeholder="O que foi feito para resolver…"
                      class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[13px] leading-relaxed placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">{{ old('solucao', $ordem->solucao) }}</textarea>
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
        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-violet-600 text-[11px] font-black text-white">2</span>
        <h2 class="text-[14px] font-bold text-slate-900">Status e Atribuição</h2>
    </div>
    <div class="grid gap-4 p-6 sm:grid-cols-2">
        <div>
            <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Status <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-2 gap-2">
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
                @endphp
                <label class="flex cursor-pointer items-center gap-2 rounded-xl border-2 border-slate-200 bg-slate-50/50 px-3 py-2 text-[12px] font-medium text-slate-500 transition hover:border-slate-300 {{ $sBg }}">
                    <input type="radio" name="status" value="{{ $key }}" class="sr-only"
                           @checked(old('status', $ordem->status) === $key)>
                    <span class="truncate">{{ $s['label'] }}</span>
                </label>
                @endforeach
            </div>
            @error('status')<p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-4">
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Técnico responsável</label>
                <div class="relative">
                    <select name="tecnico_id"
                            class="h-10 w-full appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
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
                <input type="date" name="previsao_entrega"
                       value="{{ old('previsao_entrega', $ordem->previsao_entrega?->format('Y-m-d')) }}"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
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
        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-[11px] font-black text-white">3</span>
        <h2 class="text-[14px] font-bold text-slate-900">Valores do Orçamento</h2>
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
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-400">Total a cobrar</p>
                <p class="mt-0.5 text-[11px] text-slate-500" x-show="desconto > 0"
                   x-text="fmt(servico) + ' − R$ ' + desconto.toFixed(2).replace('.',',')"></p>
            </div>
            <span class="text-[24px] font-black tabular-nums text-white" x-text="fmt(total)">
                R$ {{ number_format($ordem->valor_servico - $ordem->desconto, 2, ',', '.') }}
            </span>
        </div>

        {{-- Status orçamento --}}
        <div>
            <label class="mb-2 block text-[12.5px] font-semibold text-slate-700">Status do orçamento</label>
            <div class="grid grid-cols-3 gap-2">
                @foreach(['pendente'=>['Aguardando','bg-amber-100 text-amber-700','has-[:checked]:border-amber-400 has-[:checked]:bg-amber-50'],'aprovado'=>['Aprovado','bg-emerald-100 text-emerald-700','has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50'],'recusado'=>['Recusado','bg-red-100 text-red-600','has-[:checked]:border-red-400 has-[:checked]:bg-red-50']] as $val=>[$lbl,$badge,$cls])
                <label class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border-2 border-slate-200 py-2.5 text-[12px] font-semibold text-slate-500 transition hover:border-slate-300 {{ $cls }}">
                    <input type="radio" name="status_orcamento" value="{{ $val }}" class="sr-only"
                           @checked(old('status_orcamento', $ordem->status_orcamento ?? 'pendente') === $val)>
                    <span class="inline-block h-2 w-2 rounded-full {{ explode(' ', $badge)[0] }}"></span>
                    {{ $lbl }}
                </label>
                @endforeach
            </div>
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
