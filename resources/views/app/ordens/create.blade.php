@extends('layouts.app')
@section('title', 'Registrar Entrada')

@section('breadcrumbs')
@include('layouts.partials.breadcrumbs', ['items' => [
    ['label' => 'Ordens de Serviço', 'href' => route('app.os.index')],
    ['label' => 'Registrar Entrada'],
]])
@endsection

@php
$tipos = [
    ['Notebook',   'M2 6h20v12H2z M1 18h22 M8 21h8 M12 18v3'],
    ['Celular',    'M7 2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z M12 18h.01'],
    ['PC',         'M8 3h8 M4 7h16 M6 7l2 13h8l2-13 M12 10v4'],
    ['Impressora', 'M6 9V2h12v7 M4 9h16a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1h-2v4H6v-4H4a1 1 0 0 1-1-1v-6a1 1 0 0 1 1-1z'],
    ['Monitor',    'M2 3h20v14H2z M8 21h8 M12 17v4'],
    ['Tablet',     'M5 2h14a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z M12 18h.01'],
    ['TV',         'M2 7h20v15H2z M17 2l-5 5-5-5'],
    ['Videogame',  'M2 8h20v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8z M6 12h4 M8 10v4 M15 12h.01 M18 12h.01'],
    ['Outro',      'M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z M9 9a3 3 0 1 1 5 2.83c-.5.66-1 1.17-1 2.17 M12 17h.01'],
];
$formas = [
    ['balcao',   'Balcão',   'M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z M9 22V12h6v10'],
    ['coleta',   'Coleta',   'M1 3h15v13H1z M16 8l4 2v5h-4 M5.5 18.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z M18.5 18.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z'],
    ['motoboy',  'Motoboy',  'M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v9a2 2 0 0 1-2 2h-2 M9 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4z M17 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4z'],
    ['correios', 'Correios', 'M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z M3.3 7l8.7 5 8.7-5 M12 22V12'],
    ['outro',    'Outro',    'M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z M9 9a3 3 0 1 1 5 2.83c-.5.66-1 1.17-1 2.17 M12 17h.01'],
];
@endphp

@section('content')
<script>
window.__osClientes = @json($clientesData);
window.__osOld = {
    cpf:     @json(old('cpf_cnpj', '')),
    nome:    @json(old('nome', '')),
    tel:     @json(old('telefone', '')),
    email:   @json(old('email', '')),
    nasc:    @json(old('data_nascimento', '')),
    cep:     @json(old('cep', '')),
    rua:     @json(old('endereco', '')),
    num:     @json(old('numero', '')),
    comp:    @json(old('complemento', '')),
    bairro:  @json(old('bairro', '')),
    cidade:  @json(old('cidade', '')),
    uf:      @json(old('estado', '')),
    tipo:    @json(old('equipamento_tipo', '')),
    marca:   @json(old('equipamento_marca', '')),
    modelo:  @json(old('equipamento_modelo', '')),
    defeito: @json(old('problema_relatado', '')),
    endOpen: {{ (old('endereco') || old('cep')) ? 'true' : 'false' }},
};
</script>

<div x-data="osForm()" class="mx-auto max-w-[1080px]">

{{-- HEADER --}}
<div class="mb-7 flex items-center gap-4">
    <a href="{{ route('app.os.index') }}"
       class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m12 19-7-7 7-7M19 12H5"/></svg>
    </a>
    <div class="flex-1">
        <h1 class="text-[20px] font-extrabold tracking-tight text-slate-900">Registrar Entrada</h1>
        <p class="mt-0.5 text-[12.5px] text-slate-400">Preencha os dados para abrir a ordem de serviço.</p>
    </div>
</div>

@if($errors->any())
<div class="mb-5 flex gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4">
    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
    <div><p class="text-[13px] font-bold text-red-800">Corrija os erros</p>
    <ul class="mt-1 space-y-0.5 text-[12.5px] text-red-700">@foreach($errors->all() as $e)<li>· {{ $e }}</li>@endforeach</ul></div>
</div>
@endif

<form action="{{ route('app.os.store') }}" method="POST" enctype="multipart/form-data"
      @submit="busy = true" class="grid gap-5 lg:grid-cols-[1fr_296px]">
@csrf

{{-- ══ FORMULÁRIO (esquerda) ══════════════════════════════ --}}
<div class="space-y-4">

