@extends('layouts.app')
@section('title', 'OS ' . $ordem->numero)

@section('breadcrumbs')
@include('layouts.partials.breadcrumbs', ['items' => [
    ['label' => 'Ordens de Serviço', 'href' => route('app.os.index')],
    ['label' => $ordem->numero],
]])
@endsection

@section('content')

{{-- ── Page Header ───────────────────────────────────────────────────────────── --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
     x-data="{ tab: window.location.hash === '#tab-arquivos' ? 'arquivos' : (window.location.hash === '#tab-orcamento' ? 'orcamento' : 'detalhes') }">

    <div class="flex items-start gap-3">
        <a href="{{ route('app.os.index') }}"
           class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m12 19-7-7 7-7M19 12H5"/>
            </svg>
        </a>
        <div>
            <div class="flex flex-wrap items-center gap-2.5">
                <h1 class="font-mono text-[22px] font-bold text-slate-900">{{ $ordem->numero }}</h1>
                <x-app.os-status :status="$ordem->status" />
                @if($ordem->status_orcamento === 'aprovado')
                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Orçamento aprovado
                    </span>
                @elseif($ordem->status_orcamento === 'recusado')
                    <span class="inline-flex items-center gap-1 rounded-full border border-red-200 bg-red-50 px-2.5 py-1 text-[11px] font-semibold text-red-600">
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        Orçamento recusado
                    </span>
                @endif
            </div>
            <p class="mt-0.5 text-[12.5px] text-slate-500">
                Criada em {{ $ordem->created_at->format('d \d\e F \d\e Y') }}
                @if($ordem->tecnico) · Técnico: {{ $ordem->tecnico->name }} @endif
            </p>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('app.os.print', $ordem) }}" target="_blank"
           class="inline-flex h-9 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-700 transition hover:bg-slate-50">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/>
                <rect x="6" y="14" width="12" height="8" rx="1"/>
            </svg>
            Imprimir OS
        </a>
        <a href="{{ route('app.os.edit', $ordem) }}"
           class="inline-flex h-9 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-[13px] font-semibold text-slate-700 transition hover:bg-slate-50">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Editar
        </a>
        @if($ordem->token)
        <a href="{{ route('portal.token', $ordem->token) }}" target="_blank"
           class="inline-flex h-9 items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 text-[13px] font-semibold text-white shadow-sm shadow-blue-500/20 transition hover:from-blue-500 hover:to-indigo-500">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Portal
        </a>
        @endif
    </div>
</div>

{{-- ── Flash ─────────────────────────────────────────────────────────────────── --}}
@if(session('success'))
<div class="mb-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-[13px] font-medium text-emerald-700">
    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-[13px] font-medium text-red-700">
    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    {{ session('error') }}
</div>
@endif

