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

    {{-- ── Painel lateral escuro (desktop) ──────────────────── --}}
    <div class="hidden lg:flex lg:w-[400px] shrink-0 flex-col justify-between bg-[#0d0f16] px-12 py-14">
        <div>
            <img src="{{ asset('images/futuredata.png') }}" class="h-9 w-auto brightness-0 invert" alt="Future Data">
        </div>

        <div>
            <div class="mb-5 flex h-13 w-13 items-center justify-center rounded-2xl bg-blue-500/10 ring-1 ring-blue-500/20 p-3">
                <svg class="h-7 w-7 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2M9 12h6M9 16h4"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white leading-snug">
                Acompanhe sua<br>ordem de serviço
            </h2>
            <p class="mt-3 text-[13.5px] leading-relaxed text-slate-500">
                Acesse com seu CPF e data de nascimento para ver o status do reparo do seu equipamento.
            </p>

            <div class="mt-8 space-y-4">
                @foreach([
                    ['icon' => 'M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11', 'text' => 'Status da OS em tempo real'],
                    ['icon' => 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z',            'text' => 'Converse com a equipe técnica'],
                    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z',                           'text' => 'Histórico completo do serviço'],
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

            {{-- Dica: acesso por token --}}
            <div class="mt-10 rounded-xl border border-white/[0.07] bg-white/[0.03] px-4 py-3.5">
                <p class="text-[11.5px] font-semibold text-slate-500">Acesso rápido por link</p>
                <p class="mt-0.5 text-[11.5px] text-slate-600 leading-relaxed">
                    Se recebeu um link por WhatsApp ou e-mail, pode clicar diretamente nele para ver sua OS sem precisar fazer login.
                </p>
            </div>
        </div>

        <p class="text-[11px] text-slate-700">© {{ date('Y') }} Future Data.</p>
    </div>

    {{-- ── Formulário ────────────────────────────────────────── --}}
    <div class="flex flex-1 flex-col items-center justify-center px-6 py-14">
        <div class="w-full max-w-[380px]">

            {{-- Mobile: logo --}}
            <div class="mb-10 text-center lg:hidden">
                <img src="{{ asset('images/futuredata.png') }}" class="mx-auto h-9 w-auto" alt="Future Data">
            </div>

            <div class="mb-8">
                <h1 class="text-[22px] font-bold text-slate-900">Portal do Cliente</h1>
                <p class="mt-1 text-[13.5px] text-slate-500">
                    Acesse com seu <strong class="font-semibold text-slate-700">CPF</strong> e
                    <strong class="font-semibold text-slate-700">data de nascimento</strong>.
                </p>
            </div>

            {{-- Flash / info --}}
            @if (session('info'))
                <div class="mb-5 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-[12.5px] text-blue-700">
                    {{ session('info') }}
                </div>
            @endif

            {{-- Erros --}}
            @if ($errors->any())
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <p class="text-[12.5px] font-medium text-red-700">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('portal.entrar.post') }}" class="space-y-5" x-data>
                @csrf

                {{-- CPF / CNPJ --}}
                <div class="space-y-1.5">
                    <label for="cpf_cnpj" class="block text-[13px] font-semibold text-slate-700">
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
                        x-on:input="
                            let v = $el.value.replace(/\D/g,'');
                            if (v.length <= 11) {
                                v = v.replace(/(\d{3})(\d)/,'$1.$2')
                                     .replace(/(\d{3})(\d)/,'$1.$2')
                                     .replace(/(\d{3})(\d{1,2})$/,'$1-$2');
                            } else {
                                v = v.replace(/^(\d{2})(\d)/,'$1.$2')
                                     .replace(/^(\d{2})\.(\d{3})(\d)/,'$1.$2.$3')
                                     .replace(/\.(\d{3})(\d)/,'.$1/$2')
                                     .replace(/(\d{4})(\d)/,'$1-$2');
                            }
                            $el.value = v;
                        "
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[13.5px] text-slate-800 placeholder-slate-400 shadow-sm outline-none transition
                               focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20
                               @error('cpf_cnpj') border-red-300 bg-red-50 @enderror"
                    >
                </div>

                {{-- Data de nascimento --}}
                <div class="space-y-1.5">
                    <label for="data_nascimento" class="block text-[13px] font-semibold text-slate-700">
                        Data de nascimento
                    </label>
                    <input
                        id="data_nascimento"
                        name="data_nascimento"
                        type="date"
                        value="{{ old('data_nascimento') }}"
                        max="{{ now()->toDateString() }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[13.5px] text-slate-800 shadow-sm outline-none transition
                               focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20
                               @error('data_nascimento') border-red-300 bg-red-50 @enderror"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-[13.5px] font-semibold text-white shadow-sm shadow-blue-600/20 outline-none transition
                           hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Acessar minha OS
                </button>
            </form>

            <p class="mt-8 text-center text-[12px] text-slate-400">
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
