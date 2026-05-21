@extends('layouts.app')
@section('title', 'Novo Usuário')
@section('breadcrumbs')
<a href="{{ route('app.usuarios.index') }}" class="text-slate-400 hover:text-slate-600">Usuários</a>
<span class="mx-1.5 text-slate-300">/</span>
<span class="font-semibold text-slate-900">Novo Usuário</span>
@endsection
@section('content')
<div class="mb-6"><h1 class="text-[22px] font-bold tracking-tight text-slate-900">Novo Usuário</h1></div>
<div class="max-w-lg">
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
<div class="border-b border-slate-100 px-6 py-4"><h2 class="text-[14px] font-bold text-slate-900">Dados do utilizador</h2></div>
<form action="{{ route('app.usuarios.store') }}" method="POST" class="p-6 space-y-4">
@csrf
<div>
    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nome completo <span class="text-red-500">*</span></label>
    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    @error('name')<p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">E-mail <span class="text-red-500">*</span></label>
    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    @error('email')<p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Função <span class="text-red-500">*</span></label>
    <select name="role" class="w-full rounded-xl border {{ $errors->has('role') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
        <option value="">Selecionar...</option>
        <option value="gerente" {{ old('role') === 'gerente' ? 'selected' : '' }}>Gerente</option>
        <option value="tecnico" {{ old('role') === 'tecnico' ? 'selected' : '' }}>Técnico</option>
    </select>
    @error('role')<p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Senha <span class="text-red-500">*</span></label>
    <input type="password" name="password" class="w-full rounded-xl border {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    @error('password')<p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Confirmar senha</label>
    <input type="password" name="password_confirmation" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
</div>
<div class="flex gap-3 pt-2 border-t border-slate-100">
    <a href="{{ route('app.usuarios.index') }}" class="flex-1 rounded-xl border border-slate-200 py-2.5 text-center text-[13px] font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Cancelar</a>
    <button type="submit" class="flex-1 rounded-xl bg-blue-600 py-2.5 text-[13px] font-semibold text-white hover:bg-blue-700 transition-colors">Criar Usuário</button>
</div>
</form>
</div>
</div>
@endsection
