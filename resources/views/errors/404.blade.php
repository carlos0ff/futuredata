<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Página não encontrada · Future Data</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@700&display=swap" rel="stylesheet">
</head>
<body class="flex min-h-screen flex-col items-center justify-center bg-[#f4f5f7] px-4 [font-family:'DM_Sans',sans-serif] antialiased">

    <div class="text-center">
        {{-- Number --}}
        <p class="font-mono text-[120px] font-extrabold leading-none tracking-tight text-slate-200 sm:text-[160px]">404</p>

        {{-- Icon --}}
        <div class="-mt-4 mb-6 flex justify-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-200 bg-white shadow-sm">
                <svg class="h-7 w-7 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="11" cy="11" r="8"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 11h6M11 8v6"/>
                </svg>
            </div>
        </div>

        <h1 class="text-[22px] font-bold text-slate-900">Página não encontrada</h1>
        <p class="mt-2 max-w-sm text-[14px] text-slate-500">
            A página que você está procurando não existe ou foi movida para outro endereço.
        </p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a
                href="{{ url('/') }}"
                class="inline-flex h-10 items-center gap-2 rounded-xl bg-blue-600 px-5 text-[13.5px] font-semibold text-white shadow-sm transition hover:bg-blue-700"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 19-7-7 7-7"/>
                </svg>
                Voltar ao início
            </a>
            <a
                href="{{ url()->previous() }}"
                class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-[13.5px] font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                Página anterior
            </a>
        </div>
    </div>

    <p class="absolute bottom-6 text-[12px] text-slate-400">
        &copy; {{ date('Y') }} Future Data e Tecnologia
    </p>
</body>
</html>
