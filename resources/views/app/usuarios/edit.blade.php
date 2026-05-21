@extends('layouts.app')
@section('title', 'Editar Usuário')
@section('breadcrumbs')
<a href="{{ route('app.usuarios.index') }}" class="text-slate-400 hover:text-slate-600">Usuários</a>
<span class="mx-1.5 text-slate-300">/</span>
<span class="font-semibold text-slate-900">Editar</span>
@endsection
@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Editar Usuário</h1>
    @if($usuario->id !== auth()->id())
    <form action="{{ route('app.usuarios.destroy', $usuario) }}" method="POST" onsubmit="return confirm('Tem certeza?')">
        @csrf @method('DELETE')
        <button type="submit" class="flex items-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-[13px] font-semibold text-red-700 hover:bg-red-100 transition-colors">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            Remover
        </button>
    </form>
    @endif
</div>
<div class="max-w-lg">
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
<form action="{{ route('app.usuarios.update', $usuario) }}" method="POST" class="p-6 space-y-4">
@csrf @method('PUT')
<div>
    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nome completo <span class="text-red-500">*</span></label>
    <input type="text" name="name" value="{{ old('name', $usuario->name) }}" class="w-full rounded-xl border {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    @error('name')<p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">E-mail <span class="text-red-500">*</span></label>
    <input type="email" name="email" value="{{ old('email', $usuario->email) }}" class="w-full rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-200 bg-slate-50' }} px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    @error('email')<p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>@enderror
</div>
<div>
    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Função <span class="text-red-500">*</span></label>
    <select name="role" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
        <option value="gerente" {{ old('role', $usuario->role) === 'gerente' ? 'selected' : '' }}>Gerente</option>
        <option value="tecnico" {{ old('role', $usuario->role) === 'tecnico' ? 'selected' : '' }}>Técnico</option>
    </select>
</div>
<div>
    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nova senha <span class="text-slate-400 font-normal">(deixar em branco para manter)</span></label>
    <input type="password" name="password" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[13.5px] focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
    @error('password')<p class="mt-1 text-[11.5px] text-red-600">{{ $message }}</p>@enderror
</div>
<div class="flex gap-3 pt-2 border-t border-slate-100">
    <a href="{{ route('app.usuarios.index') }}" class="flex-1 rounded-xl border border-slate-200 py-2.5 text-center text-[13px] font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Cancelar</a>
    <button type="submit" class="flex-1 rounded-xl bg-blue-600 py-2.5 text-[13px] font-semibold text-white hover:bg-blue-700 transition-colors">Salvar</button>
</div>
</form>
</div>
</div>
@endsection
