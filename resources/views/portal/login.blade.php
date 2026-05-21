<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal do Cliente — Future Data</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen [font-family:'Inter',sans-serif]" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);" x-data>

{{-- Fundo com padrão de pontos --}}
<div class="fixed inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 32px 32px;"></div>

<div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-12">

    {{-- Card principal --}}
    <div class="w-full max-w-[420px]">

        {{-- Logo + nome --}}
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center h-14 w-14 rounded-2xl bg-white/10 ring-1 ring-white/20 mb-4 backdrop-blur">
                <img src="{{ asset('images/futuredata.png') }}" class="h-8 w-auto brightness-0 invert" alt="Future Data">
            </div>
            <h1 class="text-xl font-semibold text-white">Future Data</h1>
            <p class="mt-1 text-sm text-slate-400">Portal do Cliente</p>
        </div>

        {{-- Card do formulário --}}
        <div class="rounded-2xl bg-white shadow-2xl shadow-black/40 overflow-hidden">

            {{-- Header do card --}}
            <div class="px-8 pt-8 pb-6 border-b border-slate-100">
                <h2 class="text-[20px] font-bold text-slate-900">Acessar minha OS</h2>
                <p class="mt-1 text-[13.5px] text-slate-500 leading-relaxed">
                    Informe seus dados para acompanhar o status do reparo.
                </p>
            </div>

            {{-- Formulário --}}
            <div class="px-8 py-7">

                {{-- Erros --}}
                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 rounded-xl bg-red-50 border border-red-100 px-4 py-3.5">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <p class="text-[13px] font-medium text-red-700">{{ $errors->first() }}</p>
                    </div>
                @endif

                @if (session('info'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl bg-blue-50 border border-blue-100 px-4 py-3.5">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <p class="text-[13px] text-blue-700">{{ session('info') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('portal.entrar.post') }}" class="space-y-5">
                    @csrf

                    {{-- CPF --}}
                    <div>
                        <label for="cpf_cnpj" class="mb-2 flex items-center gap-1.5 text-[13px] font-semibold text-slate-700">
                            <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/>
                            </svg>
                            CPF ou CNPJ
                        </label>
                        <input
                            id="cpf_cnpj"
                            name="cpf_cnpj"
                            type="text"
                            value="{{ old('cpf_cnpj') }}"
                            autocomplete="off"
                            autofocus
                            placeholder="000.000.000-00"
                            inputmode="numeric"
                            maxlength="18"
                            x-on:input="
                                let v = $el.value.replace(/\D/g, '');
                                if (v.length <= 11) {
                                    v = v.replace(/(\d{3})(\d)/, '$1.$2')
                                         .replace(/(\d{3})(\d)/, '$1.$2')
                                         .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                                } else {
                                    v = v.replace(/^(\d{2})(\d)/, '$1.$2')
                                         .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                                         .replace(/\.(\d{3})(\d)/, '.$1/$2')
                                         .replace(/(\d{4})(\d)/, '$1-$2');
                                }
                                $el.value = v;
                            "
                            class="w-full rounded-xl border bg-slate-50 px-4 py-3 text-[14px] text-slate-800 placeholder-slate-400 outline-none transition
                                   focus:bg-white focus:border-blue-500 focus:ring-3 focus:ring-blue-500/10
                                   @error('cpf_cnpj') border-red-300 bg-red-50 @else border-slate-200 @enderror"
                        >
                    </div>

                    {{-- Data de nascimento --}}
                    <div>
                        <label for="data_nascimento" class="mb-2 flex items-center gap-1.5 text-[13px] font-semibold text-slate-700">
                            <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                            Data de nascimento
                        </label>
                        <input
                            id="data_nascimento"
                            name="data_nascimento"
                            type="date"
                            value="{{ old('data_nascimento') }}"
                            max="{{ now()->toDateString() }}"
                            class="w-full rounded-xl border bg-slate-50 px-4 py-3 text-[14px] text-slate-800 outline-none transition
                                   focus:bg-white focus:border-blue-500 focus:ring-3 focus:ring-blue-500/10
                                   @error('data_nascimento') border-red-300 bg-red-50 @else border-slate-200 @enderror"
                        >
                    </div>

                    {{-- Botão --}}
                    <button
                        type="submit"
                        class="mt-2 flex w-full items-center justify-center gap-2.5 rounded-xl bg-blue-600 px-5 py-3.5 text-[14.5px] font-semibold text-white
                               shadow-lg shadow-blue-600/30 outline-none transition
                               hover:bg-blue-700 hover:shadow-blue-600/40
                               active:scale-[.98]
                               focus:ring-3 focus:ring-blue-500/30"
                    >
                        Entrar no portal
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Footer do card --}}
            <div class="bg-slate-50 border-t border-slate-100 px-8 py-5">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-100">
                        <svg class="h-3.5 w-3.5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                    </div>
                    <p class="text-[12px] leading-relaxed text-slate-500">
                        <span class="font-semibold text-slate-600">Acesso por link:</span>
                        se recebeu um link pelo WhatsApp ou e-mail, clique diretamente — não precisa de login.
                    </p>
                </div>
            </div>
        </div>

        {{-- Rodapé externo --}}
        <p class="mt-6 text-center text-[12px] text-slate-500">
            É da equipe?
            <a href="{{ route('auth.entrar') }}" class="font-semibold text-slate-300 hover:text-white transition">
                Acesso interno →
            </a>
        </p>

        <p class="mt-4 text-center text-[11px] text-slate-600">
            © {{ date('Y') }} Future Data. Todos os direitos reservados.
        </p>
    </div>
</div>

</body>
</html>
