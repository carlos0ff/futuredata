<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal do Cliente — Future Data</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;1,9..144,300;1,9..144,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>

<body class="h-screen overflow-hidden" x-data>

{{-- Toast erro --}}
@if ($errors->any())
<div
    x-data="{
        show: true,
        progress: 100,
        timer: null,
        init() {
            this.timer = setInterval(() => {
                this.progress -= 2;
                if (this.progress <= 0) { this.show = false; clearInterval(this.timer); }
            }, 100);
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-4"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-4"
    class="fixed top-5 right-5 z-50 w-80 rounded-xl bg-white border border-red-200 shadow-xl overflow-hidden"
>
    <div class="flex items-start gap-3 p-4">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 mt-0.5">
            <i class="fas fa-circle-exclamation text-red-500 text-sm"></i>
        </span>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-red-700 mb-0.5">Não foi possível entrar</p>
            <p class="text-sm text-red-600">{{ $errors->first() }}</p>
        </div>
        <button @click="show = false; clearInterval(timer)" class="text-slate-400 hover:text-slate-600 transition-colors p-1 -mt-1 -mr-1">
            <i class="fas fa-xmark text-sm"></i>
        </button>
    </div>
    <div class="h-0.5 bg-red-100">
        <div class="h-full bg-red-400 transition-all duration-100" :style="`width: ${progress}%`"></div>
    </div>
</div>
@endif

{{-- Toast info --}}
@if (session('info'))
<div
    x-data="{
        show: true,
        progress: 100,
        timer: null,
        init() {
            this.timer = setInterval(() => {
                this.progress -= 2;
                if (this.progress <= 0) { this.show = false; clearInterval(this.timer); }
            }, 100);
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-4"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-4"
    class="fixed top-5 right-5 z-50 w-80 rounded-xl bg-white border border-blue-200 shadow-xl overflow-hidden"
>
    <div class="flex items-start gap-3 p-4">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 mt-0.5">
            <i class="fas fa-circle-info text-blue-500 text-sm"></i>
        </span>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-blue-700 mb-0.5">Informação</p>
            <p class="text-sm text-blue-600">{{ session('info') }}</p>
        </div>
        <button @click="show = false; clearInterval(timer)" class="text-slate-400 hover:text-slate-600 transition-colors p-1 -mt-1 -mr-1">
            <i class="fas fa-xmark text-sm"></i>
        </button>
    </div>
    <div class="h-0.5 bg-blue-100">
        <div class="h-full bg-blue-400 transition-all duration-100" :style="`width: ${progress}%`"></div>
    </div>
</div>
@endif

<div class="h-screen flex overflow-hidden">

    {{-- PAINEL ESQUERDO --}}
    <div class="hidden lg:flex lg:w-1/3 flex-col justify-between p-10 relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">

        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-red-600 rounded-full opacity-10 blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
        </div>

        {{-- Logo --}}
        <div class="relative z-10">
            <a href="{{ route('portal.entrar') }}">
                <img src="{{ asset('images/futuredata.png') }}" class="h-8 w-auto object-contain" alt="Future Data">
            </a>
        </div>

        {{-- Citação --}}
        <div class="relative z-10">
            <blockquote>
                <p class="font-bold italic leading-snug text-white mb-5" style="font-family:'Playfair Display',serif;font-size:52px;">
                    "Seu reparo, sempre ao seu alcance."
                </p>
                <p class="text-slate-400 text-base leading-relaxed">
                    Acompanhe em tempo real cada etapa do seu equipamento — da entrada à entrega.
                </p>
            </blockquote>
        </div>

        {{-- Trust indicators --}}
        <div class="relative z-10">
            <div class="flex flex-col gap-3">
                @foreach([
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>', 'text' => 'Dados protegidos com criptografia'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>', 'text' => 'Notificações automáticas via WhatsApp'],
                    ['icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>', 'text' => 'Acompanhe cada etapa em tempo real'],
                ] as $feat)
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/5 ring-1 ring-white/10">
                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $feat['icon'] !!}</svg>
                    </div>
                    <span class="text-[13px] text-slate-400">{{ $feat['text'] }}</span>
                </div>
                @endforeach
            </div>
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
                <p class="text-slate-400 text-xs tracking-widest uppercase mb-2">Portal do Cliente</p>
                <h1 class="text-[2.25rem] font-bold text-slate-900 leading-tight" style="font-family:'Sora',sans-serif;">
                    Acessar minha OS
                </h1>
                <p class="text-slate-500 mt-2 text-sm">Use o código que recebeu via WhatsApp ou no comprovante.</p>
            </div>

            {{-- Formulário --}}
            <form method="POST" action="{{ route('portal.entrar.post') }}" class="space-y-5">
                @csrf

                {{-- Código de acesso --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">Código de acesso</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-key text-slate-400 text-sm"></i>
                        </span>
                        <input
                            id="codigo"
                            name="codigo"
                            type="text"
                            value="{{ old('codigo') }}"
                            autocomplete="off"
                            autofocus
                            placeholder="Ex: OS-2024-0042"
                            class="w-full pl-11 pr-4 py-3 text-sm font-mono rounded-xl border transition-all focus:outline-none tracking-widest uppercase
                                {{ $errors->has('codigo')
                                    ? 'border-red-300 bg-red-50 text-red-700 placeholder-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100'
                                    : 'border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 hover:border-slate-300 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100' }}"
                        >
                    </div>
                    @error('codigo')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botão --}}
                <button type="submit" class="w-full py-3.5 rounded-xl bg-slate-900 text-white text-sm font-bold tracking-wide transition-all hover:bg-slate-700 active:scale-[.98] focus:outline-none focus:ring-2 focus:ring-slate-400" style="font-family:'Sora',sans-serif;">
                    Entrar no portal
                </button>

            </form>

            {{-- Dica --}}
            <div class="mt-6 flex items-start gap-3 rounded-xl bg-blue-50 border border-blue-100 px-4 py-3.5">
                <i class="fas fa-circle-info text-blue-500 text-sm mt-0.5 shrink-0"></i>
                <p class="text-xs text-slate-500 leading-relaxed">
                    <span class="font-semibold text-slate-600">Onde encontrar o código?</span>
                    Ele está no comprovante impresso, no link enviado pelo WhatsApp ou no e-mail da assistência.
                </p>
            </div>

            {{-- Equipe --}}
            <p class="mt-6 text-center text-sm text-slate-500">
                É da equipe?
                <a href="{{ route('auth.entrar') }}" class="font-medium text-slate-800 hover:text-slate-900 transition-colors">
                    Acesso interno →
                </a>
            </p>

            <p class="mt-8 text-center text-xs text-slate-400">
                © {{ date('Y') }} Future Data. Todos os direitos reservados.
            </p>

        </div>
    </div>

</div>

</body>
</html>
