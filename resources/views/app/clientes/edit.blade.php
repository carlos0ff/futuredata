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

{{-- Page header --}}
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <x-app.avatar :initials="$cliente->iniciais" size="lg" />
        <div>
            <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Editar {{ $cliente->nome }}</h1>
            <p class="mt-0.5 text-[13px] text-slate-500">Atualize as informações do cliente.</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('app.clientes.show', $cliente) }}"
           class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-[13px] font-semibold text-slate-600 shadow-sm transition-colors hover:bg-slate-50">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M5 12l7-7M5 12l7 7"/>
            </svg>
            Cancelar
        </a>
        <form method="POST" action="{{ route('app.clientes.destroy', $cliente) }}"
              onsubmit="return confirm('Excluir {{ addslashes($cliente->nome) }}? Esta ação não pode ser desfeita.')"
              class="inline">
            @csrf @method('DELETE')
            <button type="submit"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-red-200 bg-red-50 text-red-500 shadow-sm transition-colors hover:bg-red-100 hover:text-red-600">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/>
                </svg>
            </button>
        </form>
    </div>
</div>

<form action="{{ route('app.clientes.update', $cliente) }}" method="POST" class="max-w-2xl space-y-5">
@csrf @method('PUT')

    {{-- Identificação --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100">
                <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[14px] font-bold text-slate-900">Identificação</h2>
                <p class="text-[12px] text-slate-400">Nome e documento do cliente</p>
            </div>
        </div>
        <div class="grid gap-4 p-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <x-form.field label="Nome completo" for="nome" :required="true" :error="$errors->first('nome')">
                    <input id="nome" type="text" name="nome" value="{{ old('nome', $cliente->nome) }}"
                           class="h-10 w-full rounded-xl border {{ $errors->has('nome') ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 bg-slate-50 focus:border-blue-400 focus:ring-blue-100' }} px-3 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:bg-white focus:ring-2">
                </x-form.field>
            </div>
            <div>
                <x-form.field label="CPF / CNPJ" for="cpf_cnpj" :error="$errors->first('cpf_cnpj')">
                    <input id="cpf_cnpj" type="text" name="cpf_cnpj" value="{{ old('cpf_cnpj', $cliente->cpf_cnpj) }}"
                           class="h-10 w-full rounded-xl border {{ $errors->has('cpf_cnpj') ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 bg-slate-50 focus:border-blue-400 focus:ring-blue-100' }} px-3 font-mono text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:bg-white focus:ring-2">
                </x-form.field>
            </div>
        </div>
    </div>

    {{-- Contato --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100">
                <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.6 19.79 19.79 0 0 1 1.61 5c-.11-1.18.8-2.18 2-2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 10.91A16 16 0 0 0 13.09 15.91l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 16.92z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[14px] font-bold text-slate-900">Contato</h2>
                <p class="text-[12px] text-slate-400">Telefone e e-mail para comunicação</p>
            </div>
        </div>
        <div class="grid gap-4 p-6 sm:grid-cols-2">
            <div>
                <x-form.field label="Telefone" for="telefone">
                    <input id="telefone" type="text" name="telefone" value="{{ old('telefone', $cliente->telefone) }}"
                           placeholder="(11) 99999-9999"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                </x-form.field>
            </div>
            <div>
                <x-form.field label="E-mail" for="email" :error="$errors->first('email')">
                    <input id="email" type="email" name="email" value="{{ old('email', $cliente->email) }}"
                           class="h-10 w-full rounded-xl border {{ $errors->has('email') ? 'border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 bg-slate-50 focus:border-blue-400 focus:ring-blue-100' }} px-3 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:bg-white focus:ring-2">
                </x-form.field>
            </div>
        </div>
    </div>

    {{-- Endereço --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100">
                <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[14px] font-bold text-slate-900">Endereço</h2>
                <p class="text-[12px] text-slate-400">Localização do cliente</p>
            </div>
        </div>
        <div class="grid gap-4 p-6 sm:grid-cols-2">
            <div>
                <x-form.field label="CEP" for="cep">
                    <input id="cep" type="text" name="cep" value="{{ old('cep', $cliente->cep) }}"
                           placeholder="00000-000"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                </x-form.field>
            </div>
            <div class="sm:col-span-2">
                <x-form.field label="Endereço" for="endereco">
                    <input id="endereco" type="text" name="endereco" value="{{ old('endereco', $cliente->endereco) }}"
                           placeholder="Rua, número, complemento"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                </x-form.field>
            </div>
            <div>
                <x-form.field label="Cidade" for="cidade">
                    <input id="cidade" type="text" name="cidade" value="{{ old('cidade', $cliente->cidade) }}"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                </x-form.field>
            </div>
            <div>
                <x-form.field label="Estado (UF)" for="estado">
                    <input id="estado" type="text" name="estado" value="{{ old('estado', $cliente->estado) }}"
                           maxlength="2"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-[13.5px] uppercase text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
                </x-form.field>
            </div>
        </div>
    </div>

    {{-- Observações --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100">
                <svg class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[14px] font-bold text-slate-900">Observações</h2>
            </div>
        </div>
        <div class="p-6">
            <textarea name="observacoes" rows="3"
                      placeholder="Informações relevantes, preferências, histórico…"
                      class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">{{ old('observacoes', $cliente->observacoes) }}</textarea>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3 pb-6">
        <a href="{{ route('app.clientes.show', $cliente) }}"
           class="flex-1 rounded-xl border border-slate-200 py-2.5 text-center text-[13px] font-semibold text-slate-700 transition-colors hover:bg-slate-50">
            Cancelar
        </a>
        <button type="submit"
                class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 py-2.5 text-[13px] font-semibold text-white shadow-sm transition-colors hover:bg-blue-700">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Salvar Alterações
        </button>
    </div>

</form>

@endsection
