@extends('layouts.app')
@section('title', 'Meu Perfil')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [['label' => 'Meu Perfil']]
    ])
@endsection

@section('content')

{{-- Page header --}}
<div class="mb-6">
    <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Meu Perfil</h1>
    <p class="mt-0.5 text-[13px] text-slate-500">Gerencie suas informações pessoais e acesso.</p>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
        <svg class="h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/>
        </svg>
        <p class="text-[13px] font-medium text-emerald-700">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="mb-5 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
        <svg class="h-4 w-4 shrink-0 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <p class="text-[13px] font-medium text-red-700">{{ session('error') }}</p>
    </div>
@endif

{{-- Avatar section --}}
<div class="mb-6 flex items-center gap-5">
    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-400 via-blue-600 to-indigo-700 text-[20px] font-bold text-white shadow-sm">
        {{ strtoupper(auth()->user()->iniciais ?? substr(auth()->user()->name, 0, 2)) }}
    </div>
    <div>
        <p class="text-[16px] font-bold text-slate-900">{{ auth()->user()->name }}</p>
        <p class="text-[13px] text-slate-500">{{ auth()->user()->email }}</p>
        @if(auth()->user()->role)
            <span class="mt-1 inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-[11px] font-semibold text-blue-700">
                {{ ucfirst(auth()->user()->role) }}
            </span>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

    {{-- Personal info --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-[14px] font-bold text-slate-900">Informações Pessoais</h2>
            <p class="text-[12px] text-slate-500 mt-0.5">Atualize seu nome de exibição.</p>
        </div>
        <form method="POST" action="{{ route('app.perfil.atualizar') }}" class="p-6 space-y-4" novalidate>
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nome completo</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('name') border-red-400 bg-red-50 @enderror" />
                @error('name')
                    <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">E-mail</label>
                <input type="email" name="email" value="{{ auth()->user()->email }}"
                       readonly
                       class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-[13.5px] text-slate-500 outline-none" />
                <p class="mt-1 text-[11.5px] text-slate-400">O e-mail não pode ser alterado.</p>
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-2 text-[13px] font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                    Salvar Informações
                </button>
            </div>
        </form>
    </div>

    {{-- Change password --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-[14px] font-bold text-slate-900">Alterar Senha</h2>
            <p class="text-[12px] text-slate-500 mt-0.5">Use uma senha forte com pelo menos 8 caracteres.</p>
        </div>
        <form method="POST" action="{{ route('app.perfil.senha') }}" class="p-6 space-y-4" novalidate>
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Senha atual</label>
                <input type="password" name="current_password"
                       autocomplete="current-password"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('current_password') border-red-400 bg-red-50 @enderror" />
                @error('current_password')
                    <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nova senha</label>
                <input type="password" name="password"
                       autocomplete="new-password"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('password') border-red-400 bg-red-50 @enderror" />
                @error('password')
                    <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Confirmar nova senha</label>
                <input type="password" name="password_confirmation"
                       autocomplete="new-password"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100" />
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="rounded-xl bg-slate-800 px-5 py-2 text-[13px] font-semibold text-white shadow-sm hover:bg-slate-900 transition-colors">
                    Alterar Senha
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
