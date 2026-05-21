@extends('layouts.app')
@section('title', 'Novo Cliente')
@section('breadcrumbs')
<a href="{{ route('app.clientes.index') }}" class="text-slate-400 hover:text-slate-600">Clientes</a>
<span class="mx-1.5 text-slate-300">/</span>
<span class="font-semibold text-slate-900">Novo Cliente</span>
@endsection
@section('content')
<div class="mb-6"><h1 class="text-[22px] font-bold tracking-tight text-slate-900">Novo Cliente</h1></div>
<div class="max-w-2xl">
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
<div class="border-b border-slate-100 px-6 py-4"><h2 class="text-[14px] font-bold text-slate-900">Informações do cliente</h2></div>
<form action="{{ route('app.clientes.store') }}" method="POST" class="p-6">
@csrf
<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nome completo <span class="text-red-500">*</span></label>
        <input type="text" name="nome" value="{{ old('nome') }}" class="w-full rounded-xl border {{ $errors->has('nome') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
        @error('nome')<p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Telefone</label>
        <input type="text" name="telefone" value="{{ old('telefone') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div>
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">E-mail</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
        @error('email')<p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">CPF / CNPJ</label>
        <input type="text" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" class="w-full rounded-xl border {{ $errors->has('cpf_cnpj') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
        @error('cpf_cnpj')<p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">CEP</label>
        <input type="text" name="cep" value="{{ old('cep') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div class="sm:col-span-2">
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Endereço</label>
        <input type="text" name="endereco" value="{{ old('endereco') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div>
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Cidade</label>
        <input type="text" name="cidade" value="{{ old('cidade') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div>
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Estado (UF)</label>
        <input type="text" name="estado" value="{{ old('estado') }}" maxlength="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    </div>
    <div class="sm:col-span-2">
        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Observações</label>
        <textarea name="observacoes" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('observacoes') }}</textarea>
    </div>
</div>
<div class="mt-5 flex gap-3 border-t border-slate-100 pt-5">
    <a href="{{ route('app.clientes.index') }}" class="flex-1 rounded-xl border border-slate-200 py-2.5 text-center text-[13px] font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Cancelar</a>
    <button type="submit" class="flex-1 rounded-xl bg-blue-600 py-2.5 text-[13px] font-semibold text-white hover:bg-blue-700 transition-colors">Salvar Cliente</button>
</div>
</form>
</div>
</div>
@endsection
