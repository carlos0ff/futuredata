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
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            Dashboard
        </a>

        <div>
            
    <a href="#"
       class="group mb-px flex cursor-pointer items-center gap-2.5 rounded-lg bg-slate-800 px-2.5 py-[9px] text-[13.5px] font-medium text-slate-100 no-underline transition-all duration-200 hover:bg-slate-700"
       data-collapse-trigger="os-menu">

        <svg class="h-[17px] w-[17px] shrink-0 opacity-100 transition-opacity group-hover:opacity-100"
             xmlns="http://www.w3.org/2000/svg"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             stroke-width="2">
            <rect width="8" height="4" x="8" y="2" rx="1"/>
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
            <path d="M12 11h4M12 16h4M8 11h.01M8 16h.01"/>
        </svg>

        Ordens de Serviço

        <svg class="ml-auto h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-y-[1px]"
             xmlns="http://www.w3.org/2000/svg"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             stroke-width="2.5">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="m6 9 6 6 6-6"/>
        </svg>
    </a>

    <div id="os-menu" class="mb-1 pl-9">

        <a href="#"
           class="block rounded-md px-2 py-[7px] text-[13px] text-slate-400 no-underline transition-all duration-150 hover:bg-slate-800 hover:text-slate-100">
            Lista de OS
        </a>

        <a href="#"
           class="block rounded-md px-2 py-[7px] text-[13px] font-semibold text-blue-500 no-underline transition-all duration-150 hover:bg-slate-800 hover:text-blue-400">
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
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
            </svg>
            Usuários
        </a>
        <a href="#" class="mb-px flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 no-underline transition hover:bg-slate-800 hover:text-slate-100">
            <svg class="h-[17px] w-[17px] shrink-0 opacity-80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                <path d="M12 17h.01"/>
            </svg>
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
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="m9 12 2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9S3 16.97 3 12 7.03 3 12 3s9 4.03 9 9"/>
                        </svg>
                        Em teste
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button class="inline-flex cursor-pointer items-center gap-[7px] rounded-lg border border-slate-200 bg-white px-4 py-2 text-[13.5px] font-semibold text-slate-900 no-underline transition hover:bg-slate-50">
                        <svg class="h-[15px] w-[15px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                            <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/>
                            <rect x="6" y="14" width="12" height="8" rx="1"/>
                        </svg>
                        Imprimir
                    </button>
                    <button class="inline-flex cursor-pointer items-center gap-[7px] rounded-lg border border-slate-200 bg-white px-4 py-2 text-[13.5px] font-semibold text-slate-900 no-underline transition hover:bg-slate-50">
                        Ações
                        <svg class="h-[15px] w-[15px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>
                    <button class="inline-flex cursor-pointer items-center gap-[7px] rounded-lg bg-blue-600 px-4 py-2 text-[13.5px] font-semibold text-white no-underline transition hover:bg-blue-700">
                        <svg class="h-[15px] w-[15px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Editar OS
                    </button>
                </div>
            </div>

            <!-- Stepper -->
            <div class="mb-5 overflow-x-auto rounded-xl border border-slate-200 bg-white px-6 py-5">
                <div class="flex min-w-[640px] items-start">
                    <div class="relative flex flex-1 flex-col items-center after:absolute after:left-[calc(50%+18px)] after:right-[calc(-50%+18px)] after:top-[17px] after:h-0.5 after:bg-green-600 after:content-['']">
                        <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-green-600 text-white">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/>
                            </svg>
                        </div>
                        <div class="mt-2 text-center">
                            <h4 class="text-[12.5px] font-semibold text-slate-900">Recebimento</h4>
                            <p class="mt-px text-[11px] text-slate-400 [font-family:'DM_Mono',monospace]">12/05/2024 14:30</p>
                        </div>
                    </div>

                    <div class="relative flex flex-1 flex-col items-center after:absolute after:left-[calc(50%+18px)] after:right-[calc(-50%+18px)] after:top-[17px] after:h-0.5 after:bg-green-600 after:content-['']">
                        <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-green-600 text-white">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/>
                            </svg>
                        </div>
                        <div class="mt-2 text-center">
                            <h4 class="text-[12.5px] font-semibold text-slate-900">Em análise</h4>
                            <p class="mt-px text-[11px] text-slate-400 [font-family:'DM_Mono',monospace]">12/05/2024 16:20</p>
                        </div>
                    </div>

                    <div class="relative flex flex-1 flex-col items-center after:absolute after:left-[calc(50%+18px)] after:right-[calc(-50%+18px)] after:top-[17px] after:h-0.5 after:bg-slate-200 after:content-['']">
                        <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-blue-600 text-white">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="m9 12 2 2 4-4"/>
                                    <circle cx="12" cy="12" r="10"/>
                                </svg>
                            </div>
                        <div class="mt-2 text-center"><h4 class="text-[12.5px] font-semibold text-slate-900">Em teste</h4>
                        <p class="mt-px text-[11px] text-slate-400 [font-family:'DM_Mono',monospace]">13/05/2024 10:15</p>
                    </div>
                </div>
                <div class="relative flex flex-1 flex-col items-center after:absolute after:left-[calc(50%+18px)] after:right-[calc(-50%+18px)] after:top-[17px] after:h-0.5 after:bg-slate-200 after:content-['']">
                    <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-slate-200 text-slate-400">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                    </div>
                    <div class="mt-2 text-center">
                        <h4 class="text-[12.5px] font-semibold text-slate-400">Finalização</h4>
                        <p class="mt-px text-[11px] text-slate-400 [font-family:'DM_Mono',monospace]">–</p>
                    </div>
                </div>

                <div class="relative flex flex-1 flex-col items-center after:absolute after:left-[calc(50%+18px)] after:right-[calc(-50%+18px)] after:top-[17px] after:h-0.5 after:bg-slate-200 after:content-['']">
                    <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-slate-200 text-slate-400">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="5" cy="12" r="1"/>
                            <circle cx="12" cy="12" r="1"/>
                            <circle cx="19" cy="12" r="1"/>
                        </svg>
                    </div>
                    <div class="mt-2 text-center">
                        <h4 class="text-[12.5px] font-semibold text-slate-400">Pronto para entrega</h4>
                        <p class="mt-px text-[11px] text-slate-400 [font-family:'DM_Mono',monospace]">–</p>
                    </div>
                </div>

                <div class="relative flex flex-1 flex-col items-center">
                    <div class="relative z-10 flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-slate-200 text-slate-400">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="5" cy="12" r="1"/>
                            <circle cx="12" cy="12" r="1"/>
                            <circle cx="19" cy="12" r="1"/>
                        </svg>
                    </div>
                    <div class="mt-2 text-center">
                        <h4 class="text-[12.5px] font-semibold text-slate-400">Entregue</h4>
                        <p class="mt-px text-[11px] text-slate-400 [font-family:'DM_Mono',monospace]">–</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid -->
        
    </main>
</div>

{{-- Core javascript --}}
<script src="{{ asset('js/main.js') }}" type="text/javascript"></script>

</body>
</html>
