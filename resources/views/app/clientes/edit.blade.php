@extends('layouts.app')
@section('title', 'Editar Cliente')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Clientes', 'href' => route('app.clientes.index')],
            ['label' => $cliente->nome, 'href' => route('app.clientes.show', $cliente)],
            ['label' => 'Editar'],
        ]
    ])
@endsection

@section('content')

{{-- Layout: form (2/3) + sidebar (1/3) --}}
<div class="grid gap-6 lg:grid-cols-3">

    {{-- ── FORMULÁRIO (col-span-2) ──────────────────────────── --}}
    <form action="{{ route('app.clientes.update', $cliente) }}" method="POST"
          class="lg:col-span-2 space-y-4" id="form-edit-cliente">
        @csrf @method('PUT')

        {{-- 1 · Identificação --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-100">
                    <svg class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Identificação</h2>
                    <p class="text-xs text-slate-400">Nome completo e documento</p>
                </div>
            </div>
            @if($temOsEmAndamento)
            <div class="mx-6 mt-6 flex items-start gap-2.5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                <span>Este cliente possui OS em andamento. <strong class="font-semibold">Nome</strong> e <strong class="font-semibold">CPF/CNPJ</strong> não podem ser alterados até que sejam finalizadas ou canceladas.</span>
            </div>
            @endif
            <div class="grid gap-4 p-6 sm:grid-cols-2">
                {{-- Nome --}}
                <div class="sm:col-span-2">
                    <label for="nome" class="mb-1.5 block text-xs font-semibold text-slate-700">
                        Nome completo <span class="text-red-500">*</span>
                    </label>
                    <input id="nome" type="text" name="nome" value="{{ old('nome', $cliente->nome) }}"
                           placeholder="Ex.: João da Silva"
                           autofocus @disabled($temOsEmAndamento)
                           class="h-10 w-full rounded-xl border {{ $errors->has('nome') ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-100' }} px-3.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500">
                    @error('nome')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    @if($temOsEmAndamento)
                    <input type="hidden" name="nome" value="{{ $cliente->nome }}">
                    @endif
                </div>

                {{-- CPF / CNPJ --}}
                <div>
                    <label for="cpf_cnpj" class="mb-1.5 block text-xs font-semibold text-slate-700">CPF / CNPJ</label>
                    <input id="cpf_cnpj" type="text" name="cpf_cnpj" value="{{ old('cpf_cnpj', $cliente->cpf_cnpj) }}"
                           placeholder="000.000.000-00" @disabled($temOsEmAndamento)
                           class="h-10 w-full rounded-xl border {{ $errors->has('cpf_cnpj') ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-100' }} px-3.5 font-mono text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500">
                    @error('cpf_cnpj')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    @if($temOsEmAndamento)
                    <input type="hidden" name="cpf_cnpj" value="{{ $cliente->cpf_cnpj }}">
                    @endif
                </div>

                {{-- Data de nascimento --}}
                <div>
                    <label for="data_nascimento" class="mb-1.5 block text-xs font-semibold text-slate-700">Data de Nascimento / Fundação</label>
                    <input id="data_nascimento" type="date" name="data_nascimento"
                           value="{{ old('data_nascimento', $cliente->data_nascimento?->format('Y-m-d')) }}"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
            </div>
        </div>

        {{-- 2 · Contato --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-green-100">
                    <svg class="h-4 w-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.6 19.79 19.79 0 0 1 1.61 5c-.11-1.18.8-2.18 2-2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 10.91A16 16 0 0 0 13.09 15.91l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 16.92z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Contato</h2>
                    <p class="text-xs text-slate-400">Telefone e e-mail para comunicação</p>
                </div>
            </div>
            <div class="grid gap-4 p-6 sm:grid-cols-2">
                {{-- Telefone --}}
                <div>
                    <label for="telefone" class="mb-1.5 block text-xs font-semibold text-slate-700">Telefone</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.6 19.79 19.79 0 0 1 1.61 5c-.11-1.18.8-2.18 2-2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 10.91A16 16 0 0 0 13.09 15.91l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 16.92z"/>
                            </svg>
                        </span>
                        <input id="telefone" type="tel" name="telefone" value="{{ old('telefone', $cliente->telefone) }}"
                               placeholder="(11) 99999-9999"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-3.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    </div>
                </div>

                {{-- E-mail --}}
                <div>
                    <label for="email" class="mb-1.5 block text-xs font-semibold text-slate-700">E-mail</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email', $cliente->email) }}"
                               placeholder="cliente@exemplo.com"
                               class="h-10 w-full rounded-xl border {{ $errors->has('email') ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-100' }} pl-10 pr-3.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:ring-2">
                    </div>
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- 3 · Endereço --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-purple-100">
                        <svg class="h-4 w-4 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Endereço</h2>
                        <p class="text-xs text-slate-400">Preencha o CEP para busca automática</p>
                    </div>
                </div>
                <span id="cep-status" class="hidden text-xs font-medium text-blue-600 flex items-center gap-1">
                    <svg class="h-3 w-3 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    </svg>
                    Buscando…
                </span>
            </div>

            <div class="grid gap-4 p-6 sm:grid-cols-4">
                {{-- CEP --}}
                <div class="sm:col-span-1">
                    <label for="cep" class="mb-1.5 block text-xs font-semibold text-slate-700">CEP</label>
                    <input id="cep" type="text" name="cep" value="{{ old('cep', $cliente->cep) }}"
                           placeholder="00000-000" maxlength="9"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           data-cep-input>
                </div>

                {{-- Logradouro --}}
                <div class="sm:col-span-3">
                    <label for="endereco" class="mb-1.5 block text-xs font-semibold text-slate-700">Logradouro</label>
                    <input id="endereco" type="text" name="endereco" value="{{ old('endereco', $cliente->endereco) }}"
                           placeholder="Rua, Avenida, Travessa…"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>

                {{-- Número --}}
                <div class="sm:col-span-1">
                    <label for="numero" class="mb-1.5 block text-xs font-semibold text-slate-700">Número</label>
                    <input id="numero" type="text" name="numero" value="{{ old('numero', $cliente->numero) }}"
                           placeholder="123"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>

                {{-- Complemento --}}
                <div class="sm:col-span-1">
                    <label for="complemento" class="mb-1.5 block text-xs font-semibold text-slate-700">Complemento</label>
                    <input id="complemento" type="text" name="complemento" value="{{ old('complemento', $cliente->complemento) }}"
                           placeholder="Apto, Bloco…"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>

                {{-- Bairro --}}
                <div class="sm:col-span-2">
                    <label for="bairro" class="mb-1.5 block text-xs font-semibold text-slate-700">Bairro</label>
                    <input id="bairro" type="text" name="bairro" value="{{ old('bairro', $cliente->bairro) }}"
                           placeholder="Centro"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>

                {{-- Cidade --}}
                <div class="sm:col-span-3">
                    <label for="cidade" class="mb-1.5 block text-xs font-semibold text-slate-700">Cidade</label>
                    <input id="cidade" type="text" name="cidade" value="{{ old('cidade', $cliente->cidade) }}"
                           placeholder="São Paulo"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>

                {{-- Estado --}}
                <div class="sm:col-span-1">
                    <label for="estado" class="mb-1.5 block text-xs font-semibold text-slate-700">UF</label>
                    <input id="estado" type="text" name="estado" value="{{ old('estado', $cliente->estado) }}"
                           placeholder="SP" maxlength="2"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm uppercase text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
            </div>
        </div>

        {{-- 4 · Observações --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-amber-100">
                    <svg class="h-4 w-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Observações <span class="font-normal text-slate-400">(opcional)</span></h2>
                    <p class="text-xs text-slate-400">Notas internas, preferências, histórico</p>
                </div>
            </div>
            <div class="p-6">
                <textarea name="observacoes" rows="4"
                          placeholder="Informações relevantes, preferências, histórico, notas importantes…"
                          class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('observacoes', $cliente->observacoes) }}</textarea>
            </div>
        </div>

        {{-- Botões --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end pb-2">
            <a href="{{ route('app.clientes.show', $cliente) }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
                Cancelar
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 active:scale-[0.98]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Salvar Alterações
            </button>
        </div>
    </form>

    {{-- ── SIDEBAR ──────────────────────────────────────────── --}}
    <aside class="space-y-4">

        {{-- Card do cliente --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            {{-- Banner + Avatar --}}
            <div class="relative h-20 bg-gradient-to-br from-blue-600 to-indigo-700">
                <div class="absolute -bottom-6 left-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border-4 border-white bg-blue-100 text-lg font-extrabold text-blue-700 shadow-sm">
                        {{ $cliente->iniciais }}
                    </div>
                </div>
            </div>

            <div class="px-6 pb-6 pt-9">
                <h3 class="text-base font-bold text-slate-900 leading-tight">{{ $cliente->nome }}</h3>
                @if($cliente->email)
                <p class="mt-0.5 truncate text-xs text-slate-400">{{ $cliente->email }}</p>
                @endif
                <p class="mt-1 text-[11px] text-slate-400">
                    Cliente desde {{ $cliente->created_at->format('M Y') }}
                </p>

                {{-- Stats --}}
                <div class="mt-4 grid grid-cols-3 divide-x divide-slate-100 rounded-xl border border-slate-100 bg-slate-50">
                    <div class="px-3 py-3 text-center">
                        <p class="text-lg font-black text-slate-800 tabular-nums">{{ $cliente->ordens_count }}</p>
                        <p class="text-[10px] font-medium text-slate-400 leading-tight">Total OS</p>
                    </div>
                    @php
                        $finalizadas = $cliente->ordens()->where('status', 'finalizado')->count();
                    @endphp
                    <div class="px-3 py-3 text-center">
                        <p class="text-lg font-black text-emerald-600 tabular-nums">{{ $finalizadas }}</p>
                        <p class="text-[10px] font-medium text-slate-400 leading-tight">Finalizadas</p>
                    </div>
                    <div class="px-3 py-3 text-center">
                        <p class="text-sm font-black text-amber-600 tabular-nums leading-tight">
                            @if($totalGasto >= 1000)
                                R${{ number_format($totalGasto / 1000, 1, ',', '') }}k
                            @else
                                R${{ number_format($totalGasto, 0, ',', '') }}
                            @endif
                        </p>
                        <p class="text-[10px] font-medium text-slate-400 leading-tight">Gasto total</p>
                    </div>
                </div>

                @if($ultimaOs)
                <p class="mt-3 text-[11px] text-slate-400 flex items-center gap-1">
                    <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Última OS {{ $ultimaOs->created_at->diffForHumans() }}
                </p>
                @endif
            </div>
        </div>

        {{-- Ações rápidas --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-3.5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Ações rápidas</p>
            </div>
            <div class="p-3 space-y-1">
                <a href="{{ route('app.clientes.show', $cliente) }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100">
                        <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    Ver perfil completo
                </a>
                <a href="{{ route('app.os.create', ['cliente_id' => $cliente->id]) }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-blue-50">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-100">
                        <svg class="h-3.5 w-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                    </div>
                    <span>Nova OS para este cliente</span>
                </a>
                <a href="{{ route('app.os.index', ['cliente_id' => $cliente->id]) }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100">
                        <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/>
                        </svg>
                    </div>
                    Ver OS deste cliente
                </a>
            </div>
        </div>

        {{-- Zona de perigo --}}
        <div class="overflow-hidden rounded-2xl border border-red-100 bg-white shadow-sm">
            <div class="border-b border-red-100 bg-red-50 px-5 py-3.5">
                <p class="text-xs font-bold uppercase tracking-wider text-red-400">Zona de perigo</p>
            </div>
            <div class="p-4">
                <p class="mb-3 text-xs text-slate-500">
                    Excluir o cliente remove permanentemente todas as informações. OS vinculadas não serão excluídas.
                </p>
                <form method="POST" action="{{ route('app.clientes.destroy', $cliente) }}"
                      onsubmit="return confirm('Excluir {{ addslashes($cliente->nome) }}? Esta ação não pode ser desfeita.')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-100 hover:border-red-300 active:scale-[0.98]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/>
                        </svg>
                        Excluir cliente
                    </button>
                </form>
            </div>
        </div>

    </aside>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Máscara telefone ─────────────────────────────────────
    const tel = document.getElementById('telefone');
    if (tel) {
        tel.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, '').slice(0, 11);
            if (v.length > 2) v = `(${v.slice(0,2)}) ${v.slice(2)}`;
            if (v.length > 10) v = v.slice(0,10) + '-' + v.slice(10);
            this.value = v;
        });
    }

    // ── Máscara CPF/CNPJ ─────────────────────────────────────
    const doc = document.getElementById('cpf_cnpj');
    if (doc) {
        doc.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, '');
            if (v.length <= 11)
                v = v.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4').replace(/-$/, '');
            else
                v = v.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2})/, '$1.$2.$3/$4-$5').replace(/-$/, '');
            this.value = v;
        });
    }

    // ── Máscara CEP + busca ViaCEP ───────────────────────────
    const cepInput = document.querySelector('[data-cep-input]');
    const cepStatus = document.getElementById('cep-status');

    if (cepInput) {
        cepInput.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, '').slice(0, 8);
            if (v.length > 5) v = v.slice(0,5) + '-' + v.slice(5);
            this.value = v;
        });

        cepInput.addEventListener('blur', async function () {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length !== 8) return;

            cepStatus.classList.remove('hidden');
            try {
                const res  = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await res.json();
                if (!data.erro) {
                    document.getElementById('endereco').value   = data.logradouro  ?? '';
                    document.getElementById('bairro').value     = data.bairro      ?? '';
                    document.getElementById('cidade').value     = data.localidade  ?? '';
                    document.getElementById('estado').value     = data.uf          ?? '';
                    document.getElementById('numero').focus();
                }
            } catch (_) {}
            finally { cepStatus.classList.add('hidden'); }
        });
    }
});
</script>

@endsection
