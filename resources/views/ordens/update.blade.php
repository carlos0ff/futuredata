<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OS #12458 — AssistPro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest" defer></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased [font-family:'DM_Sans',sans-serif]">

<!-- ══ SIDEBAR ══ -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 hidden min-h-screen w-[220px] min-w-[220px] flex-col bg-slate-950 lg:flex">
    <div class="flex items-center gap-2.5 border-b border-white/5 px-[18px] pb-[18px] pt-5">
        <img src="{{ asset('images/futuredata.png') }}" class="h-10 w-auto object-contain brightness-0 invert" alt="Future Data" />
    </div>

    <nav class="flex-1 overflow-y-auto px-2.5 py-3 [scrollbar-width:thin] [scrollbar-color:rgba(255,255,255,.1)_transparent]">
        <a href="#" class="mb-px flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 no-underline transition hover:bg-slate-800 hover:text-slate-100">
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <rect width="7" height="9" x="3" y="3" rx="1"/>
                <rect width="7" height="5" x="14" y="3" rx="1"/>
                <rect width="7" height="9" x="14" y="12" rx="1"/>
                <rect width="7" height="5" x="3" y="16" rx="1"/>
            </svg>
            Dashboard
        </a>

        <div class="relative">
            <a href="#" class="group flex cursor-pointer items-center gap-2.5 rounded-md px-3 py-[10px] text-[13.5px] font-medium text-slate-200 no-underline transition-all hover:bg-slate-800/50 hover:text-white" data-collapse-trigger="os-menu">
                
                <svg class="h-[17px] w-[17px] shrink-0 text-slate-400 transition-colors group-hover:text-slate-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect width="8" height="4" x="8" y="2" rx="1"/>
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    <path d="M12 11h4M12 16h4M8 11h.01M8 16h.01"/>
                </svg>
                
                Ordens de Serviço
                
                <svg class="ml-auto h-3.5 w-3.5 text-slate-400 transition-all duration-200 group-hover:rotate-180 group-hover:text-slate-200" 
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                </svg>
            </a>

            <!-- Submenu com linha vertical -->
            <div id="os-menu" class="relative mb-1 pl-9 before:absolute before:left-[18px] before:top-1 before:bottom-1 before:w-px before:bg-slate-700">
                <a href="#" class="block rounded-md px-3 py-[7px] text-[13px] text-slate-400 transition-all hover:bg-slate-800/50 hover:text-slate-100 hover:pl-4">
                    Lista de OS
                </a>
                <a href="#" class="block rounded-md px-3 py-[7px] text-[13px] font-semibold text-blue-400 transition-all hover:bg-slate-800/50 hover:text-blue-300 hover:pl-4">
                    Nova OS
                </a>
            </div>
        </div>

        <a href="#" class="mb-px flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 no-underline transition hover:bg-slate-800 hover:text-slate-100">
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Clientes
        </a>
        <a href="#" class="mb-px flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 no-underline transition hover:bg-slate-800 hover:text-slate-100">
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
            Equipamentos
        </a>
        <a href="#" class="mb-px flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 no-underline transition hover:bg-slate-800 hover:text-slate-100">
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
            Financeiro
        </a>
        <a href="#" class="mb-px flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 no-underline transition hover:bg-slate-800 hover:text-slate-100">
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/></svg>
            Estoque
        </a>
        <a href="#" class="mb-px flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 no-underline transition hover:bg-slate-800 hover:text-slate-100">
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8M16 13H8M16 17H8"/></svg>
            Relatórios
        </a>
        <a href="#" class="mb-px flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 no-underline transition hover:bg-slate-800 hover:text-slate-100">
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            Configurações
        </a>
        <a href="#" class="mb-px flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 no-underline transition hover:bg-slate-800 hover:text-slate-100">
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z"/><path d="M7 7h.01"/></svg>
            Automação
        </a>
        <a href="#" class="mb-px flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 no-underline transition hover:bg-slate-800 hover:text-slate-100">
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            Usuários
        </a>
        <a href="#" class="mb-px flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 no-underline transition hover:bg-slate-800 hover:text-slate-100">
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
            Suporte
        </a>
    </nav>

    <div class="border-t border-white/5 px-2.5 py-3">
        <div class="flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-2 transition hover:bg-slate-800">
            <div class="flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-xs font-bold text-white">CJ</div>
            <div>
                <h4 class="text-[13px] font-semibold text-slate-100">Carlos Junior</h4>
                <p class="text-[11px] text-slate-400">Administrador</p>
            </div>
            <svg class="ml-auto h-3.5 w-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
        </div>
    </div>
