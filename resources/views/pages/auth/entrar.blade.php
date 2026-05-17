@extends('layouts.auth')
@section('title', 'Entrar')

@section('content')

{{-- Logo --}}
<a href="/" class="mb-8 group flex items-center gap-3">
    <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-2xl border border-white/[0.08] bg-white/[0.04] transition-all duration-300 group-hover:border-blue-500/30">
        <img src="{{ asset('images/futuredata.png') }}" class="h-8 w-auto object-contain brightness-0 invert" alt="Future Data">
    </div>
    <div>
        <h1 class="text-[15px] font-bold text-white">Future Data</h1>
        <p class="text-[11px] text-slate-500">Gestão de Assistência Técnica</p>
    </div>
</a>

{{-- Card --}}
<div class="w-full max-w-[400px] overflow-hidden rounded-2xl border border-white/[0.08] bg-white/[0.03] shadow-2xl backdrop-blur-xl">

    {{-- Card header --}}
    <div class="border-b border-white/[0.06] px-8 py-6">
        <h2 class="text-[20px] font-bold text-white">Acesse sua conta</h2>
        <p class="mt-1 text-[13px] text-slate-400">Entre com suas credenciais para continuar.</p>
    </div>

    {{-- Card body --}}
    <div class="px-8 py-6">

        @if($errors->any())
            <div class="mb-4 flex items-start gap-3 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                <div>
                    @foreach($errors->all() as $error)
                        <p class="text-[12.5px] text-red-300">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('auth.entrar.post') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="mb-1.5 block text-[12.5px] font-semibold text-slate-300">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="seu@email.com.br"autocomplete="email" class="h-11 w-full rounded-xl border border-white/[0.08] bg-white/[0.05] px-3.5 text-[13.5px] text-white placeholder:text-slate-600 outline-none transition focus:border-blue-500/60 focus:bg-white/[0.08] focus:ring-2 focus:ring-blue-500/20 @error('email') border-red-500/40 @enderror" required />
                <x-form.validation-error field="email" />
            </div>

            {{-- Password --}}
            <div x-data="{ showPwd: false }">
                <label for="password" class="mb-1.5 block text-[12.5px] font-semibold text-slate-300">Senha</label>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        :type="showPwd ? 'text' : 'password'"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                        class="h-11 w-full rounded-xl border border-white/[0.08] bg-white/[0.05] px-3.5 pr-10 text-[13.5px] text-white placeholder:text-slate-600 outline-none transition focus:border-blue-500/60 focus:bg-white/[0.08] focus:ring-2 focus:ring-blue-500/20 @error('password') border-red-500/40 @enderror"
                    >
                    <button
                        type="button"
                        @click="showPwd = !showPwd"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-500 hover:text-slate-300"
                        tabindex="-1"
                    >
                        <svg x-show="!showPwd" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="showPwd" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                <x-form.validation-error field="password" />
            </div>

            {{-- Remember & Forgot --}}
            <div class="flex items-center justify-between">
                <label class="flex cursor-pointer items-center gap-2 select-none">
                    <input
                        name="remember"
                        type="checkbox"
                        class="h-4 w-4 rounded border-white/20 bg-white/10 text-blue-500 accent-blue-500 transition"
                    >
                    <span class="text-[12.5px] text-slate-400">Lembrar de mim</span>
                </label>
                <a href="{{ route('auth.recuperar') }}" class="text-[12.5px] font-medium text-blue-400 transition hover:text-blue-300">
                    Esqueci minha senha
                </a>
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="mt-1 h-11 w-full rounded-xl bg-blue-600 text-[14px] font-bold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/40 active:scale-[0.99]"
            >
                Entrar
            </button>
        </form>
    </div>
</div>

{{-- Footer --}}
<p class="mt-6 text-center text-[12px] text-slate-600">
    &copy; {{ date('Y') }} Future Data e Tecnologia. Todos os direitos reservados.
</p>

@endsection
