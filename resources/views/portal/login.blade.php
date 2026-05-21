<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal do Cliente — Future Data</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-50 [font-family:'DM_Sans',sans-serif]" x-data>

<div class="flex min-h-screen">

    {{-- ── Painel visual (apenas desktop) ────────────────────── --}}
    <div class="hidden lg:flex lg:w-[420px] shrink-0 flex-col justify-between bg-[#0d0f16] px-12 py-14">
        <div>
            <img src="{{ asset('images/futuredata.png') }}" class="h-9 w-auto brightness-0 invert" alt="Future Data">
        </div>

        <div>
            <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-500/10 ring-1 ring-blue-500/20">
                <svg class="h-7 w-7 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2M9 12h6M9 16h4"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white">Acompanhe sua <br>ordem de serviço</h2>
            <p class="mt-3 text-[13.5px] leading-relaxed text-slate-500">
                Acesse o portal para acompanhar o status do reparo do seu equipamento em tempo real.
            </p>

            <div class="mt-8 space-y-4">
                @foreach([
                    ['icon' => 'M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11', 'text' => 'Status atualizado em tempo real'],
                    ['icon' => 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z', 'text' => 'Envie mensagens para a equipe'],
                    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z', 'text' => 'Histórico completo do serviço'],
                ] as $item)
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-500/10">
                            <svg class="h-3.5 w-3.5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                            </svg>
                        </div>
                        <p class="text-[12.5px] text-slate-400">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <p class="text-[11px] text-slate-700">
            © {{ date('Y') }} Future Data. Todos os direitos reservados.
        </p>
    </div>

    {{-- ── Formulário ────────────────────────────────────────── --}}
    <div class="flex flex-1 flex-col items-center justify-center px-6 py-14">
        <div class="w-full max-w-[380px]">

            {{-- Mobile: logo --}}
            <div class="mb-10 lg:hidden text-center">
                <img src="{{ asset('images/futuredata.png') }}" class="mx-auto h-9 w-auto" alt="Future Data">
            </div>

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Portal do Cliente</h1>
                <p class="mt-1 text-[13.5px] text-slate-500">Entre com o seu e-mail e senha para acompanhar sua OS.</p>
            </div>

            {{-- Erros --}}
            @if ($errors->any())
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <p class="text-[12.5px] font-medium text-red-700">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('portal.entrar.post') }}" class="space-y-4">
                @csrf

                {{-- E-mail --}}
                <div class="space-y-1.5">
                    <label for="email" class="block text-[13px] font-semibold text-slate-700">E-mail</label>
                    <input
                        id="email" name="email" type="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        autofocus
                        placeholder="seu@email.com"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[13.5px] text-slate-800 placeholder-slate-400 shadow-sm outline-none transition
                               focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20
                               @error('email') border-red-300 @enderror"
                    >
                </div>

                {{-- Senha --}}
                <div class="space-y-1.5">
                    <label for="password" class="block text-[13px] font-semibold text-slate-700">Senha</label>
                    <div x-data="{ show: false }" class="relative">
                        <input
                            id="password" name="password"
                            :type="show ? 'text' : 'password'"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 pr-11 text-[13.5px] text-slate-800 placeholder-slate-400 shadow-sm outline-none transition
                                   focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20
                                   @error('password') border-red-300 @enderror"
                        >
                        <button @click="show = !show" type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 transition hover:text-slate-700">
                            <svg x-show="!show" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="show" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Lembrar --}}
                <label class="flex cursor-pointer items-center gap-2">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 accent-blue-600">
                    <span class="text-[12.5px] text-slate-600">Manter conectado</span>
                </label>

                <button type="submit"
                    class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-[13.5px] font-semibold text-white shadow-sm shadow-blue-600/20 outline-none transition
                           hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Acessar portal
                </button>
            </form>

            {{-- Acesso por token --}}
            <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 px-4 py-4">
                <p class="text-[12.5px] font-semibold text-slate-700">Não tem acesso?</p>
                <p class="mt-0.5 text-[12px] text-slate-500">
                    Você pode acompanhar sua OS pelo link que recebeu por WhatsApp ou e-mail, sem precisar de login.
                </p>
            </div>

            <p class="mt-6 text-center text-[12px] text-slate-400">
                É da equipe?
                <a href="{{ route('auth.entrar') }}" class="font-medium text-slate-600 transition hover:text-slate-900">
                    Acesso interno →
                </a>
            </p>

        </div>
    </div>

</div>

</body>
</html>
