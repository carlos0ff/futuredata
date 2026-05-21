<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal do Cliente — Future Data</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <style>
        .input-base {
            width: 100%;
            border-radius: 0.75rem;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            color: #1e293b;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .input-base::placeholder { color: #94a3b8; }
        .input-base:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96,165,250,.15);
        }
        .input-base.error { border-color: #fca5a5; background: #fff5f5; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp .3s ease both; }
    </style>
</head>
<body class="min-h-screen bg-[#f8fafc] [font-family:'DM_Sans',sans-serif]" x-data>

<div class="flex min-h-screen">

    {{-- ═══ LADO ESQUERDO — visual / branding ═══════════════════ --}}
    <div class="relative hidden lg:flex lg:w-[440px] xl:w-[500px] shrink-0 flex-col overflow-hidden bg-[#0d0f16]">

        {{-- Gradiente decorativo --}}
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-32 -left-32 h-80 w-80 rounded-full bg-blue-600/20 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-64 w-64 rounded-full bg-indigo-600/10 blur-3xl"></div>
        </div>

        {{-- Conteúdo --}}
        <div class="relative flex flex-1 flex-col justify-between px-12 py-12">

            {{-- Topo: logo --}}
            <img src="{{ asset('images/futuredata.png') }}" class="h-8 w-auto brightness-0 invert opacity-90" alt="Future Data">

            {{-- Centro: headline --}}
            <div>
                {{-- Ícone --}}
                <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl border border-blue-500/20 bg-blue-500/10">
                    <svg class="h-7 w-7 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2M9 12h6M9 16h4"/>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold leading-snug text-white">
                    Acompanhe sua<br>
                    <span class="text-blue-400">ordem de serviço</span>
                </h1>
                <p class="mt-4 text-[14px] leading-relaxed text-slate-400">
                    Veja em tempo real o status do reparo do seu equipamento sem complicação.
                </p>

                {{-- Features --}}
                <div class="mt-9 space-y-4">
                    @foreach([
                        ['icon' => 'M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11',
                         'label' => 'Status atualizado pela equipe técnica'],
                        ['icon' => 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z',
                         'label' => 'Troca de mensagens com o técnico'],
                        ['icon' => 'M9 12h6M9 16h4M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2m-4-2a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2',
                         'label' => 'Histórico completo da OS'],
                    ] as $f)
                        <div class="flex items-center gap-3.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/[0.05] ring-1 ring-white/[0.08]">
                                <svg class="h-4 w-4 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}"/>
                                </svg>
                            </div>
                            <span class="text-[13px] text-slate-400">{{ $f['label'] }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Dica token --}}
                <div class="mt-10 rounded-xl border border-white/[0.07] bg-white/[0.03] p-4">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                        <div>
                            <p class="text-[12px] font-semibold text-slate-400">Acesso por link</p>
                            <p class="mt-0.5 text-[12px] leading-relaxed text-slate-600">
                                Se recebeu um link pelo WhatsApp ou e-mail, clique diretamente nele — não precisa de login.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rodapé --}}
            <p class="text-[11px] text-slate-700">© {{ date('Y') }} Future Data. Todos os direitos reservados.</p>
        </div>
    </div>

    {{-- ═══ LADO DIREITO — formulário ══════════════════════════════ --}}
    <div class="flex flex-1 flex-col items-center justify-center px-6 py-14">
        <div class="fade-up w-full max-w-[400px]">

            {{-- Mobile: logo --}}
            <div class="mb-10 text-center lg:hidden">
                <img src="{{ asset('images/futuredata.png') }}" class="mx-auto h-9 w-auto" alt="Future Data">
            </div>

            {{-- Cabeçalho do form --}}
            <div class="mb-8">
                <h2 class="text-[26px] font-bold text-slate-900">Entrar no portal</h2>
                <p class="mt-1.5 text-[14px] text-slate-500">
                    Use seu <span class="font-medium text-slate-700">CPF</span> e
                    <span class="font-medium text-slate-700">data de nascimento</span> para acessar.
                </p>
            </div>

            {{-- Alertas --}}
            @if (session('info'))
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <p class="text-[13px] text-blue-700">{{ session('info') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <p class="text-[13px] font-medium text-red-700">{{ $errors->first() }}</p>
                </div>
            @endif

            {{-- Formulário --}}
            <form method="POST" action="{{ route('portal.entrar.post') }}" class="space-y-5">
                @csrf

                {{-- CPF / CNPJ --}}
                <div>
                    <label for="cpf_cnpj" class="mb-1.5 block text-[13.5px] font-semibold text-slate-700">
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
                        class="input-base @error('cpf_cnpj') error @enderror"
                    >
                </div>

                {{-- Data de nascimento --}}
                <div>
                    <label for="data_nascimento" class="mb-1.5 block text-[13.5px] font-semibold text-slate-700">
                        Data de nascimento
                    </label>
                    <input
                        id="data_nascimento"
                        name="data_nascimento"
                        type="date"
                        value="{{ old('data_nascimento') }}"
                        max="{{ now()->toDateString() }}"
                        class="input-base @error('data_nascimento') error @enderror"
                    >
                </div>

                {{-- Botão --}}
                <button
                    type="submit"
                    class="mt-1 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-[14px] font-semibold text-white shadow-md shadow-blue-600/25 outline-none transition
                           hover:bg-blue-700 active:scale-[.98] focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Acessar portal
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                    </svg>
                </button>
            </form>

            {{-- Separador --}}
            <div class="my-7 flex items-center gap-3">
                <div class="h-px flex-1 bg-slate-200"></div>
                <span class="text-[12px] text-slate-400">ou</span>
                <div class="h-px flex-1 bg-slate-200"></div>
            </div>

            {{-- Acesso por link --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                <p class="text-[12.5px] font-semibold text-slate-700">Tem um link da sua OS?</p>
                <p class="mt-1 text-[12px] text-slate-500">Cole o link que recebeu por WhatsApp ou e-mail na barra de endereço do navegador para acesso direto.</p>
            </div>

            {{-- Link equipe --}}
            <p class="mt-7 text-center text-[12.5px] text-slate-400">
                É da equipe técnica?
                <a href="{{ route('auth.entrar') }}" class="font-medium text-slate-600 underline-offset-2 hover:underline hover:text-slate-900 transition">
                    Acesso interno
                </a>
            </p>

        </div>
    </div>

</div>

</body>
</html>