</aside>

<!-- ══ MAIN ══ -->
<div class="flex min-h-screen flex-1 flex-col lg:ml-[220px]">
    <header class="flex h-14 items-center gap-3 border-b border-slate-200 bg-white px-6">
        <button id="mobile-menu-button" type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 transition hover:bg-slate-200 lg:hidden" aria-label="Abrir menu lateral">
            <i data-lucide="menu" class="h-[18px] w-[18px]"></i>
        </button>
        <div class="flex flex-1 items-center gap-1.5 text-[13px] text-slate-500">
            <a href="#" class="text-slate-500 no-underline hover:text-slate-900">Ordens de Serviço</a>
            <span class="text-slate-400">›</span>
            <span class="font-semibold text-slate-900">OS #12458</span>
        </div>
        <div class="relative hidden sm:block">
            <i data-lucide="search" class="pointer-events-none absolute left-2.5 top-1/2 h-[15px] w-[15px] -translate-y-1/2 text-slate-400"></i>
            <input type="text" class="w-[220px] rounded-lg border border-slate-200 bg-slate-50 py-[7px] pl-[34px] pr-3 text-[13px] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-600" placeholder="Buscar...">
        </div>
        <button type="button" class="relative flex h-9 w-9 cursor-pointer items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-200 hover:text-slate-900">
            <i data-lucide="bell" class="h-[17px] w-[17px]"></i>
            <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full border-2 border-white bg-red-500 text-[9px] font-bold leading-none text-white">3</span>
        </button>
        <button type="button" class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-200 hover:text-slate-900">
            <i data-lucide="circle-help" class="h-[17px] w-[17px]"></i>
        </button>
    </header>

    <main class="flex-1 px-6 py-5 pb-8">
        <div class="mb-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3.5">
                <h1 class="text-[26px] font-bold tracking-[-0.5px] text-slate-900">OS #12458</h1>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9S3 16.97 3 12 7.03 3 12 3s9 4.03 9 9"/></svg>
                    Em teste
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button class="inline-flex cursor-pointer items-center gap-[7px] rounded-lg border border-slate-200 bg-white px-4 py-2 text-[13.5px] font-semibold text-slate-900 no-underline transition hover:bg-slate-50">
                    <svg class="h-[15px] w-[15px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
                    Imprimir
                </button>
                <button class="inline-flex cursor-pointer items-center gap-[7px] rounded-lg border border-slate-200 bg-white px-4 py-2 text-[13.5px] font-semibold text-slate-900 no-underline transition hover:bg-slate-50">
                    Ações
                    <svg class="h-[15px] w-[15px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                </button>
                <button class="inline-flex cursor-pointer items-center gap-[7px] rounded-lg bg-blue-600 px-4 py-2 text-[13.5px] font-semibold text-white no-underline transition hover:bg-blue-700">
                    <svg class="h-[15px] w-[15px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Editar OS
                </button>
            </div>
        </div>

        <!-- Stepper -->
        <div class="mb-5 overflow-x-auto rounded-xl border border-slate-200 bg-white px-6 py-5">
            <div class="flex min-w-[640px] items-start">
                <div class="relative flex flex-1 flex-col items-center after:absolute after:left-[calc(50%+18px)] after:right-[calc(-50%+18px)] after:top-[17px] after:h-0.5 after:bg-green-600 after:content-['']">
                    <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-green-600 text-white"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg></div>
                    <div class="mt-2 text-center"><h4 class="text-[12.5px] font-semibold text-slate-900">Recebimento</h4><p class="mt-px text-[11px] text-slate-400 [font-family:'DM_Mono',monospace]">12/05/2024 14:30</p></div>
                </div>
                <div class="relative flex flex-1 flex-col items-center after:absolute after:left-[calc(50%+18px)] after:right-[calc(-50%+18px)] after:top-[17px] after:h-0.5 after:bg-green-600 after:content-['']">
                    <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-green-600 text-white"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg></div>
                    <div class="mt-2 text-center"><h4 class="text-[12.5px] font-semibold text-slate-900">Em análise</h4><p class="mt-px text-[11px] text-slate-400 [font-family:'DM_Mono',monospace]">12/05/2024 16:20</p></div>
                </div>
                <div class="relative flex flex-1 flex-col items-center after:absolute after:left-[calc(50%+18px)] after:right-[calc(-50%+18px)] after:top-[17px] after:h-0.5 after:bg-slate-200 after:content-['']">
                    <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-blue-600 text-white"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg></div>
                    <div class="mt-2 text-center"><h4 class="text-[12.5px] font-semibold text-slate-900">Em teste</h4><p class="mt-px text-[11px] text-slate-400 [font-family:'DM_Mono',monospace]">13/05/2024 10:15</p></div>
                </div>
                <div class="relative flex flex-1 flex-col items-center after:absolute after:left-[calc(50%+18px)] after:right-[calc(-50%+18px)] after:top-[17px] after:h-0.5 after:bg-slate-200 after:content-['']">
                    <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-slate-200 text-slate-400"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg></div>
                    <div class="mt-2 text-center"><h4 class="text-[12.5px] font-semibold text-slate-400">Finalização</h4><p class="mt-px text-[11px] text-slate-400 [font-family:'DM_Mono',monospace]">–</p></div>
                </div>
                <div class="relative flex flex-1 flex-col items-center after:absolute after:left-[calc(50%+18px)] after:right-[calc(-50%+18px)] after:top-[17px] after:h-0.5 after:bg-slate-200 after:content-['']">
                    <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-slate-200 text-slate-400"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="5" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/></svg></div>
                    <div class="mt-2 text-center"><h4 class="text-[12.5px] font-semibold text-slate-400">Pronto para entrega</h4><p class="mt-px text-[11px] text-slate-400 [font-family:'DM_Mono',monospace]">–</p></div>
                </div>
                <div class="relative flex flex-1 flex-col items-center">
                    <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-slate-200 text-slate-400"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="5" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/></svg></div>
                    <div class="mt-2 text-center"><h4 class="text-[12.5px] font-semibold text-slate-400">Entregue</h4><p class="mt-px text-[11px] text-slate-400 [font-family:'DM_Mono',monospace]">–</p></div>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid items-start gap-5 xl:grid-cols-[1fr_320px]">
            
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm mb-4">
                    <h3 class="mb-4 text-[16px] font-extrabold text-slate-900">Informações do equipamento</h3>
                    <div class="flex gap-4">
                        <div class="flex h-[76px] w-[86px] shrink-0 items-center justify-center rounded-md bg-slate-100">
                            <div class="h-14 w-16 rounded bg-gradient-to-br from-[#050b18] via-[#0d3c76] to-[#44a5ff] shadow-lg"></div>
                        </div>
                        <div class="text-[14px] leading-6">
                            <p class="font-extrabold text-slate-900">Notebook Dell Inspiron 15</p>
                            <p><span class="text-slate-600">Nº de Série:</span> 5CD1234</p>
                            <p><span class="text-slate-600">Modelo:</span> Inspiron 15 3000</p>
                            <p><span class="text-slate-600">Cor:</span> Preto</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-0 border-b border-slate-200 px-1">
                    <button data-tab="historico" class="tab-button mb-[-1px] flex cursor-pointer items-center gap-[7px] border-b-2 border-transparent bg-transparent px-4 py-3.5 text-[13.5px] font-medium text-slate-500 transition hover:text-slate-900"><svg class="h-[15px] w-[15px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>Histórico</button>
                    <button data-tab="orcamento" class="tab-button mb-[-1px] flex cursor-pointer items-center gap-[7px] border-b-2 border-blue-600 bg-transparent px-4 py-3.5 text-[13.5px] font-semibold text-blue-600 transition"><svg class="h-[15px] w-[15px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>Orçamento</button>
                    <button data-tab="arquivos" class="tab-button mb-[-1px] flex cursor-pointer items-center gap-[7px] border-b-2 border-transparent bg-transparent px-4 py-3.5 text-[13.5px] font-medium text-slate-500 transition hover:text-slate-900"><svg class="h-[15px] w-[15px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>Arquivos</button>
                    <button data-tab="mensagens" class="tab-button mb-[-1px] flex cursor-pointer items-center gap-[7px] border-b-2 border-transparent bg-transparent px-4 py-3.5 text-[13.5px] font-medium text-slate-500 transition hover:text-slate-900"><svg class="h-[15px] w-[15px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Mensagens</button>
                </div>

                <div data-tab-panel="historico" class="hidden px-5 py-5 pb-6">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-[13px] text-slate-600">
                        Histórico da ordem de serviço ainda não preenchido.
                    </div>
                </div>
                <div data-tab-panel="arquivos" class="hidden px-5 py-5 pb-6">
                    <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-[13px] text-slate-600">
                        Nenhum arquivo anexado até o momento.
                    </div>
                </div>
                <div data-tab-panel="mensagens" class="hidden px-5 py-5 pb-6">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-[13px] text-slate-600">
                        Nenhuma mensagem registada para esta OS.
                    </div>
                </div>
                <div id="orcamento-panel" data-tab-panel="orcamento" class="px-5 py-5 pb-6">
                    <div class="mb-3.5 flex flex-wrap items-center justify-between gap-2.5">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h3 class="text-sm font-semibold text-slate-900">Orçamento enviado em 13/05/2024 às 10:20</h3>
                            <span class="inline-flex items-center rounded-md border border-green-200 bg-green-50 px-2.5 py-[3px] text-[11.5px] font-semibold text-green-700">Aguardando aprovação do cliente</span>
                        </div>
                        <button class="inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-[13px] py-[7px] text-[12.5px] font-medium text-slate-500 transition hover:bg-slate-50 hover:text-slate-900"><svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"/></svg>Reenviar orçamento</button>
                    </div>

                    <div class="mb-4 overflow-hidden rounded-[10px] border border-slate-200">
                        <table class="w-full border-collapse text-[13.5px]">
                            <thead>
                                <tr>
                                    <th class="w-10 border-b border-slate-200 bg-slate-50 px-3.5 py-2.5 text-left text-xs font-semibold uppercase tracking-[0.04em] text-slate-500">Item</th>
                                    <th class="border-b border-slate-200 bg-slate-50 px-3.5 py-2.5 text-left text-xs font-semibold uppercase tracking-[0.04em] text-slate-500">Descrição</th>
                                    <th class="border-b border-slate-200 bg-slate-50 px-3.5 py-2.5 text-right text-xs font-semibold uppercase tracking-[0.04em] text-slate-500">Quantidade</th>
                                    <th class="border-b border-slate-200 bg-slate-50 px-3.5 py-2.5 text-right text-xs font-semibold uppercase tracking-[0.04em] text-slate-500">Valor unitário</th>
                                    <th class="border-b border-slate-200 bg-slate-50 px-3.5 py-2.5 text-right text-xs font-semibold uppercase tracking-[0.04em] text-slate-500">Valor total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="5" class="bg-slate-50 px-3.5 py-2"><span class="inline-flex items-center gap-[7px] text-[12.5px] font-bold text-violet-600"><svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/></svg>Peças e componentes</span></td></tr>
                                <tr><td class="border-b border-slate-100 px-3.5 py-[11px] text-slate-400">1</td><td class="border-b border-slate-100 px-3.5 py-[11px]">Tela 15.6&quot; LED Slim</td><td class="border-b border-slate-100 px-3.5 py-[11px] text-right">1</td><td class="border-b border-slate-100 px-3.5 py-[11px] text-right">R$ 250,00</td><td class="border-b border-slate-100 px-3.5 py-[11px] text-right font-semibold">R$ 250,00</td></tr>
                                <tr><td class="border-b border-slate-100 px-3.5 py-[11px] text-slate-400">2</td><td class="border-b border-slate-100 px-3.5 py-[11px]">Conector de carga</td><td class="border-b border-slate-100 px-3.5 py-[11px] text-right">1</td><td class="border-b border-slate-100 px-3.5 py-[11px] text-right">R$ 80,00</td><td class="border-b border-slate-100 px-3.5 py-[11px] text-right font-semibold">R$ 80,00</td></tr>
                                <tr><td colspan="5" class="bg-slate-50 px-3.5 py-2"><span class="inline-flex items-center gap-[7px] text-[12.5px] font-bold text-cyan-600"><svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>Serviços</span></td></tr>
                                <tr><td class="border-b border-slate-100 px-3.5 py-[11px] text-slate-400">3</td><td class="border-b border-slate-100 px-3.5 py-[11px]">Diagnóstico técnico</td><td class="border-b border-slate-100 px-3.5 py-[11px] text-right">1</td><td class="border-b border-slate-100 px-3.5 py-[11px] text-right">R$ 80,00</td><td class="border-b border-slate-100 px-3.5 py-[11px] text-right font-semibold">R$ 80,00</td></tr>
                                <tr><td class="px-3.5 py-[11px] text-slate-400">4</td><td class="px-3.5 py-[11px]">Mão de obra especializada</td><td class="px-3.5 py-[11px] text-right">1</td><td class="px-3.5 py-[11px] text-right">R$ 120,00</td><td class="px-3.5 py-[11px] text-right font-semibold">R$ 120,00</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- <div class="mb-4 flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3.5 py-2.5 text-[13px] text-blue-600">
                        <svg class="h-[15px] w-[15px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 16v-4M12 8h.01"/>
                        </svg>
                        Prazo estimado de conclusão: 2 a 3 dias úteis após aprovação.
                    </div> -->

                    <div class="ml-auto w-full sm:w-[280px]">
                        <div class="flex items-center justify-between py-[7px] text-[13.5px]"><span class="text-slate-500">Subtotal</span><span class="font-semibold">R$ 530,00</span></div>
                        <div class="flex items-center justify-between py-[7px] text-[13.5px]"><span class="text-slate-500">Desconto</span><span class="font-semibold text-red-600">- R$ 30,00</span></div>
                        <div class="mt-2 flex items-center justify-between border-t-2 border-slate-200 pt-3"><span class="text-[15px] font-bold text-slate-900">Total</span><span class="text-lg font-extrabold text-slate-900">R$ 500,00</span></div>
                    </div>

                    <!-- <div class="mt-5 rounded-[10px] border border-slate-200 bg-slate-50 px-[18px] py-4">
                        <h3 class="mb-1.5 text-sm font-bold">Aprovação do orçamento</h3>
                        <p class="mb-3.5 text-[13px] text-slate-500">Ao aprovar este orçamento, você autoriza o início do reparo do seu equipamento.</p>
                        <div class="flex flex-wrap gap-2.5">
                            <button class="inline-flex cursor-pointer items-center gap-[7px] rounded-lg bg-green-600 px-5 py-2.5 text-[13.5px] font-bold text-white transition hover:bg-green-700"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg>Aprovar orçamento</button>
                            <button class="inline-flex cursor-pointer items-center gap-[7px] rounded-lg border border-slate-200 bg-white px-5 py-2.5 text-[13.5px] font-semibold text-slate-500 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/></svg>Recusar orçamento</button>
                        </div>
                    </div> -->
                </div>
            </div>

            <!-- RIGHT -->
            <aside>
                <div class="mb-3.5 rounded-xl border border-slate-200 bg-white px-[18px] py-4">
                    <div class="mb-3.5 text-sm font-bold">Informações do equipamento</div>
                    <div class="flex items-start gap-3">
                        <div class="flex h-14 w-[68px] shrink-0 items-center justify-center rounded-md bg-gradient-to-br from-sky-950 to-slate-950"><svg class="h-8 w-8 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg></div>
                        <div><h4 class="mb-1 text-[13.5px] font-bold">Notebook Dell Inspiron 15</h4><p class="mb-0.5 text-xs text-slate-500">Nº de Série: 5CD1234</p><p class="mb-0.5 text-xs text-slate-500">Modelo: Inspiron 15 3000</p><p class="mb-0.5 text-xs text-slate-500">Cor: Preto</p></div>
                    </div>
                </div>

                <div class="mb-3.5 rounded-xl border border-slate-200 bg-white px-[18px] py-4">
                    <div class="mb-3 flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50"><svg class="h-[18px] w-[18px] text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div>
                        <div class="text-sm font-bold">Previsão de entrega</div>
                    </div>
                    <div class="text-[11.5px] font-medium text-slate-400">Data prevista</div>
                    <div class="text-[22px] font-extrabold tracking-[-0.5px] text-green-600 [font-family:'DM_Mono',monospace]">17/05/2024</div>
                    <div class="mb-3 text-xs text-slate-500">Sexta-feira</div>
                    <button class="flex w-full cursor-pointer items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 p-[9px] text-[12.5px] font-medium text-slate-500 transition hover:bg-slate-200 hover:text-slate-900"><svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>Alterar data prevista</button>
                </div>

                <div class="mb-3.5 rounded-xl border border-slate-200 bg-white px-[18px] py-4">
                    <div class="mb-3.5 text-sm font-bold">Técnico responsável</div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-xs font-bold text-white">CE</div>
                            <div>
                                <div class="text-[13.5px] font-semibold">Carlos Eduardo</div>
                                <span class="mt-0.5 inline-block rounded-[5px] border border-green-200 bg-green-50 px-2 py-0.5 text-[11px] font-semibold text-green-700">Técnico em informática</span>
                            </div>
                        </div>
                        
                        <svg class="h-7 w-7 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>
                        </svg>
                    </div>
                </div>

                <div class="mb-3.5 rounded-md border border-slate-200 bg-white px-[18px] py-4">
                    <div class="mb-3.5 text-sm font-bold">Resumo financeiro</div>
                    <div class="flex items-center justify-between border-b border-slate-100 py-[7px] text-[13px]">
                        <span class="text-slate-500">Serviços</span>
                        <span class="font-semibold">R$ 200,00</span>
                    </div>

                    <div class="flex items-center justify-between border-b border-slate-100 py-[7px] text-[13px]">
                        <span class="text-slate-500">Peças</span>
                        <span class="font-semibold">R$ 300,00</span>
                    </div>

                    <div class="flex items-center justify-between py-[7px] text-[13px]">
                        <span class="text-slate-500">Desconto</span>
                        <span class="font-semibold text-red-600">- R$ 30,00</span>
                    </div>

                    <div class="mt-2 flex items-center justify-between border-t-2 border-slate-200 pt-2.5">
                        <span class="text-[15px] font-bold">Total</span
                        <span class="text-base font-extrabold">R$ 500,00</span>
                    </div>
                </div>

                <div class="rounded-md border border-emerald-900 bg-gradient-to-br from-emerald-800 to-emerald-700 px-[18px] py-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[10px] bg-white/15"><svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg></div>
                        <div>
                            <h4 class="mb-0.5 text-[13px] font-bold text-white">Precisa entrar em contato com o cliente?</h4>
                            <p class="text-xs text-white/70">Inicie uma conversa rápida pelo WhatsApp.</p>
                        </div>
                    </div>

                    <a target="_blank" href="https://api.whatsapp.com/send?phone=5599999999999&text={{ urlencode('Olá, gostaria de falar com você.') }}" class="mt-3 flex w-full cursor-pointer items-center justify-center gap-[7px] rounded-lg border border-white/20 bg-white/15 p-2.5 text-[13px] font-semibold text-white transition hover:bg-white/25">
                        <svg class="h-[15px] w-[15px]" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                        </svg>
                        Conversar no WhatsApp
                    </a>

                </div>
            </aside>
        </div>
    </main>
</div>

{{-- Core javascript --}}
<script src="{{ asset('js/main.js') }}" type="text/javascript"></script>

</body>
</html>