{{-- ── Tabs ─────────────────────────────────────────────────────────────────── --}}
<div x-data="{ tab: window.location.hash === '#tab-arquivos' ? 'arquivos' : (window.location.hash === '#tab-orcamento' ? 'orcamento' : 'detalhes') }">

    <div class="mb-5 flex gap-1 rounded-xl border border-slate-200 bg-white p-1 shadow-sm w-fit">
        <button @click="tab = 'detalhes'" :class="tab === 'detalhes' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                class="flex items-center gap-1.5 rounded-lg px-4 py-1.5 text-[13px] font-semibold transition-all">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                <rect x="9" y="3" width="6" height="4" rx="1"/>
                <path d="M9 12h6M9 16h4"/>
            </svg>
            Detalhes
        </button>
        <button @click="tab = 'orcamento'; window.location.hash = 'tab-orcamento'"
                :class="tab === 'orcamento' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                class="flex items-center gap-1.5 rounded-lg px-4 py-1.5 text-[13px] font-semibold transition-all">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
            Orçamento
            @if($ordem->status_orcamento === 'pendente')
                <span class="ml-0.5 h-1.5 w-1.5 rounded-full bg-amber-400"></span>
            @endif
        </button>
        <button @click="tab = 'arquivos'; window.location.hash = 'tab-arquivos'"
                :class="tab === 'arquivos' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                class="flex items-center gap-1.5 rounded-lg px-4 py-1.5 text-[13px] font-semibold transition-all">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15.5 2H8.6c-.4 0-.8.2-1.1.5-.3.3-.5.7-.5 1.1v12.8c0 .4.2.8.5 1.1.3.3.7.5 1.1.5h9.8c.4 0 .8-.2 1.1-.5.3-.3.5-.7.5-1.1V6.5L15.5 2z"/>
                <polyline points="15 2 15 7 20 7"/>
            </svg>
            Arquivos
            @if($ordem->arquivos->count() > 0)
                <span class="ml-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-blue-100 px-1 text-[10px] font-bold text-blue-700">
                    {{ $ordem->arquivos->count() }}
                </span>
            @endif
        </button>
    </div>

    {{-- ── TAB: Detalhes ──────────────────────────────────────────────────────── --}}
    <div x-show="tab === 'detalhes'" x-cloak>
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[1fr_320px]">

            <div class="space-y-5">

                {{-- Cliente --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
                        <h2 class="text-[13.5px] font-bold text-slate-900">Cliente</h2>
                        <a href="{{ route('app.clientes.show', $ordem->cliente) }}" class="text-[12px] font-medium text-blue-600 hover:text-blue-700">Ver perfil →</a>
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
                            <p class="text-[13px] leading-relaxed text-slate-800 whitespace-pre-line">{{ $ordem->problema_relatado ?: '—' }}</p>
                        </div>
                        <div class="rounded-xl bg-blue-50 p-4">
                            <p class="mb-1.5 text-[10.5px] font-semibold uppercase tracking-wider text-blue-500">Diagnóstico técnico</p>
                            <p class="text-[13px] leading-relaxed text-slate-800 whitespace-pre-line">{{ $ordem->diagnostico ?: 'Aguardando diagnóstico.' }}</p>
                        </div>
                    </div>
                    @if($ordem->solucao)
                    <div class="border-t border-slate-100 px-5 pb-5">
                        <div class="rounded-xl bg-emerald-50 p-4">
                            <p class="mb-1.5 text-[10.5px] font-semibold uppercase tracking-wider text-emerald-600">Solução aplicada</p>
                            <p class="text-[13px] leading-relaxed text-slate-800 whitespace-pre-line">{{ $ordem->solucao }}</p>
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

            </div>{{-- /left col --}}

            {{-- ── Coluna direita ──────────────────────────────────────────── --}}
            <div class="space-y-5">

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
                            <textarea name="observacao_status" rows="2" placeholder="Ex.: Cliente notificado via WhatsApp."
                                      class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"></textarea>
                        </div>
                        <button type="submit" class="w-full rounded-xl bg-slate-900 py-2.5 text-[13px] font-bold text-white transition hover:bg-slate-800">Atualizar Status</button>
                    </form>
                </div>

                {{-- Resumo financeiro --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-100 px-5 py-3.5">
                        <h2 class="text-[13.5px] font-bold text-slate-900">Resumo financeiro</h2>
                    </div>
                    <div class="space-y-2.5 p-5 text-[13px]">
                        <div class="flex justify-between"><span class="text-slate-500">Serviços</span><span class="font-medium text-slate-800">R$ {{ number_format($ordem->valor_servico, 2, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Peças</span><span class="font-medium text-slate-800">R$ {{ number_format($ordem->valor_pecas, 2, ',', '.') }}</span></div>
                        @if($ordem->desconto > 0)
                        <div class="flex justify-between"><span class="text-slate-500">Desconto</span><span class="font-medium text-red-600">− R$ {{ number_format($ordem->desconto, 2, ',', '.') }}</span></div>
                        @endif
                        <div class="border-t border-slate-100 pt-2.5">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900">Total</span>
                                <span class="text-[20px] font-bold text-slate-900">R$ {{ number_format($ordem->total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
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
                    <div class="space-y-3 p-4">
                        <div class="flex items-center justify-between rounded-xl border border-blue-200 bg-white px-3.5 py-2.5">
                            <div>
                                <p class="mb-0.5 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Código de acesso</p>
                                <span class="font-mono text-[15px] font-bold tracking-widest text-slate-900">{{ $ordem->token }}</span>
                            </div>
                            <button @click="navigator.clipboard.writeText('{{ $ordem->token }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="ml-3 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600">
                                <svg x-show="!copied" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                <svg x-show="copied" class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </div>
                        <a href="{{ route('portal.token', $ordem->token) }}" target="_blank"
                           class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-2.5 text-[13px] font-bold text-white shadow-sm shadow-blue-500/20 transition hover:bg-blue-700 active:scale-[0.98]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Abrir portal do cliente
                        </a>
                        @if($ordem->cliente?->telefone)
                        @php $wa = 'https://wa.me/55'.preg_replace('/\D/','',$ordem->cliente->telefone).'?text='.urlencode('Olá! Acesse o portal para acompanhar sua OS: '.route('portal.token', $ordem->token)); @endphp
                        <a href="{{ $wa }}" target="_blank" rel="noopener"
                           class="flex w-full items-center justify-center gap-2 rounded-xl border border-[#25d366]/40 bg-[#25d366]/10 py-2.5 text-[13px] font-semibold text-[#128c4e] transition hover:bg-[#25d366]/15">
                            <svg class="h-4 w-4 text-[#25d366]" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                            Enviar via WhatsApp
                        </a>
                        @endif
                    </div>
                </div>
                @endif

            </div>{{-- /right col --}}
        </div>
    </div>{{-- /tab detalhes --}}

    {{-- ── TAB: Orçamento ────────────────────────────────────────────────────── --}}
    <div x-show="tab === 'orcamento'" x-cloak id="tab-orcamento">
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[1fr_320px]">
            <div class="space-y-5">

                {{-- Status atual do orçamento --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-100 px-5 py-3.5">
                        <h2 class="text-[13.5px] font-bold text-slate-900">Status do Orçamento</h2>
                    </div>
                    <div class="p-5">
                        @php
                            $orcConfig = match($ordem->status_orcamento) {
                                'aprovado' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'badge' => 'bg-emerald-100 text-emerald-700', 'icon_color' => 'text-emerald-600', 'label' => 'Aprovado'],
                                'recusado' => ['bg' => 'bg-red-50',     'border' => 'border-red-200',     'text' => 'text-red-800',     'badge' => 'bg-red-100 text-red-700',     'icon_color' => 'text-red-500',     'label' => 'Recusado'],
                                default    => ['bg' => 'bg-amber-50',   'border' => 'border-amber-200',   'text' => 'text-amber-800',   'badge' => 'bg-amber-100 text-amber-700', 'icon_color' => 'text-amber-600',   'label' => 'Aguardando aprovação'],
                            };
                        @endphp
                        <div class="flex items-center gap-4 rounded-2xl border {{ $orcConfig['border'] }} {{ $orcConfig['bg'] }} px-5 py-4">
                            @if($ordem->status_orcamento === 'aprovado')
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100"><svg class="h-6 w-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                            @elseif($ordem->status_orcamento === 'recusado')
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100"><svg class="h-6 w-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></div>
                            @else
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-100"><svg class="h-6 w-6 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
                            @endif
                            <div>
                                <p class="text-[16px] font-bold {{ $orcConfig['text'] }}">{{ $orcConfig['label'] }}</p>
                                <p class="mt-0.5 text-[12.5px] {{ $orcConfig['text'] }} opacity-70">
                                    @if($ordem->status_orcamento === 'aprovado') Cliente autorizou a execução do serviço.
                                    @elseif($ordem->status_orcamento === 'recusado') Cliente não autorizou. Entrar em contacto.
                                    @else Aguardando resposta do cliente via portal ou presencialmente.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detalhamento dos valores --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-100 px-5 py-3.5">
                        <h2 class="text-[13.5px] font-bold text-slate-900">Detalhamento dos valores</h2>
                    </div>
                    <div class="p-5">
                        <div class="space-y-3 text-[13px]">
                            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100">
                                        <svg class="h-3.5 w-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                    </div>
                                    <span class="font-medium text-slate-700">Mão de obra / Serviços</span>
                                </div>
                                <span class="font-semibold tabular-nums text-slate-900">R$ {{ number_format($ordem->valor_servico, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100">
                                        <svg class="h-3.5 w-3.5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                    </div>
                                    <span class="font-medium text-slate-700">Peças / Componentes</span>
                                </div>
                                <span class="font-semibold tabular-nums text-slate-900">R$ {{ number_format($ordem->valor_pecas, 2, ',', '.') }}</span>
                            </div>
                            @if($ordem->desconto > 0)
                            <div class="flex items-center justify-between rounded-xl bg-red-50 px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-100">
                                        <svg class="h-3.5 w-3.5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                                    </div>
                                    <span class="font-medium text-red-600">Desconto</span>
                                </div>
                                <span class="font-semibold tabular-nums text-red-600">− R$ {{ number_format($ordem->desconto, 2, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="flex items-center justify-between rounded-2xl bg-slate-900 px-5 py-4">
                                <span class="text-[14px] font-bold text-white">Total do Orçamento</span>
                                <span class="text-[22px] font-black text-white tabular-nums">R$ {{ number_format($ordem->total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Coluna direita: ações de orçamento --}}
            <div class="space-y-5">

                {{-- Alterar status manualmente --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    <div class="border-b border-slate-100 px-5 py-3.5">
                        <h2 class="text-[13.5px] font-bold text-slate-900">Alterar status do orçamento</h2>
                    </div>
                    <form action="{{ route('app.os.update', $ordem) }}" method="POST" class="space-y-3 p-5">
                        @csrf @method('PUT')
                        {{-- manter campos obrigatórios do update --}}
                        <input type="hidden" name="status" value="{{ $ordem->status }}">
                        <input type="hidden" name="problema_relatado" value="{{ $ordem->problema_relatado }}">

                        <div class="space-y-2">
                            @foreach(['pendente' => ['Pendente', 'bg-amber-50 border-amber-200 text-amber-700 hover:bg-amber-100'], 'aprovado' => ['Aprovado pelo cliente', 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100'], 'recusado' => ['Recusado pelo cliente', 'bg-red-50 border-red-200 text-red-600 hover:bg-red-100']] as $val => [$lbl, $cls])
                            <label class="flex cursor-pointer items-center gap-3 rounded-xl border {{ $cls }} px-4 py-3 transition-colors">
                                <input type="radio" name="status_orcamento" value="{{ $val }}"
                                       @checked($ordem->status_orcamento === $val || (! $ordem->status_orcamento && $val === 'pendente'))
                                       class="accent-blue-600">
                                <span class="text-[13px] font-semibold">{{ $lbl }}</span>
                            </label>
                            @endforeach
                        </div>
                        <button type="submit" class="w-full rounded-xl bg-slate-900 py-2.5 text-[13px] font-bold text-white transition hover:bg-slate-800">
                            Salvar status
                        </button>
                    </form>
                </div>

                {{-- Link do portal para aprovação --}}
                @if($ordem->token)
                <div class="overflow-hidden rounded-2xl border border-blue-100 bg-blue-50 px-5 py-4">
                    <p class="mb-1.5 text-[12px] font-bold text-blue-900">Link de aprovação no portal</p>
                    <p class="mb-3 text-[11.5px] text-blue-700 leading-relaxed">O cliente pode aprovar ou recusar o orçamento diretamente no portal usando o código abaixo.</p>
                    <div class="flex items-center gap-2 rounded-xl border border-blue-200 bg-white px-3 py-2.5"
                         x-data="{ copied: false }">
                        <span class="flex-1 font-mono text-[12px] font-bold text-slate-900 tracking-widest">{{ $ordem->token }}</span>
                        <button @click="navigator.clipboard.writeText('{{ route('portal.token', $ordem->token) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition hover:bg-blue-100 hover:text-blue-600">
                            <svg x-show="!copied" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            <svg x-show="copied" class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>{{-- /tab orcamento --}}

    {{-- ── TAB: Arquivos ─────────────────────────────────────────────────────── --}}
    <div x-show="tab === 'arquivos'" x-cloak id="tab-arquivos">
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[1fr_320px]">

            {{-- Lista de arquivos --}}
            <div class="space-y-4">
                @if($ordem->arquivos->isEmpty())
                <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white py-16 text-center">
                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">
                        <svg class="h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15.5 2H8.6c-.4 0-.8.2-1.1.5-.3.3-.5.7-.5 1.1v12.8c0 .4.2.8.5 1.1.3.3.7.5 1.1.5h9.8c.4 0 .8-.2 1.1-.5.3-.3.5-.7.5-1.1V6.5L15.5 2z"/><polyline points="15 2 15 7 20 7"/></svg>
                    </div>
                    <p class="text-[14px] font-semibold text-slate-700">Nenhum arquivo anexado</p>
                    <p class="mt-1 text-[13px] text-slate-400">Use o painel ao lado para enviar documentos.</p>
                </div>
                @else
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-3.5">
                        <h2 class="text-[13.5px] font-bold text-slate-900">
                            {{ $ordem->arquivos->count() }} {{ $ordem->arquivos->count() === 1 ? 'arquivo' : 'arquivos' }} armazenados
                        </h2>
                    </div>
                    <ul class="divide-y divide-slate-100">
                        @foreach($ordem->arquivos as $arquivo)
                        @php
                            $iconColor = match($arquivo->tipo) {
                                'os_assinada'  => 'bg-emerald-100 text-emerald-600',
                                'foto_entrada', 'foto_saida' => 'bg-blue-100 text-blue-600',
                                'orcamento'    => 'bg-amber-100 text-amber-600',
                                'laudo'        => 'bg-indigo-100 text-indigo-600',
                                'nota_fiscal'  => 'bg-purple-100 text-purple-600',
                                default        => 'bg-slate-100 text-slate-500',
                            };
                        @endphp
                        <li class="group flex items-center gap-3.5 px-5 py-3.5 transition hover:bg-slate-50/70">
                            {{-- Ícone do tipo --}}
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $iconColor }}">
                                @if($arquivo->isImagem())
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                @elseif($arquivo->isPdf())
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                @else
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15.5 2H8.6c-.4 0-.8.2-1.1.5-.3.3-.5.7-.5 1.1v12.8c0 .4.2.8.5 1.1.3.3.7.5 1.1.5h9.8c.4 0 .8-.2 1.1-.5.3-.3.5-.7.5-1.1V6.5L15.5 2z"/><polyline points="15 2 15 7 20 7"/></svg>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-[13px] font-semibold text-slate-800">{{ $arquivo->nome_original }}</p>
                                <div class="mt-0.5 flex flex-wrap items-center gap-2">
                                    <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[10.5px] font-semibold text-slate-500">
                                        {{ $tipos[$arquivo->tipo]['label'] ?? $arquivo->tipo }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 tabular-nums">{{ $arquivo->tamanho_formatado }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $arquivo->created_at->format('d/m/Y H:i') }}</span>
                                    @if($arquivo->usuario)
                                        <span class="text-[11px] text-slate-400">· por {{ $arquivo->usuario->name }}</span>
                                    @endif
                                </div>
                                @if($arquivo->descricao)
                                    <p class="mt-0.5 text-[11.5px] text-slate-500">{{ $arquivo->descricao }}</p>
                                @endif
                            </div>

                            {{-- Ações --}}
                            <div class="flex items-center gap-0.5 opacity-0 transition-opacity group-hover:opacity-100">
                                <a href="{{ route('app.os.arquivos.download', [$ordem, $arquivo]) }}"
                                   title="Descarregar"
                                   class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition hover:bg-blue-100 hover:text-blue-600">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </a>
                                <form method="POST" action="{{ route('app.os.arquivos.destroy', [$ordem, $arquivo]) }}"
                                      onsubmit="return confirm('Remover o arquivo \'{{ $arquivo->nome_original }}\'?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Remover"
                                            class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition hover:bg-red-100 hover:text-red-600">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
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
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-3.5">
                        <h2 class="text-[13.5px] font-bold text-slate-900">Adicionar arquivo</h2>
                        <p class="mt-0.5 text-[11.5px] text-slate-400">Máx. 20 MB · PDF, imagens, documentos</p>
                    </div>
                    <form action="{{ route('app.os.arquivos.store', $ordem) }}" method="POST"
                          enctype="multipart/form-data" class="space-y-4 p-5">
                        @csrf

                        {{-- Seletor de tipo --}}
                        <div>
                            <label class="mb-1.5 block text-[12px] font-semibold text-slate-600">Tipo do documento <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="tipo" required class="h-9 w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 pl-3 pr-8 text-[13px] text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                    @foreach($tipos as $key => $cfg)
                                        <option value="{{ $key }}">{{ $cfg['label'] }}</option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>

                        {{-- Upload field --}}
                        <div x-data="{ name: '' }">
                            <label class="mb-1.5 block text-[12px] font-semibold text-slate-600">Arquivo <span class="text-red-500">*</span></label>
                            <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center transition hover:border-blue-400 hover:bg-blue-50/30"
                                   :class="name ? 'border-blue-400 bg-blue-50/20' : ''">
                                <svg class="h-8 w-8 text-slate-400" :class="name ? 'text-blue-500' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                                <span x-text="name ? name : 'Clique ou arraste o ficheiro aqui'" class="text-[12.5px] font-medium" :class="name ? 'text-blue-700' : 'text-slate-500'"></span>
                                <input type="file" name="arquivo" class="hidden" required
                                       @change="name = $event.target.files[0]?.name ?? ''">
                            </label>
                        </div>

                        {{-- Descrição opcional --}}
                        <div>
                            <label class="mb-1.5 block text-[12px] font-semibold text-slate-600">Descrição <span class="text-[11px] font-normal text-slate-400">(opcional)</span></label>
                            <input type="text" name="descricao" maxlength="255"
                                   placeholder="Ex.: OS assinada pelo cliente na entrega"
                                   class="h-9 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        </div>

                        <button type="submit"
                                class="w-full rounded-xl bg-blue-600 py-2.5 text-[13px] font-bold text-white transition hover:bg-blue-700 active:scale-[0.99]">
                            Enviar arquivo
                        </button>
                    </form>
                </div>

                {{-- Info sobre armazenamento --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                        <div>
                            <p class="text-[12.5px] font-semibold text-slate-700">Armazenamento seguro</p>
                            <p class="mt-1 text-[11.5px] leading-relaxed text-slate-500">
                                Os arquivos ficam em armazenamento privado (não público) e servem como prova documental em caso de disputas jurídicas. Guarde sempre a OS assinada pelo cliente.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>{{-- /tab arquivos --}}

</div>{{-- /x-data tabs --}}

@endsection
