<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar Senha — Future Data</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>

<body class="h-screen overflow-hidden" x-data>

<div class="h-screen flex overflow-hidden">

    {{-- PAINEL ESQUERDO --}}
    <div class="hidden lg:flex lg:w-1/3 flex-col justify-between p-10 relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">

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

    {{-- PAINEL DIREITO --}}
    <div class="w-full lg:w-2/3 flex flex-col items-center justify-center px-4 sm:px-8 overflow-y-auto bg-white">

        <div class="w-full max-w-md">

            {{-- Logo mobile --}}
            <div class="lg:hidden mb-8 flex justify-center">
                <img src="{{ asset('images/futuredata.png') }}" class="h-10 w-auto object-contain" alt="Future Data">
            </div>

            {{-- Cabeçalho --}}
            <div class="mb-8">
                <p class="text-slate-400 text-xs tracking-widest uppercase mb-2">Recuperação de acesso</p>
                <h1 class="text-[2.25rem] font-bold text-slate-900 leading-tight" style="font-family:'Sora',sans-serif;">
                    Recuperar senha
                </h1>
                <p class="text-slate-500 mt-2 text-sm">Informe seu e-mail e enviaremos um link de redefinição.</p>
            </div>

            {{-- Sucesso --}}
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 flex items-start gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 mt-0.5">
                        <i class="fas fa-circle-check text-emerald-500 text-sm"></i>
                    </span>
                    <p class="text-sm text-emerald-700 pt-1">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Erros --}}
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 flex items-start gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 mt-0.5">
                        <i class="fas fa-circle-exclamation text-red-500 text-sm"></i>
                    </span>
                    <p class="text-sm text-red-600 pt-1">{{ $errors->first() }}</p>
                </div>
            @endif

            {{-- Formulário --}}
            <form method="POST" action="{{ route('auth.recuperar.post') }}" class="space-y-5">
                @csrf

                {{-- E-mail --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">E-mail</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-400 text-sm"></i>
                        </span>
                        <input
                            id="email" name="email" type="email"
                            value="{{ old('email') }}"
                            placeholder="seu@email.com"
                            autofocus
                            autocomplete="email"
                            class="w-full pl-11 pr-4 py-3 text-sm rounded-xl border transition-all focus:outline-none
                                {{ $errors->has('email')
                                    ? 'border-red-300 bg-red-50 text-red-700 placeholder-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100'
                                    : 'border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 hover:border-slate-300 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100' }}"
                        />
                    </div>
                </div>

                {{-- Botão --}}
                <button type="submit"
                    class="w-full py-3.5 rounded-xl bg-slate-900 text-white text-sm font-bold tracking-wide transition-all hover:bg-slate-700 active:scale-[.98] focus:outline-none focus:ring-2 focus:ring-slate-400"
                    style="font-family:'Sora',sans-serif;">
                    Enviar link de recuperação
                </button>

            </form>

            {{-- Voltar --}}
            <p class="mt-6 text-center text-sm">
                <a href="{{ route('auth.entrar') }}" class="text-slate-500 hover:text-slate-800 font-medium transition-colors">
                    ← Voltar ao login
                </a>
            </p>

            {{-- Footer mobile --}}
            <p class="lg:hidden mt-8 text-center text-xs text-slate-400">
                © {{ date('Y') }} Future Data. Todos os direitos reservados.
            </p>

        </div>
    </div>

</div>

</body>
</html>
