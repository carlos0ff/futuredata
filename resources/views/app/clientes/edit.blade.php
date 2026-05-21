@extends('layouts.app')
@section('title', 'Editar Cliente')
@section('breadcrumbs')
<a href="{{ route('app.clientes.index') }}" class="text-slate-400 hover:text-slate-600">Clientes</a>
<span class="mx-1.5 text-slate-300">/</span>
<a href="{{ route('app.clientes.show', $cliente) }}" class="text-slate-400 hover:text-slate-600">{{ $cliente->nome }}</a>
<span class="mx-1.5 text-slate-300">/</span>
<span class="font-semibold text-slate-900">Editar</span>
@endsection
@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Editar Cliente</h1>
    <form action="{{ route('app.clientes.destroy', $cliente) }}" method="POST" onsubmit="return confirm('Remover este cliente?')">
        @csrf @method('DELETE')
        <button type="submit" class="flex items-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-[13px] font-semibold text-red-700 hover:bg-red-100 transition-colors">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            Remover
        </button>
    </form>
</div>
<div class="max-w-2xl">
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
<form action="{{ route('app.clientes.update', $cliente) }}" method="POST" class="p-6">
@csrf @method('PUT')
<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nome completo <span class="text-red-500">*</span></label>
        <input type="text" name="nome" value="{{ old('nome', $cliente->nome) }}" class="w-full rounded-xl border {{ $errors->has('nome') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
        @error('nome')<p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Telefone</label>
        <input type="text" name="telefone" value="{{ old('telefone', $cliente->telefone) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div>
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">E-mail</label>
        <input type="email" name="email" value="{{ old('email', $cliente->email) }}" class="w-full rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div>
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">CPF / CNPJ</label>
        <input type="text" name="cpf_cnpj" value="{{ old('cpf_cnpj', $cliente->cpf_cnpj) }}" class="w-full rounded-xl border {{ $errors->has('cpf_cnpj') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div>
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">CEP</label>
        <input type="text" name="cep" value="{{ old('cep', $cliente->cep) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div class="sm:col-span-2">
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Endereço</label>
        <input type="text" name="endereco" value="{{ old('endereco', $cliente->endereco) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div>
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Cidade</label>
        <input type="text" name="cidade" value="{{ old('cidade', $cliente->cidade) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div>
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Estado (UF)</label>
        <input type="text" name="estado" value="{{ old('estado', $cliente->estado) }}" maxlength="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div class="sm:col-span-2">
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Observações</label>
        <textarea name="observacoes" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('observacoes', $cliente->observacoes) }}</textarea>
    </div>
</div>
<div class="mt-5 flex gap-3 border-t border-slate-100 pt-5">
    <a href="{{ route('app.clientes.show', $cliente) }}" class="flex-1 rounded-xl border border-slate-200 py-2.5 text-center text-[13px] font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Cancelar</a>
    <button type="submit" class="flex-1 rounded-xl bg-blue-600 py-2.5 text-[13px] font-semibold text-white hover:bg-blue-700 transition-colors">Salvar</button>
</div>
</form>
</div>
</div>
@endsection
