<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Entrar — Future Data</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-[#060810] [font-family:'DM_Sans',sans-serif]" x-data>

<div class="flex min-h-screen">

    {{-- ── Formulário ────────────────────────────────────────── --}}
    <div class="flex flex-1 flex-col items-center justify-center px-6 py-14">
        <div class="w-full max-w-[360px]">

            {{-- Logo --}}
            <div class="mb-10 text-center">
                <img src="{{ asset('images/futuredata.png') }}" class="mx-auto h-10 w-auto brightness-0 invert" alt="Future Data">
                <p class="mt-3 text-[13px] text-slate-500">Acesso para equipe interna</p>
            </div>

            {{-- Erros de validação --}}
            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3">
                    <p class="text-[12.5px] font-medium text-red-400">{{ $errors->first() }}</p>
                </div>
            @endif

            {{-- Flash --}}
            @if (session('error'))
                <div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3">
                    <p class="text-[12.5px] font-medium text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('auth.entrar.post') }}" class="space-y-4">
                @csrf

                {{-- E-mail --}}
                <div class="space-y-1.5">
                    <label for="email" class="block text-[13px] font-medium text-slate-400">E-mail</label>
                    <input
                        id="email" name="email" type="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        autofocus
                        placeholder="seu@email.com"
                        class="w-full rounded-xl border border-white/[0.08] bg-white/[0.04] px-4 py-2.5 text-[13.5px] text-white placeholder-slate-600 outline-none transition
                               focus:border-blue-500/50 focus:bg-white/[0.07] focus:ring-2 focus:ring-blue-500/20
                               @error('email') border-red-500/40 @enderror"
                    >
                </div>

                {{-- Senha --}}
                <div class="space-y-1.5">
                    <label for="password" class="block text-[13px] font-medium text-slate-400">Senha</label>
                    <div x-data="{ show: false }" class="relative">
                        <input
                            id="password" name="password"
                            :type="show ? 'text' : 'password'"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-xl border border-white/[0.08] bg-white/[0.04] px-4 py-2.5 pr-11 text-[13.5px] text-white placeholder-slate-600 outline-none transition
                                   focus:border-blue-500/50 focus:bg-white/[0.07] focus:ring-2 focus:ring-blue-500/20
                                   @error('password') border-red-500/40 @enderror"
                        >
                        <button @click="show = !show" type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-600 transition hover:text-slate-300">
                            <svg x-show="!show" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="show" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Lembrar + Esqueci --}}
                <div class="flex items-center justify-between">
                    <label class="flex cursor-pointer items-center gap-2">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-white/20 bg-white/[0.05] accent-blue-500">
                        <span class="text-[12.5px] text-slate-500">Lembrar de mim</span>
                    </label>
                    <a href="{{ route('auth.recuperar') }}" class="text-[12.5px] font-medium text-blue-400 transition hover:text-blue-300">
                        Esqueci a senha
                    </a>
                </div>

                <button type="submit"
                    class="mt-1 w-full rounded-xl bg-blue-600 px-4 py-2.5 text-[13.5px] font-semibold text-white shadow-lg shadow-blue-600/20 outline-none transition
                           hover:bg-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-[#060810]">
                    Entrar
                </button>
            </form>

            {{-- Link portal --}}
            <p class="mt-8 text-center text-[12px] text-slate-600">
                É cliente?
                <a href="{{ route('portal.entrar') }}" class="font-medium text-slate-400 transition hover:text-white">
                    Acessar portal do cliente →
                </a>
            </p>

        </div>
    </div>

    {{-- ── Painel visual (apenas desktop) ────────────────────── --}}
    <div class="hidden lg:flex lg:flex-1 flex-col items-center justify-center border-l border-white/[0.04] bg-gradient-to-br from-blue-600/10 via-[#0a0d1a] to-[#060810] px-12">
        <div class="max-w-sm text-center">
            <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600/15 ring-1 ring-blue-500/20">
                <svg class="h-8 w-8 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-white">Future Data</h2>
            <p class="mt-2 text-[13.5px] leading-relaxed text-slate-500">
                Sistema de gestão para assistências técnicas. Controle total de OS, clientes, estoque e financeiro.
            </p>
            <div class="mt-8 flex flex-col gap-2.5 text-left">
                @foreach(['Ordens de serviço com histórico completo', 'Portal de acompanhamento para clientes', 'Relatórios financeiros em tempo real', 'Gestão de estoque e equipamentos'] as $feat)
                    <div class="flex items-center gap-2.5 text-[12.5px] text-slate-500">
                        <div class="h-1.5 w-1.5 rounded-full bg-blue-500"></div>
                        {{ $feat }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

</body>
</html>
