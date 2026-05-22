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

<body class="min-h-screen [font-family:'DM_Sans',sans-serif]" x-data>

<div class="min-h-screen w-full flex flex-col items-center justify-center px-4 sm:px-6 py-12 bg-gradient-to-br from-slate-100 via-blue-50/40 to-slate-100">

    <!-- Logo -->
    <div class="mb-8 sm:mb-10">
        <a href="{{ route('auth.entrar') }}" class="flex items-center justify-center">
            <img src="{{ asset('images/futuredata.png') }}" class="h-10 sm:h-12 w-auto object-contain" alt="Future Data - Informática | Assistência Técnica" />
        </a>
    </div>

    <!-- Card -->
    <div class="w-full max-w-md bg-white rounded-md shadow-2xl ring-1 ring-black/[0.06] px-6 py-10 sm:px-10 sm:py-12">

        <div class="mb-8 text-center">
            <h2 class="text-[#1B3556] text-2xl sm:text-3xl font-bold">Recuperar senha</h2>
            <p class="mt-1.5 text-sm text-gray-500">Informe seu e-mail e enviaremos um link de redefinição.</p>
        </div>

        <!-- Mensagem de sucesso -->
        @if (session('success'))
            <div class="mb-5">
                <div class="bg-emerald-50 border border-emerald-200 rounded-md p-3.5 flex items-start gap-3">
                    <svg class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Mensagem de erro -->
        @if ($errors->any())
            <div class="mb-5">
                <div class="bg-red-50 border border-red-200 rounded-md p-3.5 flex items-start gap-3">
                    <svg class="w-4 h-4 text-[#E31B1B] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('auth.recuperar.post') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email -->
            <div class="flex flex-col gap-1.5">
                <label for="email" class="text-[#1B3556] font-medium text-sm">E-mail</label>
                <input
                    id="email" name="email" type="email"
                    placeholder="seu@email.com"
                    value="{{ old('email') }}"
                    autofocus
                    autocomplete="email"
                    class="h-12 w-full rounded-md border border-gray-200 bg-gray-50 px-4 text-gray-800 placeholder-gray-400 transition-all focus:border-[#1B3556] focus:bg-white focus:ring-2 focus:ring-[#1B3556]/15 focus:outline-none"
                />
            </div>

            <!-- Button -->
            <button type="submit" class="mt-1 h-12 w-full rounded-md bg-[#1B3556] font-semibold text-white shadow-[0_4px_14px_rgba(27,53,86,0.30)] transition-all hover:bg-[#142840]">
                Enviar link de recuperação
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-400">
            <a href="{{ route('auth.entrar') }}" class="text-[#E31B1B] hover:text-[#b81515] transition-colors font-medium">
                ← Voltar ao login
            </a>
        </p>
    </div>

    <!-- Footer -->
    <p class="mt-8 text-xs text-gray-400">
        &copy; {{ date('Y') }} Future Data. Todos os direitos reservados.
    </p>
</div>

</body>
</html>
