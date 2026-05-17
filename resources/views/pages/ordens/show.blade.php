@extends('layouts.app')
@section('title', 'OS ' . $ordem->numero)

@section('breadcrumbs')
@include('layouts.partials.breadcrumbs', ['items' => [
    ['label' => 'Ordens de Serviço', 'href' => route('ordens.index')],
    ['label' => $ordem->numero],
]])
@endsection

@section('content')
@php
    $badgeClass = match($ordem->status) {
        'finalizado'         => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'execucao'           => 'border-blue-200 bg-blue-50 text-blue-700',
        'em_teste'           => 'border-cyan-200 bg-cyan-50 text-cyan-700',
        'analise'            => 'border-amber-200 bg-amber-50 text-amber-700',
        'aguardando_cliente' => 'border-purple-200 bg-purple-50 text-purple-700',
        'cancelado'          => 'border-red-200 bg-red-50 text-red-600',
        default              => 'border-slate-200 bg-slate-100 text-slate-700',
    };
@endphp

{{-- Header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <div class="flex items-start gap-3">
        <a href="{{ route('ordens.index') }}" class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7M19 12H5"/></svg>
        </a>
        <div>
            <div class="flex flex-wrap items-center gap-2.5">
                <h1 class="font-mono text-[22px] font-bold text-slate-900">{{ $ordem->numero }}</h1>
                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11.5px] font-semibold {{ $badgeClass }}">
                    {{ $status[$ordem->status]['label'] ?? $ordem->status }}
                </span>
            </div>
            <p class="mt-0.5 text-[12.5px] text-slate-500">
                Criada em {{ $ordem->created_at->format('d \d\e F \d\e Y') }}
                @if($ordem->tecnico) · Técnico: {{ $ordem->tecnico->name }} @endif
            </p>
        </div>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('ordens.edit', $ordem) }}" class="inline-flex h-9 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-700 transition hover:bg-slate-50">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Editar
        </a>
        <button onclick="window.print()" class="inline-flex h-9 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-700 transition hover:bg-slate-50">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
            Imprimir
        </button>
    </div>
</div>

