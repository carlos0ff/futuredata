<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Erro interno · Future Data</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@700&display=swap" rel="stylesheet">
</head>
<body class="flex min-h-screen flex-col items-center justify-center bg-[#f4f5f7] px-4 [font-family:'DM_Sans',sans-serif] antialiased">

    <div class="text-center">
        {{-- Number --}}
        <p class="font-mono text-[120px] font-extrabold leading-none tracking-tight text-slate-200 sm:text-[160px]">500</p>

        {{-- Icon --}}
        <div class="-mt-4 mb-6 flex justify-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-red-200 bg-red-50 shadow-sm">
                <svg class="h-7 w-7 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
        </div>

        <h1 class="text-[22px] font-bold text-slate-900">Erro interno do servidor</h1>
        <p class="mt-2 max-w-sm text-[14px] text-slate-500">
            Algo deu errado no nosso lado. Nossa equipe já foi notificada e está trabalhando para resolver o problema.
        </p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ url('/') }}" class="inline-flex h-10 items-center gap-2 rounded-xl bg-blue-600 px-5 text-[13.5px] font-semibold text-white shadow-sm transition hover:bg-blue-700" >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 19-7-7 7-7"/>
                </svg>
                Voltar ao início
            </a>
            <button onclick="window.location.reload()" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-[13.5px] font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50" >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 3v5h-5"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H3v5"/>
                </svg>
                Tentar novamente
            </button>
        </div>
    </div>

    <p class="absolute bottom-6 text-[12px] text-slate-400">
        &copy; {{ date('Y') }} Future Data e Tecnologia
    </p>
</body>
</html>
