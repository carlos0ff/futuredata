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
        <a href="{{ route('app.os.index') }}" class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50">
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
        {{-- Portal do cliente --}}
        @if($ordem->token)
        <a href="{{ route('portal.token', $ordem->token) }}" target="_blank"
           class="inline-flex h-9 items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 text-[13px] font-semibold text-white shadow-md shadow-blue-500/20 transition hover:from-blue-500 hover:to-indigo-500">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Portal do Cliente
        </a>
        @endif
        <a href="{{ route('app.os.edit', $ordem) }}" class="inline-flex h-9 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-700 transition hover:bg-slate-50">
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
            <form action="{{ route('app.os.update', $ordem) }}" method="POST" class="space-y-3 p-5">
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

        {{-- Portal do Cliente --}}
        @if($ordem->token)
        <div class="overflow-hidden rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-indigo-50 shadow-sm"
             x-data="{ copied: false }">
            <div class="flex items-center gap-2.5 border-b border-blue-100 px-5 py-3.5">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </div>
                <h3 class="text-[13px] font-bold text-blue-900">Portal do Cliente</h3>
            </div>

            <div class="p-4 space-y-3">
                {{-- Token badge --}}
                <div class="flex items-center justify-between rounded-xl border border-blue-200 bg-white px-3.5 py-2.5">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-0.5">Código de acesso</p>
                        <span class="font-mono text-[15px] font-bold tracking-widest text-slate-900">{{ $ordem->token }}</span>
                    </div>
                    <button
                        @click="navigator.clipboard.writeText('{{ $ordem->token }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="ml-3 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600">
                        <svg x-show="!copied" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        <svg x-show="copied" class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </div>

                {{-- URL --}}
                <div class="rounded-xl border border-blue-200 bg-white px-3.5 py-2.5">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-1">Link do portal</p>
                    <div class="flex items-center gap-2">
                        <code class="flex-1 truncate text-[11.5px] text-blue-700 font-mono">{{ route('portal.token', $ordem->token) }}</code>
                        <button
                            @click="navigator.clipboard.writeText('{{ route('portal.token', $ordem->token) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-400 transition hover:border-blue-300 hover:text-blue-600">
                            <svg x-show="!copied" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            <svg x-show="copied" class="h-3 w-3 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Botões de acção --}}
                <a href="{{ route('portal.token', $ordem->token) }}" target="_blank"
                   class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-2.5 text-[13px] font-bold text-white shadow-sm shadow-blue-500/20 transition hover:bg-blue-700 active:scale-[0.98]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Abrir portal do cliente
                </a>

                @if($ordem->cliente?->telefone)
                @php $wa = 'https://wa.me/55'.preg_replace('/\D/','',$ordem->cliente->telefone).'?text='.urlencode('Olá! Acesse o portal para acompanhar sua OS: '.route('portal.token', $ordem->token)); @endphp
                <a href="{{ $wa }}" target="_blank" rel="noopener"
                   class="flex w-full items-center justify-center gap-2 rounded-xl border border-[#25d366]/40 bg-[#25d366]/10 py-2.5 text-[13px] font-semibold text-[#128c4e] transition hover:bg-[#25d366]/15 active:scale-[0.98]">
                    <svg class="h-4 w-4 text-[#25d366]" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    Enviar link ao cliente
                </a>
                @endif

                <p class="text-center text-[10.5px] text-slate-400">
                    O cliente acede sem login · Código: <span class="font-mono font-semibold text-slate-600">{{ $ordem->token }}</span>
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
