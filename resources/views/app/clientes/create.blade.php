@extends('layouts.app')
@section('title', 'Novo Cliente')

@section('breadcrumbs')
@include('layouts.partials.breadcrumbs', ['items' => [
    ['label' => 'Clientes', 'href' => route('app.clientes.index')],
    ['label' => 'Novo Cliente'],
]])
@endsection

@section('content')

<div
    x-data="clienteForm()"
    x-init="init()"
    class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_300px]"
>

{{-- ══════════════════════════════════════════
     COLUNA PRINCIPAL
══════════════════════════════════════════ --}}
<div class="space-y-5">

    {{-- Cabeçalho --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3.5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-600 shadow-md shadow-blue-600/20">
                <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-[20px] font-bold text-slate-900">Novo Cliente</h1>
                <p class="text-[12.5px] text-slate-500">Preencha os dados para cadastrar</p>
            </div>
        </div>
        <a href="{{ route('app.clientes.index') }}"
           class="flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-[12.5px] font-semibold text-slate-600 shadow-sm hover:bg-slate-50 transition-colors">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
            Voltar
        </a>
    </div>

    <form id="form-cliente" action="{{ route('app.clientes.store') }}" method="POST">
    @csrf

    {{-- ── 1. Identificação ── --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div>
                <h2 class="text-[14px] font-bold text-slate-900">Identificação</h2>
                <p class="text-[12px] text-slate-400">Nome, documento e data de nascimento</p>
            </div>
            <div class="ml-auto flex items-center gap-2">
                <button type="button" @click="tipo = 'pf'" :class="tipo === 'pf' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                        class="rounded-lg px-3 py-1 text-[11.5px] font-semibold transition-colors">
                    Pessoa Física
                </button>
                <button type="button" @click="tipo = 'pj'" :class="tipo === 'pj' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                        class="rounded-lg px-3 py-1 text-[11.5px] font-semibold transition-colors">
                    Pessoa Jurídica
                </button>
            </div>
        </div>

        <div class="grid gap-4 p-6 sm:grid-cols-2">
            {{-- Nome --}}
            <div class="sm:col-span-2">
                <label for="nome" class="mb-1.5 block text-[12px] font-semibold uppercase tracking-wide text-slate-500">
                    Nome <span x-text="tipo === 'pj' ? 'Empresarial' : 'Completo'"></span>
                    <span class="text-red-500">*</span>
                </label>
                <input id="nome" type="text" name="nome"
                       x-model="nome"
                       value="{{ old('nome') }}"
                       :placeholder="tipo === 'pj' ? 'Ex.: Tech Solutions Ltda' : 'Ex.: João da Silva'"
                       autofocus
                       class="h-10 w-full rounded-xl border px-3.5 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:bg-white focus:ring-2
                              {{ $errors->has('nome') ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 bg-slate-50 focus:border-blue-400 focus:ring-blue-100' }}">
                @error('nome')
                <p class="mt-1 flex items-center gap-1 text-[11.5px] text-red-600">
                    <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- CPF / CNPJ --}}
            <div>
                <label for="cpf_cnpj" class="mb-1.5 block text-[12px] font-semibold uppercase tracking-wide text-slate-500">
                    <span x-text="tipo === 'pj' ? 'CNPJ' : 'CPF'"></span>
                </label>
                <div class="relative">
                    <input id="cpf_cnpj" type="text" name="cpf_cnpj"
                           x-model="cpf"
                           @input="maskCpf($event)"
                           value="{{ old('cpf_cnpj') }}"
                           :placeholder="tipo === 'pj' ? '00.000.000/0001-00' : '000.000.000-00'"
                           :maxlength="tipo === 'pj' ? 18 : 14"
                           class="h-10 w-full rounded-xl border px-3.5 font-mono text-[13px] text-slate-900 placeholder-slate-400 outline-none transition focus:bg-white focus:ring-2
                                  {{ $errors->has('cpf_cnpj') ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 bg-slate-50 focus:border-blue-400 focus:ring-blue-100' }}">
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                        <svg x-show="cpf.length > 0" class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    </div>
                </div>
                @error('cpf_cnpj')
                <p class="mt-1 flex items-center gap-1 text-[11.5px] text-red-600">
                    <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Data de nascimento (PF) / Data de fundação (PJ) --}}
            <div>
                <label for="data_nascimento" class="mb-1.5 block text-[12px] font-semibold uppercase tracking-wide text-slate-500">
                    <span x-text="tipo === 'pj' ? 'Data de Fundação' : 'Data de Nascimento'"></span>
                    <span class="ml-1 text-[10.5px] font-normal normal-case text-slate-400">(necessário para o portal)</span>
                </label>
                <input id="data_nascimento" type="date" name="data_nascimento"
                       value="{{ old('data_nascimento') }}"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 text-[13.5px] text-slate-900 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
            </div>
        </div>
    </div>

    {{-- ── 2. Contato ── --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm mt-5">
        <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-green-50 text-green-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.6 19.79 19.79 0 0 1 1.61 5c-.11-1.18.8-2.18 2-2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 10.91A16 16 0 0 0 13.09 15.91l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 16.92z"/></svg>
            </div>
            <div>
                <h2 class="text-[14px] font-bold text-slate-900">Contato</h2>
                <p class="text-[12px] text-slate-400">Telefone e e-mail para comunicação</p>
            </div>
        </div>

        <div class="grid gap-4 p-6 sm:grid-cols-2">
            {{-- Telefone --}}
            <div>
                <label for="telefone" class="mb-1.5 block text-[12px] font-semibold uppercase tracking-wide text-slate-500">Telefone / WhatsApp</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                        <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.6 19.79 19.79 0 0 1 1.61 5c-.11-1.18.8-2.18 2-2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 10.91A16 16 0 0 0 13.09 15.91l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 16.92z"/></svg>
                    </span>
                    <input id="telefone" type="text" name="telefone"
                           x-model="telefone"
                           @input="maskTel($event)"
                           value="{{ old('telefone') }}"
                           placeholder="(11) 99999-9999"
                           maxlength="15"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-3.5 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                </div>
            </div>

            {{-- E-mail --}}
            <div>
                <label for="email" class="mb-1.5 block text-[12px] font-semibold uppercase tracking-wide text-slate-500">E-mail</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                        <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </span>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="joao@exemplo.com"
                           class="h-10 w-full rounded-xl border pl-9 pr-3.5 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:bg-white focus:ring-2
                                  {{ $errors->has('email') ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 bg-slate-50 focus:border-blue-400 focus:ring-blue-100' }}">
                </div>
                @error('email')
                <p class="mt-1 flex items-center gap-1 text-[11.5px] text-red-600">
                    <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>
        </div>
    </div>

    {{-- ── 3. Endereço ── --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm mt-5">
        <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
                <h2 class="text-[14px] font-bold text-slate-900">
                    Endereço
                    <span class="ml-1.5 text-[11px] font-normal text-slate-400">(opcional)</span>
                </h2>
                <p class="text-[12px] text-slate-400">Digite o CEP para preencher automaticamente</p>
            </div>
        </div>

        <div class="grid gap-4 p-6 sm:grid-cols-6">
            {{-- CEP --}}
            <div class="sm:col-span-2">
                <label for="cep" class="mb-1.5 block text-[12px] font-semibold uppercase tracking-wide text-slate-500">CEP</label>
                <div class="relative">
                    <input id="cep" type="text" name="cep"
                           x-model="cep"
                           @input="maskCep($event)"
                           @blur="buscarCep()"
                           value="{{ old('cep') }}"
                           placeholder="00000-000"
                           maxlength="9"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 pr-9 font-mono text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                    <div class="absolute inset-y-0 right-3 flex items-center">
                        <svg x-show="cepBusy" class="h-3.5 w-3.5 animate-spin text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <svg x-show="!cepBusy && cepOk" class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <svg x-show="!cepBusy && cepErro" class="h-3.5 w-3.5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </div>
                </div>
                <p x-show="cepErro" class="mt-1 text-[11.5px] text-red-600" style="display:none">CEP não encontrado.</p>
            </div>

            {{-- Logradouro --}}
            <div class="sm:col-span-4">
                <label for="endereco" class="mb-1.5 block text-[12px] font-semibold uppercase tracking-wide text-slate-500">Logradouro</label>
                <input id="endereco" type="text" name="endereco"
                       x-model="rua"
                       value="{{ old('endereco') }}"
                       placeholder="Rua, Avenida, Travessa..."
                       class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
            </div>

            {{-- Número --}}
            <div class="sm:col-span-1">
                <label for="numero" class="mb-1.5 block text-[12px] font-semibold uppercase tracking-wide text-slate-500">Nº</label>
                <input id="numero" type="text" name="numero"
                       value="{{ old('numero') }}"
                       placeholder="123"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
            </div>

            {{-- Complemento --}}
            <div class="sm:col-span-2">
                <label for="complemento" class="mb-1.5 block text-[12px] font-semibold uppercase tracking-wide text-slate-500">Complemento</label>
                <input id="complemento" type="text" name="complemento"
                       value="{{ old('complemento') }}"
                       placeholder="Apto, Bloco..."
                       class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
            </div>

            {{-- Bairro --}}
            <div class="sm:col-span-3">
                <label for="bairro" class="mb-1.5 block text-[12px] font-semibold uppercase tracking-wide text-slate-500">Bairro</label>
                <input id="bairro" type="text" name="bairro"
                       x-model="bairro"
                       value="{{ old('bairro') }}"
                       placeholder="Centro"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
            </div>

            {{-- Cidade --}}
            <div class="sm:col-span-4">
                <label for="cidade" class="mb-1.5 block text-[12px] font-semibold uppercase tracking-wide text-slate-500">Cidade</label>
                <input id="cidade" type="text" name="cidade"
                       x-model="cidade"
                       value="{{ old('cidade') }}"
                       placeholder="São Paulo"
                       class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
            </div>

            {{-- UF --}}
            <div class="sm:col-span-2">
                <label for="estado" class="mb-1.5 block text-[12px] font-semibold uppercase tracking-wide text-slate-500">Estado</label>
                <select id="estado" name="estado"
                        x-model="uf"
                        class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-[13.5px] text-slate-900 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    <option value="">UF</option>
                    @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                    <option value="{{ $uf }}" {{ old('estado') === $uf ? 'selected' : '' }}>{{ $uf }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- ── 4. Observações ── --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm mt-5">
        <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div>
                <h2 class="text-[14px] font-bold text-slate-900">Observações <span class="ml-1 text-[11px] font-normal text-slate-400">(opcional)</span></h2>
                <p class="text-[12px] text-slate-400">Notas internas visíveis apenas para a equipe</p>
            </div>
        </div>
        <div class="p-6">
            <textarea name="observacoes" rows="3"
                      placeholder="Preferências, histórico de atendimento, informações relevantes…"
                      class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 resize-none">{{ old('observacoes') }}</textarea>
        </div>
    </div>

    {{-- Botões mobile --}}
    <div class="flex gap-3 pb-6 lg:hidden mt-5">
        <a href="{{ route('app.clientes.index') }}"
           class="flex-1 rounded-xl border border-slate-200 py-2.5 text-center text-[13px] font-semibold text-slate-700 transition-colors hover:bg-slate-50">
            Cancelar
        </a>
        <button type="submit"
                class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 py-2.5 text-[13px] font-semibold text-white shadow-sm transition-colors hover:bg-blue-700">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Salvar Cliente
        </button>
    </div>

    </form>
</div>

{{-- ══════════════════════════════════════════
     COLUNA LATERAL — Preview + Ações
══════════════════════════════════════════ --}}
<div class="hidden lg:block">
    <div class="sticky top-24 space-y-4">

        {{-- Avatar preview --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100">
                <p class="text-[12.5px] font-bold text-slate-500 uppercase tracking-wide">Pré-visualização</p>
            </div>
            <div class="p-5 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 text-[22px] font-black text-white shadow-lg shadow-blue-500/25"
                     x-text="iniciais || '?'"></div>
                <p class="mt-3 text-[14px] font-bold text-slate-900 leading-tight" x-text="nome || 'Nome do cliente'"></p>
                <p class="mt-0.5 text-[12px] text-slate-400" x-text="telefone || 'Telefone não informado'"></p>
                <div x-show="cpf" class="mt-2 inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-mono font-medium text-slate-600">
                    <span x-text="cpf"></span>
                </div>
            </div>
        </div>

        {{-- Checklist de preenchimento --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <p class="text-[12.5px] font-bold text-slate-500 uppercase tracking-wide">Completude</p>
                <span class="text-[12px] font-bold text-blue-600" x-text="progresso + '%'"></span>
            </div>
            <div class="px-5 py-1 pb-3">
                <div class="mt-3 mb-4 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-blue-500 transition-all duration-500" :style="'width:' + progresso + '%'"></div>
                </div>
                <ul class="space-y-2">
                    <template x-for="item in checklist" :key="item.label">
                        <li class="flex items-center gap-2.5 text-[12.5px]">
                            <div class="flex h-4.5 w-4.5 shrink-0 items-center justify-center rounded-full transition-colors"
                                 :class="item.ok ? 'bg-emerald-500' : 'bg-slate-100 border border-slate-200'">
                                <svg x-show="item.ok" class="h-2.5 w-2.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span :class="item.ok ? 'text-slate-800 font-medium' : 'text-slate-400'" x-text="item.label"></span>
                            <span x-show="item.required && !item.ok" class="ml-auto text-[10px] font-semibold text-red-500 uppercase">Obrig.</span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        {{-- Botões --}}
        <div class="space-y-2">
            <button type="submit" form="form-cliente"
                    :disabled="!nome.trim()"
                    class="w-full flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed px-5 py-3 text-[13.5px] font-bold text-white shadow-md shadow-blue-600/20 transition-all active:scale-[0.98]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Salvar Cliente
            </button>
            <a href="{{ route('app.clientes.index') }}"
               class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-[13px] font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                Cancelar
            </a>
        </div>

        {{-- Dica --}}
        <div class="rounded-2xl bg-blue-50 border border-blue-100 p-4">
            <div class="flex items-start gap-2.5">
                <svg class="h-4 w-4 text-blue-600 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                <div>
                    <p class="text-[12px] font-semibold text-blue-800">Portal do Cliente</p>
                    <p class="text-[11.5px] text-blue-600 mt-0.5 leading-relaxed">Informe o CPF e a data de nascimento para que o cliente acesse o portal.</p>
                </div>
            </div>
        </div>

    </div>
</div>

</div>{{-- fim grid --}}

@push('scripts')
<script>
function clienteForm() {
    return {
        tipo: 'pf',
        nome: @json(old('nome', '')),
        cpf:  @json(old('cpf_cnpj', '')),
        telefone: @json(old('telefone', '')),
        cep:  @json(old('cep', '')),
        rua:  @json(old('endereco', '')),
        bairro: @json(old('bairro', '')),
        cidade: @json(old('cidade', '')),
        uf:   @json(old('estado', '')),
        cepBusy: false,
        cepOk: false,
        cepErro: false,

        init() {
            if (this.cep.length === 9) this.buscarCep();
        },

        get iniciais() {
            const p = this.nome.trim().split(/\s+/);
            if (p.length === 0 || !p[0]) return '';
            return (p[0][0] + (p[1] ? p[1][0] : '')).toUpperCase();
        },

        get progresso() {
            const total = this.checklist.filter(i => i.required || i.ok).length || this.checklist.length;
            const ok    = this.checklist.filter(i => i.ok).length;
            return Math.round((ok / this.checklist.length) * 100);
        },

        get checklist() {
            return [
                { label: 'Nome',              ok: this.nome.trim().length > 2,  required: true  },
                { label: 'CPF / CNPJ',        ok: this.cpf.length >= 14,        required: false },
                { label: 'Data de nascimento',ok: !!document.getElementById('data_nascimento')?.value, required: false },
                { label: 'Telefone',          ok: this.telefone.length >= 14,   required: false },
                { label: 'E-mail',            ok: !!document.getElementById('email')?.value,    required: false },
                { label: 'Endereço',          ok: this.rua.length > 3,          required: false },
            ];
        },

        maskCpf(e) {
            let v = e.target.value.replace(/\D/g, '');
            if (this.tipo === 'pj') {
                v = v.substring(0, 14);
                v = v.replace(/^(\d{2})(\d)/, '$1.$2')
                     .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                     .replace(/\.(\d{3})(\d)/, '.$1/$2')
                     .replace(/(\d{4})(\d)/, '$1-$2');
            } else {
                v = v.substring(0, 11);
                v = v.replace(/(\d{3})(\d)/, '$1.$2')
                     .replace(/(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
                     .replace(/\.(\d{3})(\d)/, '.$1-$2');
            }
            this.cpf = v;
        },

        maskTel(e) {
            let v = e.target.value.replace(/\D/g, '').substring(0, 11);
            if (v.length > 10) {
                v = v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
            } else if (v.length > 6) {
                v = v.replace(/^(\d{2})(\d{4})(\d*)$/, '($1) $2-$3');
            } else if (v.length > 2) {
                v = v.replace(/^(\d{2})(\d*)$/, '($1) $2');
            }
            this.telefone = v;
        },

        maskCep(e) {
            let v = e.target.value.replace(/\D/g, '').substring(0, 8);
            if (v.length > 5) v = v.replace(/^(\d{5})(\d)/, '$1-$2');
            this.cep = v;
            this.cepOk = false;
            this.cepErro = false;
        },

        async buscarCep() {
            const digits = this.cep.replace(/\D/g, '');
            if (digits.length !== 8) return;

            this.cepBusy = true;
            this.cepOk   = false;
            this.cepErro = false;

            try {
                const r = await fetch(`https://viacep.com.br/ws/${digits}/json/`);
                const d = await r.json();
                if (d.erro) { this.cepErro = true; return; }
                this.rua    = d.logradouro || '';
                this.bairro = d.bairro     || '';
                this.cidade = d.localidade || '';
                this.uf     = d.uf         || '';
                this.cepOk  = true;
                document.getElementById('numero')?.focus();
            } catch {
                this.cepErro = true;
            } finally {
                this.cepBusy = false;
            }
        },
    };
}
</script>
@endpush

@endsection
