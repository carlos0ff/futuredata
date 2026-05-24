<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Entrar — Future Data</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>

<body class="h-screen overflow-hidden" x-data>

<div class="h-screen flex overflow-hidden">

    {{-- ══════════════════════════════════════════ --}}
    {{-- PAINEL ESQUERDO                            --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="hidden lg:flex lg:w-1/3 flex-col justify-between p-10 relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">

        {{-- Glows decorativos --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-red-600 rounded-full opacity-10 blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
        </div>

        {{-- Logo --}}
        <div class="relative z-10">
            <a href="{{ route('auth.entrar') }}">
                <img src="{{ asset('images/futuredata.png') }}" class="h-8 w-auto object-contain" alt="Future Data">
            </a>
        </div>

        {{-- Citação --}}
        <div class="relative z-10">
            <blockquote>
                <p class="text-3xl font-bold italic leading-snug text-white mb-5" style="font-family:'Playfair Display',serif;">
                    "Assistência técnica de excelência, sempre ao seu lado."
                </p>
                <p class="text-slate-400 text-base leading-relaxed">
                    Gestão completa de ordens de serviço, integração WhatsApp e suporte ao cliente em tempo real.
                </p>
            </blockquote>
        </div>

        {{-- Autor + Copyright --}}
        <div class="relative z-10 space-y-4">
            <div class="flex items-center gap-3">
                <img
                    src="https://media.licdn.com/dms/image/v2/D4D03AQF7xzwKIYFwwQ/profile-displayphoto-crop_800_800/B4DZw4KyXeKwAI-/0/1770468879303?e=1781136000&v=beta&t=Oilvt6HaBEHqwFh_pLWFFyYDfJ4ntX-glf0wzHR6sGM"
                    class="h-11 w-11 rounded-full ring-2 ring-slate-600 object-cover flex-shrink-0"
                    alt="Gustavo Web"
                >
                <div>
                    <p class="text-slate-500 text-[10px] tracking-widest uppercase mb-0.5">Produzido por</p>
                    <p class="text-white text-sm font-semibold leading-none" style="font-family:'Sora',sans-serif;">Gustavo Web</p>
                    <p class="text-slate-400 text-xs mt-0.5">Fundador da BlackDev</p>
                </div>
            </div>
            <p class="text-slate-600 text-xs">© {{ date('Y') }} Future Data. Todos os direitos reservados.</p>
        </div>

    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- PAINEL DIREITO                             --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="w-full lg:w-2/3 flex flex-col items-center justify-center px-4 sm:px-8 overflow-y-auto bg-white">

        <div class="w-full max-w-md">

            {{-- Logo mobile --}}
            <div class="lg:hidden mb-8 flex justify-center">
                <img src="{{ asset('images/futuredata.png') }}" class="h-10 w-auto object-contain" alt="Future Data">
            </div>

            {{-- Cabeçalho --}}
            <div class="mb-8">
                <p class="text-slate-400 text-xs tracking-widest uppercase mb-2">Bem-vindo de volta</p>
                <h1 class="text-[2.25rem] font-bold text-slate-900 leading-tight" style="font-family:'Sora',sans-serif;">
                    Acesse sua conta
                </h1>
                <p class="text-slate-500 mt-2 text-sm">Continue de onde parou na plataforma.</p>
            </div>

            {{-- Erros --}}
            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 flex items-start gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 mt-0.5">
                        <i class="fas fa-circle-exclamation text-red-500 text-sm"></i>
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-red-700 mb-1">Não foi possível entrar</p>
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-600">{{ $error }}</p>
                        @endforeach
                    </div>
                    <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors p-1">
                        <i class="fas fa-xmark text-sm"></i>
                    </button>
                </div>
            @endif

            {{-- Formulário --}}
            <form method="POST" action="{{ route('auth.entrar.post') }}" class="space-y-5">
                @csrf

                {{-- E-mail --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">E-mail</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-400 text-sm"></i>
                        </span>
                        <input
                            name="email" type="email"
                            value="{{ old('email') }}"
                            placeholder="seu@email.com"
                            autocomplete="email"
                            class="w-full pl-11 pr-4 py-3 text-sm rounded-xl border transition-all focus:outline-none
                                {{ $errors->has('email')
                                    ? 'border-red-300 bg-red-50 text-red-700 placeholder-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100'
                                    : 'border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 hover:border-slate-300 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100' }}"
                        />
                    </div>
                </div>

                {{-- Senha --}}
                <div class="space-y-1.5" x-data="{ show: false }">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">Senha</label>
                        <a href="{{ route('auth.recuperar') }}" class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">
                            Esqueceu a senha?
                        </a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-400 text-sm"></i>
                        </span>
                        <input
                            name="password"
                            :type="show ? 'text' : 'password'"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            class="w-full pl-11 pr-11 py-3 text-sm rounded-xl border transition-all focus:outline-none
                                {{ $errors->has('password')
                                    ? 'border-red-300 bg-red-50 text-red-700 placeholder-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100'
                                    : 'border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 hover:border-slate-300 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100' }}"
                        />
                        <button type="button" @click="show = !show" tabindex="-1"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Lembrar --}}
                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                    <input name="remember" type="checkbox" class="w-4 h-4 rounded border-slate-300 accent-slate-800 cursor-pointer">
                    <span class="text-sm text-slate-500">Lembrar de mim</span>
                </label>

                {{-- Botão --}}
                <button type="submit"
                    class="w-full py-3.5 rounded-xl bg-slate-900 text-white text-sm font-bold tracking-wide transition-all hover:bg-slate-700 active:scale-[.98] focus:outline-none focus:ring-2 focus:ring-slate-400"
                    style="font-family:'Sora',sans-serif;">
                    Entrar no Sistema
                </button>

            </form>

            {{-- Footer mobile --}}
            <p class="lg:hidden mt-8 text-center text-xs text-slate-400">
                © {{ date('Y') }} Future Data. Todos os direitos reservados.
            </p>

        </div>
    </div>

</div>

</body>
</html>
