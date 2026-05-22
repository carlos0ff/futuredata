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

<body class="min-h-screen [font-family:'DM_Sans',sans-serif]" x-data>

<div class="min-h-screen w-full flex flex-col items-center justify-center px-4 sm:px-6 py-12 bg-gradient-to-br from-slate-100 via-blue-50/40 to-slate-100">

    <!-- Logo -->
    <div class="mb-8 sm:mb-10">
        <a href="{{ route('auth.entrar') }}" class="flex items-center justify-center">
            <img src="{{ asset('images/futuredata.png') }}" class="h-10 sm:h-12 w-auto object-contain" alt="Future Data - Informática | Assistência Técnica " />
        </a>
    </div>

    <!-- Card -->
    <div class="w-full max-w-md bg-white rounded-md shadow-2xl ring-1 ring-black/[0.06] px-6 py-10 sm:px-10 sm:py-12">

        <div class="mb-8 text-center">
            <h2 class="text-[#1B3556] text-2xl sm:text-3xl font-bold">Acesse sua conta</h2>
            <p class="mt-1.5 text-sm text-gray-500">Bem-vindo de volta!</p>
        </div>

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
        <form method="POST" action="{{ route('auth.entrar.post') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[#1B3556] font-medium text-sm">E-mail</label>
                <input name="email" type="email" placeholder="seu@email.com" value="{{ old('email') }}" class="h-12 w-full rounded-md border border-gray-200 bg-gray-50 px-4 text-gray-800 placeholder-gray-400 transition-all focus:border-[#1B3556] focus:bg-white focus:ring-2 focus:ring-[#1B3556]/15 focus:outline-none" autocomplete="email"  />
            </div>

            <!-- Senha -->
            <div class="flex flex-col gap-1.5" x-data="{ show: false }">
                <div class="flex items-center justify-between">
                    <label class="text-[#1B3556] font-medium text-sm">Senha</label>
                    <a href="{{ route('auth.recuperar') }}" class="text-xs text-[#E31B1B] hover:text-[#b81515] transition-colors">Esqueci minha senha</a>
                </div>

                <div class="relative">
                    <input name="password" :type="show ? 'text' : 'password'" placeholder="••••••••" autocomplete="current-password" class="h-12 w-full rounded-md border border-gray-200 bg-gray-50 px-4 pr-11 text-gray-800 placeholder-gray-400 tracking-widest transition-all focus:border-[#1B3556] focus:bg-white focus:ring-2 focus:ring-[#1B3556]/15 focus:outline-none focus:tracking-normal" />
                    
                    <button type="button" @click="show = !show" tabindex="-1" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-[#1B3556] transition-colors">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember -->
            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                <input name="remember" type="checkbox" class="w-4 h-4 rounded accent-[#1B3556]" />
                <span class="text-sm text-gray-600">Lembrar de mim</span>
            </label>

            <!-- Button -->
            <button type="submit" class="mt-1 h-12 w-full rounded-md bg-[#1B3556] font-semibold text-white shadow-[0_4px_14px_rgba(27,53,86,0.30)] transition-all hover:bg-[#142840]" >
                Entrar
            </button>
        </form>
    </div>

    <!-- Footer  -->
    <p class="mt-8 text-xs text-gray-400">
        &copy; {{ date('Y') }} Future Data. Todos os direitos reservados.
    </p>
</div>

</body>
</html>