{{-- ── 1 · CLIENTE ─────────────────────────────────────────── --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <div class="flex items-center gap-3">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-[11px] font-black text-white">1</span>
            <h2 class="text-[14px] font-bold text-slate-900">Cliente</h2>
        </div>
        <template x-if="found">
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-[10.5px] font-bold text-emerald-700">
                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
                Cadastro encontrado
            </span>
        </template>
        <template x-if="!found && (nome || cpf)">
            <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-1 text-[10.5px] font-bold text-blue-700">
                + Novo cliente
            </span>
        </template>
    </div>
    <div class="p-6 space-y-4">

        {{-- BUSCA --}}
        <div class="relative" @click.outside="buscaAberta=false">
            <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Pesquisar cliente existente</label>
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" x-model="busca"
                       @input="onBusca($event.target.value)"
                       @focus="if(busca.length>=1) buscaAberta=true"
                       @keydown.escape="buscaAberta=false"
                       @keydown.arrow-down.prevent="focusResultado(0)"
                       placeholder="Nome, CPF ou telefone…"
                       autocomplete="off"
                       :disabled="!!found"
                       class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-10 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/15 disabled:cursor-not-allowed disabled:opacity-60">
                <button x-show="busca && !found" type="button" @click="busca='';buscaAberta=false;resultados=[]"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 rounded-lg p-1 text-slate-400 hover:text-slate-600" style="display:none">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Dropdown --}}
            <div x-show="buscaAberta"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute left-0 right-0 top-[calc(100%+4px)] z-50 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl"
                 style="display:none">

                {{-- Resultados --}}
                <template x-if="resultados.length > 0">
                    <div>
                        <p class="px-4 py-2 text-[10.5px] font-black uppercase tracking-widest text-slate-400">
                            <span x-text="resultados.length"></span> resultado<span x-show="resultados.length!==1">s</span>
                        </p>
                        <ul class="max-h-64 overflow-y-auto pb-1">
                            <template x-for="(c, i) in resultados" :key="c.id">
                                <li>
                                    <button type="button"
                                            :id="'res-'+i"
                                            @click="selecionarCliente(c)"
                                            @keydown.arrow-down.prevent="focusResultado(i+1)"
                                            @keydown.arrow-up.prevent="focusResultado(i-1)"
                                            class="flex w-full items-center gap-3 px-4 py-3 text-left transition hover:bg-slate-50 focus:bg-blue-50 focus:outline-none">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-indigo-200 text-[11px] font-bold text-blue-700"
                                             x-text="c.iniciais"></div>
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-[13px] font-semibold text-slate-900" x-text="c.nome"></p>
                                            <p class="text-[11.5px] text-slate-400" x-text="[c.cpf_cnpj, c.telefone].filter(Boolean).join(' · ')"></p>
                                        </div>
                                        <svg class="h-4 w-4 shrink-0 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>

                {{-- Sem resultados --}}
                <template x-if="resultados.length === 0">
                    <div class="flex items-center gap-3 px-4 py-4">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100">
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold text-slate-700">Nenhum cadastro encontrado</p>
                            <p class="text-[11.5px] text-slate-400">Preencha os dados abaixo para cadastrar.</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Card cliente selecionado --}}
        <div x-show="found"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-[0.98]"
             x-transition:enter-end="opacity-100 scale-100"
             class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50/60 px-4 py-3"
             style="display:none">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 text-[12px] font-bold text-white"
                 x-text="found?.iniciais||'?'"></div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-[13.5px] font-bold text-slate-900" x-text="found?.nome"></p>
                <p class="text-[12px] text-slate-500" x-text="[found?.cpf_cnpj, found?.telefone].filter(Boolean).join(' · ')"></p>
            </div>
            <button type="button" @click="limparCliente()"
                    class="shrink-0 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-500 transition hover:bg-slate-50">
                trocar
            </button>
        </div>

        {{-- Campos --}}
        <div class="grid gap-3.5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nome completo <span class="text-red-500">*</span></label>
                <input type="text" name="nome" x-ref="nome" x-model="nome"
                       placeholder="Nome completo"
                       class="h-10 w-full rounded-xl border px-3 text-[13px] placeholder:text-slate-400 outline-none transition {{ $errors->has('nome') ? 'border-red-400 bg-red-50/30' : 'border-slate-200 bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15' }}">
                @error('nome')<p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">CPF</label>
                <input type="text" name="cpf_cnpj" x-model="cpf"
                       @input="cpf=maskCpf($event.target.value)"
                       placeholder="000.000.000-00" maxlength="14"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 font-mono text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
            </div>
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Telefone / WhatsApp <span class="text-red-500">*</span></label>
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.41 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6.16 6.16l1.27-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <input type="text" name="telefone" x-model="tel" @input="tel=maskTel($event.target.value)"
                           placeholder="(81) 99999-0000" maxlength="15"
                           class="h-10 w-full rounded-xl border pl-8 pr-3 text-[13px] placeholder:text-slate-400 outline-none transition {{ $errors->has('telefone') ? 'border-red-400 bg-red-50/30' : 'border-slate-200 bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15' }}">
                </div>
                @error('telefone')<p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Data de nascimento
                    <span class="ml-1 text-[10.5px] font-normal text-slate-400">senha do portal</span>
                </label>
                <input type="date" name="data_nascimento" x-model="nasc"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">E-mail</label>
                <input type="email" name="email" x-model="email" placeholder="email@exemplo.com"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
            </div>
        </div>

        {{-- Endereço --}}
        <div>
            <button type="button" @click="endOpen=!endOpen"
                    class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-2.5 transition hover:border-slate-300">
                <span class="flex items-center gap-2 text-[12.5px] font-semibold text-slate-600">
                    <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Endereço
                </span>
                <div class="flex items-center gap-2">
                    <span x-show="cidade" class="max-w-[140px] truncate text-[11.5px] text-slate-400" x-text="[rua,cidade].filter(Boolean).join(', ')" style="display:none"></span>
                    <svg :class="endOpen&&'rotate-180'" class="h-3.5 w-3.5 text-slate-400 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                </div>
            </button>
            <div x-show="endOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-1" class="mt-2.5" style="display:none">
                <div class="grid gap-3 rounded-xl border border-slate-200 bg-slate-50/50 p-4 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">CEP</label>
                        <div class="relative">
                            <input type="text" name="cep" x-model="cep"
                                   @input="cep=$event.target.value.replace(/\D/g,'').slice(0,8).replace(/(\d{5})(\d+)/,'$1-$2')"
                                   @blur="fetchCep(cep)" placeholder="00000-000" maxlength="9"
                                   class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                            <svg x-show="cepBusy" class="pointer-events-none absolute right-2.5 top-1/2 h-4 w-4 -translate-y-1/2 animate-spin text-blue-500" viewBox="0 0 24 24" fill="none" style="display:none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Logradouro</label>
                        <input type="text" name="endereco" x-model="rua" placeholder="Rua, Avenida…" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                    </div>
                    <div class="sm:col-span-1">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Nº</label>
                        <input type="text" name="numero" x-model="num" placeholder="123" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Complemento</label>
                        <input type="text" name="complemento" x-model="comp" placeholder="Apto, Sala…" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Bairro</label>
                        <input type="text" name="bairro" x-model="bairro" placeholder="Bairro" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                    </div>
                    <div class="sm:col-span-4">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">Cidade</label>
                        <input type="text" name="cidade" x-model="cidade" placeholder="Cidade" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-[11px] font-semibold text-slate-500">UF</label>
                        <input type="text" name="estado" x-model="uf" placeholder="PE" maxlength="2" class="h-9 w-full rounded-lg border border-slate-200 bg-white px-3 text-center text-[13px] uppercase placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── 2 · EQUIPAMENTO ──────────────────────────────────────── --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <div class="flex items-center gap-3">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-[11px] font-black text-white">2</span>
            <h2 class="text-[14px] font-bold text-slate-900">Equipamento</h2>
        </div>
        <div x-show="tipo" class="flex items-center gap-1.5 rounded-full bg-slate-900 px-3 py-1" style="display:none">
            <svg class="h-3.5 w-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
            <span class="text-[11.5px] font-semibold text-white" x-text="tipo"></span>
        </div>
    </div>
    <div class="p-6 space-y-5">

        {{-- TIPO DE EQUIPAMENTO — usa has-[:checked]: CSS puro --}}
        <div>
            <label class="mb-3 block text-[12.5px] font-semibold text-slate-700">
                Tipo de equipamento <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-3 gap-2.5 sm:grid-cols-5">
                @foreach($tipos as [$tipo, $paths])
                <label class="relative flex cursor-pointer flex-col items-center gap-2.5 rounded-2xl border-2 border-slate-200 bg-white px-2 py-4 text-center text-slate-500 transition-all duration-150 hover:border-slate-300 hover:bg-slate-50 active:scale-[0.96]
                              has-[:checked]:border-slate-900 has-[:checked]:bg-slate-900 has-[:checked]:text-white">

                    <input type="radio" name="equipamento_tipo" value="{{ $tipo }}"
                           @change="tipo = '{{ $tipo }}'"
                           class="sr-only"
                           @checked(old('equipamento_tipo') === $tipo)>

                    {{-- Checkmark --}}
                    <div class="absolute right-2 top-2 h-4 w-4 rounded-full bg-transparent transition has-[:checked]:bg-white/25 [label:has(:checked)_&]:opacity-100 opacity-0 flex items-center justify-center pointer-events-none">
                        <svg class="h-2.5 w-2.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg>
                    </div>

                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        @foreach(explode(' M', $paths) as $si => $seg)
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $si === 0 ? $seg : 'M'.$seg }}"/>
                        @endforeach
                    </svg>
                    <span class="text-[11.5px] font-bold leading-tight">{{ $tipo }}</span>
                </label>
                @endforeach
            </div>
            @error('equipamento_tipo')<p class="mt-1.5 text-[12px] text-red-500">{{ $message }}</p>@enderror
        </div>

        {{-- Campos do equipamento --}}
        <div class="grid gap-3.5 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Marca</label>
                <input type="text" name="equipamento_marca" x-model="marca" value="{{ old('equipamento_marca') }}"
                       placeholder="Dell, Samsung, Apple…"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
            </div>
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Modelo</label>
                <input type="text" name="equipamento_modelo" x-model="modelo" value="{{ old('equipamento_modelo') }}"
                       placeholder="Galaxy S23, MacBook Air…"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
            </div>
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nº de série</label>
                <input type="text" name="equipamento_serie" value="{{ old('equipamento_serie') }}"
                       placeholder="Opcional"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 font-mono text-[13px] tracking-wider placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
            </div>
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Estado físico</label>
                <input type="text" name="estado_fisico" value="{{ old('estado_fisico') }}"
                       placeholder="Sem avarias, tela trincada…"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Acessórios recebidos</label>
                <input type="text" name="acessorios" value="{{ old('acessorios') }}"
                       placeholder="Carregador, capa, caixa original…"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
            </div>
        </div>

        {{-- FORMA DE ENTRADA — também has-[:checked]: --}}
        <div>
            <label class="mb-3 block text-[12.5px] font-semibold text-slate-700">Como chegou? <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-5 gap-2">
                @foreach($formas as [$val, $lbl, $paths])
                <label class="flex cursor-pointer flex-col items-center gap-2 rounded-xl border-2 border-slate-200 bg-white py-3.5 text-center text-slate-500 transition-all duration-150 hover:border-slate-300 active:scale-[0.96]
                              has-[:checked]:border-slate-900 has-[:checked]:bg-slate-900 has-[:checked]:text-white">
                    <input type="radio" name="forma_entrada" value="{{ $val }}" class="sr-only"
                           @checked(old('forma_entrada', 'balcao') === $val)>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        @foreach(explode(' M', $paths) as $si => $seg)
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $si === 0 ? $seg : 'M'.$seg }}"/>
                        @endforeach
                    </svg>
                    <span class="text-[10.5px] font-bold">{{ $lbl }}</span>
                </label>
                @endforeach
            </div>
            @error('forma_entrada')<p class="mt-1.5 text-[12px] text-red-500">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- ── 3 · DEFEITO E OS ─────────────────────────────────────── --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
    <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-[11px] font-black text-white">3</span>
        <h2 class="text-[14px] font-bold text-slate-900">Defeito e OS</h2>
    </div>
    <div class="p-6 space-y-4">
        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label class="text-[12.5px] font-semibold text-slate-700">Defeito relatado <span class="text-red-500">*</span></label>
                <span class="text-[11px] tabular-nums" :class="chars>400?'font-semibold text-amber-500':'text-slate-400'" x-text="chars+'/1000'"></span>
            </div>
            <textarea name="problema_relatado" rows="4" maxlength="1000" x-model="defeito"
                      placeholder="Descreva o problema exatamente como o cliente relatou…"
                      class="w-full resize-none rounded-xl border px-3.5 py-3 text-[13px] leading-relaxed placeholder:text-slate-400 outline-none transition {{ $errors->has('problema_relatado') ? 'border-red-400 bg-red-50/30' : 'border-slate-200 bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15' }}">{{ old('problema_relatado') }}</textarea>
            @error('problema_relatado')<p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">
                Observações internas
                <span class="text-[11px] font-normal text-slate-400 ml-1">— não visível ao cliente</span>
            </label>
            <textarea name="observacoes" rows="2" placeholder="Notas internas, checklist…"
                      class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">{{ old('observacoes') }}</textarea>
        </div>
        <div class="grid gap-3.5 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Técnico responsável</label>
                <div class="relative">
                    <select name="tecnico_id" class="h-10 w-full appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
                        <option value="">A definir</option>
                        @foreach($tecnicos as $t)
                        <option value="{{ $t->id }}" @selected(old('tecnico_id')==$t->id)>{{ $t->name }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Previsão de entrega</label>
                <input type="date" name="previsao_entrega" value="{{ old('previsao_entrega') }}"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15">
            </div>
        </div>
    </div>
</div>

{{-- ── 4 · FOTOS ─────────────────────────────────────────────── --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <div class="flex items-center gap-3">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-200 text-[11px] font-black text-slate-500">4</span>
            <h2 class="text-[14px] font-bold text-slate-900">Fotos <span class="text-[12px] font-normal text-slate-400">— opcional</span></h2>
        </div>
        <span x-show="fotos.length>0" class="rounded-full bg-blue-100 px-2.5 py-1 text-[11.5px] font-bold text-blue-700" x-text="fotos.length+' foto'+(fotos.length!==1?'s':'')" style="display:none"></span>
    </div>
    <div class="p-6">
        <label class="flex cursor-pointer flex-col items-center gap-3 rounded-xl border-2 border-dashed py-10 text-center transition-all"
               :class="fotos.length>0?'border-blue-300 bg-blue-50/30':'border-slate-200 bg-slate-50/30 hover:border-slate-300 hover:bg-slate-50'">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl" :class="fotos.length>0?'bg-blue-100':'bg-slate-100'">
                <svg class="h-6 w-6" :class="fotos.length>0?'text-blue-500':'text-slate-400'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                </svg>
            </div>
            <div>
                <p class="text-[13px] font-semibold" :class="fotos.length>0?'text-blue-700':'text-slate-700'"
                   x-text="fotos.length>0?fotos.length+' foto'+(fotos.length!==1?'s')+' — clique para adicionar mais':'Clique ou arraste as fotos aqui'">Clique ou arraste as fotos aqui</p>
                <p class="mt-0.5 text-[11.5px] text-slate-400">JPG, PNG ou WEBP · Máx. 10 MB cada</p>
            </div>
            <input type="file" name="fotos[]" multiple accept="image/*" class="hidden" @change="addFotos($event)">
        </label>
        <div x-show="fotos.length>0" class="mt-3.5 grid grid-cols-4 gap-2 sm:grid-cols-6" style="display:none">
            <template x-for="(f,i) in fotos" :key="i">
                <div class="group relative aspect-square overflow-hidden rounded-xl bg-slate-100 ring-1 ring-black/[0.05]">
                    <img :src="f.url" :alt="f.name" class="h-full w-full object-cover transition group-hover:brightness-75">
                    <button type="button" @click="rmFoto(i)"
                            class="absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white opacity-0 shadow transition group-hover:opacity-100">
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
        </div>
    </div>
</div>

{{-- Submit mobile --}}
<div class="flex gap-3 pb-2 lg:hidden">
    <a href="{{ route('app.os.index') }}" class="flex h-11 flex-1 items-center justify-center rounded-xl border border-slate-200 bg-white text-[13px] font-semibold text-slate-700 transition hover:bg-slate-50">Cancelar</a>
    <button type="submit" :disabled="busy"
            class="flex h-11 flex-[2] items-center justify-center gap-2 rounded-xl bg-blue-600 text-[13px] font-bold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700 disabled:opacity-60">
        <svg x-show="!busy" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <svg x-show="busy" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" style="display:none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
        <span x-text="busy?'Salvando…':'Registrar Entrada'"></span>
    </button>
</div>

</div>{{-- /esquerda --}}

{{-- ══ PAINEL DIREITO (sticky) ════════════════════════════ --}}
<div class="hidden lg:block">
<div class="sticky top-[74px] space-y-4">

    {{-- Resumo ao vivo --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">
        <div class="border-b border-slate-100 px-5 py-3.5">
            <p class="text-[10.5px] font-black uppercase tracking-widest text-slate-400">Resumo da OS</p>
        </div>
        <div class="divide-y divide-slate-50 text-[12.5px]">

            <div class="flex items-center justify-between px-5 py-3">
                <span class="text-slate-500">Status inicial</span>
                <span class="flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[10.5px] font-semibold text-slate-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>Entrada
                </span>
            </div>

            <div class="px-5 py-3.5">
                <p class="mb-2 text-[10px] font-black uppercase tracking-widest text-slate-400">Cliente</p>
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-indigo-200 text-[11px] font-bold text-blue-700"
                         x-text="nome?nome.trim().split(/\s+/).filter(p=>p).slice(0,2).map(p=>p[0].toUpperCase()).join(''):'?'">?</div>
                    <div class="min-w-0">
                        <p class="truncate font-semibold text-slate-900" x-text="nome||'—'"></p>
                        <p class="truncate text-[11.5px] text-slate-400" x-text="tel||'—'"></p>
                    </div>
                </div>
                <div x-show="cpf.length>8" class="mt-2 flex justify-between rounded-lg bg-slate-50 px-3 py-1.5" style="display:none">
                    <span class="text-[10.5px] text-slate-400">CPF</span>
                    <span class="font-mono text-[11.5px] font-semibold text-slate-700" x-text="cpf"></span>
                </div>
            </div>

            <div class="px-5 py-3.5">
                <p class="mb-2 text-[10px] font-black uppercase tracking-widest text-slate-400">Equipamento</p>
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl ring-1 ring-black/[0.06]"
                         :class="tipo?'bg-slate-900':'bg-slate-100'">
                        <svg class="h-4 w-4" :class="tipo?'text-white':'text-slate-400'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900" x-text="tipo||'Não selecionado'"></p>
                        <p class="truncate text-[11.5px] text-slate-400" x-text="[marca,modelo].filter(Boolean).join(' ')||'—'"></p>
                    </div>
                </div>
            </div>

            <div class="space-y-2 px-5 py-3.5">
                <div x-show="defeito.trim()" class="rounded-lg bg-slate-50 p-2.5" style="display:none">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Defeito</p>
                    <p class="text-[11.5px] leading-relaxed text-slate-600 line-clamp-3" x-text="defeito"></p>
                </div>
                <div x-show="fotos.length>0" class="flex items-center justify-between" style="display:none">
                    <span class="text-slate-500">Fotos</span>
                    <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[11px] font-bold text-blue-700" x-text="fotos.length+' foto'+(fotos.length!==1?'s':'')"></span>
                </div>
            </div>

        </div>
    </div>

    {{-- CTA --}}
    <button type="submit" :disabled="busy"
            class="flex h-12 w-full items-center justify-center gap-2.5 rounded-2xl bg-blue-600 font-bold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 disabled:opacity-60 active:scale-[0.98]">
        <svg x-show="!busy" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <svg x-show="busy" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" style="display:none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
        <span x-text="busy?'Registrando…':'Registrar Entrada'"></span>
    </button>

    <a href="{{ route('app.os.index') }}" class="flex h-10 w-full items-center justify-center rounded-xl border border-slate-200 text-[13px] font-semibold text-slate-600 transition hover:bg-slate-50">Cancelar</a>

    <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3.5 text-[11.5px]">
        <p class="font-bold text-blue-900">Portal do cliente</p>
        <p class="mt-1 leading-relaxed text-blue-700">
            Login com <strong>CPF</strong> e <strong>data de nascimento</strong>. Mensagem de boas-vindas enviada automaticamente pelo WhatsApp após a entrada.
        </p>
    </div>

</div>
</div>{{-- /direita --}}

</form>
</div>
@endsection

@push('scripts')
<script>
function osForm() {
    const d = window.__osOld;
    return {
        clientes:    window.__osClientes,
        found:       null,
        busca:       '',
        resultados:  [],
        buscaAberta: false,
        cpf:     d.cpf,
        nome:    d.nome,
        tel:     d.tel,
        email:   d.email,
        nasc:    d.nasc,
        cep:     d.cep,
        rua:     d.rua,
        num:     d.num,
        comp:    d.comp,
        bairro:  d.bairro,
        cidade:  d.cidade,
        uf:      d.uf,
        tipo:    d.tipo,
        marca:   d.marca,
        modelo:  d.modelo,
        defeito: d.defeito,
        endOpen: d.endOpen,
        cepBusy: false,
        fotos:   [],
        busy:    false,

        get digits() { return this.cpf.replace(/\D/g, ''); },
        get chars()  { return this.defeito.length; },

        onBusca(v) {
            this.busca = v;
            const q = v.toLowerCase().replace(/\D/g, '') || v.toLowerCase();
            if (v.length < 1) { this.resultados = []; this.buscaAberta = false; return; }
            this.resultados = this.clientes.filter(c => {
                const digits = v.replace(/\D/g, '');
                if (digits.length >= 3 && c.cpf_limpo.includes(digits)) return true;
                if (digits.length >= 3 && c.telefone.replace(/\D/g,'').includes(digits)) return true;
                return c.nome.toLowerCase().includes(v.toLowerCase());
            }).slice(0, 8);
            this.buscaAberta = true;
        },

        selecionarCliente(c) {
            this.found = c;
            this.busca = c.nome;
            this.buscaAberta = false;
            this.fill(c);
            this.cpf = c.cpf_cnpj || '';
        },

        limparCliente() {
            this.found = null;
            this.busca = '';
            this.resultados = [];
            this.cpf = ''; this.nome = ''; this.tel = ''; this.email = '';
            this.nasc = ''; this.cep = ''; this.rua = ''; this.num = '';
            this.comp = ''; this.bairro = ''; this.cidade = ''; this.uf = '';
        },

        focusResultado(i) {
            const el = document.getElementById('res-' + i);
            if (el) el.focus();
        },

        maskCpf(v) {
            let n = v.replace(/\D/g, '').slice(0, 11);
            if (n.length > 9) return n.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, '$1.$2.$3-$4');
            if (n.length > 6) return n.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
            if (n.length > 3) return n.replace(/(\d{3})(\d+)/, '$1.$2');
            return n;
        },

        maskTel(v) {
            let n = v.replace(/\D/g, '').slice(0, 11);
            if (n.length > 10) return n.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            if (n.length > 6)  return n.replace(/(\d{2})(\d{4})(\d+)/,   '($1) $2-$3');
            if (n.length > 2)  return n.replace(/(\d{2})(\d+)/,          '($1) $2');
            return n;
        },

        fill(c) {
            this.nome   = c.nome             || '';
            this.tel    = c.telefone         || '';
            this.email  = c.email            || '';
            this.nasc   = c.data_nascimento  || '';
            this.cep    = c.cep              || '';
            this.rua    = c.endereco         || '';
            this.num    = c.numero           || '';
            this.comp   = c.complemento      || '';
            this.bairro = c.bairro           || '';
            this.cidade = c.cidade           || '';
            this.uf     = c.estado           || '';
            if (c.cep || c.endereco) this.endOpen = true;
        },

        async fetchCep(v) {
            const n = v.replace(/\D/g, '');
            if (n.length !== 8) return;
            this.cepBusy = true;
            try {
                const r = await fetch('https://viacep.com.br/ws/' + n + '/json/');
                const data = await r.json();
                if (!data.erro) {
                    this.rua    = data.logradouro || '';
                    this.bairro = data.bairro     || '';
                    this.cidade = data.localidade  || '';
                    this.uf     = data.uf          || '';
                    this.endOpen = true;
                }
            } catch (e) {}
            this.cepBusy = false;
        },

        addFotos(e) {
            Array.from(e.target.files).forEach(f => {
                this.fotos.push({ name: f.name, url: URL.createObjectURL(f), size: f.size });
            });
            e.target.value = '';
        },

        rmFoto(i) { this.fotos.splice(i, 1); },
    };
}
</script>
@endpush
