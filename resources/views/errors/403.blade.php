<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Acesso negado · Future Data</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@700&display=swap" rel="stylesheet">
</head>
<body class="flex min-h-screen flex-col items-center justify-center bg-[#f4f5f7] px-4 [font-family:'DM_Sans',sans-serif] antialiased">

    <div class="text-center">
        {{-- Number --}}
        <p class="font-mono text-[120px] font-extrabold leading-none tracking-tight text-slate-200 sm:text-[160px]">403</p>

        {{-- Icon --}}
        <div class="-mt-4 mb-6 flex justify-center">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-amber-200 bg-amber-50 shadow-sm">
                <svg class="h-7 w-7 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
        </div>

        <h1 class="text-[22px] font-bold text-slate-900">Acesso negado</h1>
        <p class="mt-2 max-w-sm text-[14px] text-slate-500">
            Você não tem permissão para acessar esta página. Entre em contato com o administrador caso acredite que isso seja um erro.
        </p>

        @if($exception->getMessage())
            <p class="mt-3 inline-flex items-center gap-2 rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-[12.5px] text-amber-700">
                {{ $exception->getMessage() }}
            </p>
        @endif

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
        </div>
    </div>

    <p class="absolute bottom-6 text-[12px] text-slate-400">
        &copy; {{ date('Y') }} Future Data e Tecnologia
    </p>
</body>
</html>
