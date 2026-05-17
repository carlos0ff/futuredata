@extends('layouts.auth')
@section('title', 'Recuperar senha')

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
        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/15">
            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>
        <h2 class="text-[20px] font-bold text-white">Recuperar senha</h2>
        <p class="mt-1 text-[13px] text-slate-400">
            Informe seu e-mail e enviaremos as instruções para redefinir sua senha.
        </p>
    </div>

    {{-- Card body --}}
    <div class="px-8 py-6">

        @if(session('status'))
            <div class="mb-4 flex items-start gap-3 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/>
                </svg>
                <p class="text-[12.5px] text-emerald-300">{{ session('status') }}</p>
            </div>
        @endif

        <form action="{{ route('auth.recuperar.post') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="mb-1.5 block text-[12.5px] font-semibold text-slate-300">E-mail cadastrado</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    placeholder="seu@email.com.br"
                    autocomplete="email"
                    required
                    class="h-11 w-full rounded-xl border border-white/[0.08] bg-white/[0.05] px-3.5 text-[13.5px] text-white placeholder:text-slate-600 outline-none transition focus:border-blue-500/60 focus:bg-white/[0.08] focus:ring-2 focus:ring-blue-500/20 @error('email') border-red-500/40 @enderror"
                >
                <x-form.validation-error field="email" />
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="mt-1 h-11 w-full rounded-xl bg-blue-600 text-[14px] font-bold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/40 active:scale-[0.99]"
            >
                Enviar instruções
            </button>

            {{-- Back to login --}}
            <a
                href="{{ route('auth.login') }}"
                class="flex items-center justify-center gap-1.5 text-[12.5px] font-medium text-slate-500 transition hover:text-slate-300"
            >
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 19-7-7 7-7"/>
                </svg>
                Voltar para o login
            </a>
        </form>
    </div>
</div>

{{-- Footer --}}
<p class="mt-6 text-center text-[12px] text-slate-600">
    &copy; {{ date('Y') }} Future Data e Tecnologia. Todos os direitos reservados.
</p>

@endsection
