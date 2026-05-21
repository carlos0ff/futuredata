<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar Senha — Future Data</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
</head>
<body class="flex min-h-screen items-center justify-center bg-[#060810] px-6 [font-family:'DM_Sans',sans-serif]">

<div class="w-full max-w-[360px]">

    <div class="mb-8 text-center">
        <img src="{{ asset('images/futuredata.png') }}" class="mx-auto h-10 w-auto brightness-0 invert" alt="Future Data">
        <h1 class="mt-4 text-xl font-bold text-white">Recuperar senha</h1>
        <p class="mt-1.5 text-[13px] text-slate-500">Informe seu e-mail e enviaremos um link de redefinição.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3">
            <p class="text-[12.5px] font-medium text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3">
            <p class="text-[12.5px] font-medium text-red-400">{{ $errors->first() }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('auth.recuperar.post') }}" class="space-y-4">
        @csrf

        <div class="space-y-1.5">
            <label for="email" class="block text-[13px] font-medium text-slate-400">E-mail</label>
            <input
                id="email" name="email" type="email"
                value="{{ old('email') }}"
                autofocus
                placeholder="seu@email.com"
                class="w-full rounded-xl border border-white/[0.08] bg-white/[0.04] px-4 py-2.5 text-[13.5px] text-white placeholder-slate-600 outline-none transition
                       focus:border-blue-500/50 focus:bg-white/[0.07] focus:ring-2 focus:ring-blue-500/20"
            >
        </div>

        <button type="submit"
            class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-[13.5px] font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-500">
            Enviar link de recuperação
        </button>
    </form>

    <p class="mt-6 text-center text-[12.5px] text-slate-600">
        <a href="{{ route('auth.entrar') }}" class="font-medium text-slate-400 transition hover:text-white">
            ← Voltar ao login
        </a>
    </p>

</div>

</body>
</html>