{{-- Grid --}}
<div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_340px]">

    <div class="space-y-5">

        {{-- Cliente --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
                <h2 class="text-[13.5px] font-bold text-slate-900">Cliente</h2>
            </div>
            <div class="grid grid-cols-2 gap-3 p-5 sm:grid-cols-4">
                @foreach(['Nome' => $ordem->cliente?->nome, 'Telefone' => $ordem->cliente?->telefone, 'E-mail' => $ordem->cliente?->email, 'Cidade' => ($ordem->cliente?->cidade ?? '') . (($ordem->cliente?->estado) ? ' / '.$ordem->cliente->estado : '')] as $label => $valor)
                <div class="rounded-xl bg-slate-50 px-4 py-3">
                    <p class="mb-0.5 text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">{{ $label }}</p>
                    <p class="text-[13px] font-medium text-slate-800">{{ $valor ?: '—' }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Equipamento --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
                <h2 class="text-[13.5px] font-bold text-slate-900">Equipamento</h2>
                @if($ordem->equipamento?->em_garantia)
                <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">Em garantia</span>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-3 p-5 sm:grid-cols-3">
                @foreach(['Tipo' => $ordem->equipamento?->tipo, 'Marca' => $ordem->equipamento?->marca, 'Modelo' => $ordem->equipamento?->modelo, 'Nº Série' => $ordem->equipamento?->numero_serie, 'Acessórios' => $ordem->equipamento?->acessorios, 'Condição' => $ordem->equipamento?->condicao_entrada] as $label => $valor)
                <div class="rounded-xl bg-slate-50 px-4 py-3">
                    <p class="mb-0.5 text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">{{ $label }}</p>
                    <p class="text-[13px] font-medium text-slate-800">{{ $valor ?: '—' }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Problema e Diagnóstico --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-5 py-3.5">
                <h2 class="text-[13.5px] font-bold text-slate-900">Problema e Diagnóstico</h2>
            </div>
            <div class="grid gap-4 p-5 sm:grid-cols-2">
                <div class="rounded-xl bg-red-50 p-4">
                    <p class="mb-1.5 text-[10.5px] font-semibold uppercase tracking-wider text-red-500">Problema relatado</p>
                    <p class="text-[13px] leading-relaxed text-slate-800">{{ $ordem->problema_relatado ?: '—' }}</p>
                </div>
                <div class="rounded-xl bg-blue-50 p-4">
                    <p class="mb-1.5 text-[10.5px] font-semibold uppercase tracking-wider text-blue-500">Diagnóstico técnico</p>
                    <p class="text-[13px] leading-relaxed text-slate-800">{{ $ordem->diagnostico ?: 'Aguardando diagnóstico.' }}</p>
                </div>
            </div>
            @if($ordem->solucao)
            <div class="border-t border-slate-100 px-5 pb-5">
                <div class="rounded-xl bg-emerald-50 p-4">
                    <p class="mb-1.5 text-[10.5px] font-semibold uppercase tracking-wider text-emerald-600">Solução aplicada</p>
                    <p class="text-[13px] leading-relaxed text-slate-800">{{ $ordem->solucao }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Histórico --}}
        @if($ordem->historico->isNotEmpty())
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-5 py-3.5">
                <h2 class="text-[13.5px] font-bold text-slate-900">Histórico</h2>
            </div>
            <div class="p-5">
                <ol class="relative space-y-4 border-l border-slate-200 pl-5">
                    @foreach($ordem->historico as $h)
                    @php
                        $dotColor = match($h->status_novo) {
                            'finalizado' => 'bg-emerald-400',
                            'cancelado'  => 'bg-red-400',
                            'em_teste'   => 'bg-cyan-400',
                            default      => 'bg-blue-400',
                        };
                    @endphp
                    <li class="relative">
                        <span class="absolute -left-[22px] top-1.5 h-2.5 w-2.5 rounded-full ring-2 ring-white {{ $dotColor }}"></span>
                        <div class="rounded-xl bg-slate-50 p-3.5">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="text-[13px] font-semibold text-slate-900">{{ $status[$h->status_novo]['label'] ?? $h->status_novo }}</p>
                                <time class="text-[11px] text-slate-400 tabular-nums">{{ $h->created_at->format('d/m/Y \à\s H:i') }}</time>
                            </div>
                            @if($h->observacao)<p class="mt-1 text-[12.5px] text-slate-500">{{ $h->observacao }}</p>@endif
                            @if($h->usuario)<p class="mt-0.5 text-[11.5px] text-slate-400">por {{ $h->usuario->name }}</p>@endif
                        </div>
                    </li>
                    @endforeach
                </ol>
            </div>
        </div>
        @endif
    </div>

    {{-- Coluna direita --}}
    <div class="space-y-5">

        {{-- Financeiro --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-5 py-3.5">
                <h2 class="text-[13.5px] font-bold text-slate-900">Resumo financeiro</h2>
            </div>
            <div class="space-y-3 p-5 text-[13px]">
                <div class="flex justify-between"><span class="text-slate-500">Serviços</span><span class="font-medium text-slate-800">R$ {{ number_format($ordem->valor_servico, 2, ',', '.') }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Peças</span><span class="font-medium text-slate-800">R$ {{ number_format($ordem->valor_pecas, 2, ',', '.') }}</span></div>
                @if($ordem->desconto > 0)
                <div class="flex justify-between"><span class="text-slate-500">Desconto</span><span class="font-medium text-red-600">− R$ {{ number_format($ordem->desconto, 2, ',', '.') }}</span></div>
                @endif
                <div class="border-t border-slate-100 pt-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-900">Total</span>
                        <span class="text-[20px] font-bold text-slate-900">R$ {{ number_format($ordem->total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Atualizar status --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-5 py-3.5">
                <h2 class="text-[13.5px] font-bold text-slate-900">Atualizar status</h2>
            </div>
            <form action="{{ route('ordens.update', $ordem) }}" method="POST" class="space-y-3 p-5">
                @csrf @method('PUT')
                <div>
                    <label class="mb-1.5 block text-[12px] font-semibold text-slate-600">Novo status</label>
                    <div class="relative">
                        <select name="status" class="h-9 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 pl-3 pr-8 text-[13px] text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            @foreach($status as $key => $s)
                            <option value="{{ $key }}" @selected($ordem->status === $key)>{{ $s['label'] }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-[12px] font-semibold text-slate-600">Observação</label>
                    <textarea name="observacao_status" rows="3" placeholder="Ex.: Cliente notificado via WhatsApp."
                              class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"></textarea>
                </div>
                <button type="submit" class="w-full rounded-xl bg-blue-600 py-2.5 text-[13.5px] font-bold text-white transition hover:bg-blue-700">Atualizar</button>
            </form>
        </div>

        @if($ordem->previsao_entrega)
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white px-5 py-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Previsão de entrega</p>
            <p class="mt-1 text-[15px] font-bold text-slate-900">{{ $ordem->previsao_entrega->format('d/m/Y') }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
