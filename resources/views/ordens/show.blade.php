<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document</title>

    <!-- TailwindCSS CDN -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class="flex min-h-screen bg-[#F4F7FB]">
    <aside class="hidden md:flex flex-col w-[280px] bg-[#001121] border-r border-white/5 shadow-2xl">
        {{-- Header --}}
        <div class="h-20 px-6 flex items-center justify-between border-b border-white/5">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/futuredata.png') }}" class="h-10 w-auto object-contain brightness-0 invert" alt="Future Data" />
            </div>
            <button class="h-9 w-9 rounded-lg hover:bg-white/5 transition flex items-center justify-center" aria-label="Recolher menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-7">

            {{-- Principal --}}
            <div>
                <p class="text-[10px] uppercase tracking-[0.18em] text-gray-500 font-semibold px-3 mb-2">Principal</p>
                <ul class="space-y-1">

                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 h-12 rounded-lg bg-[#0A2238] text-white font-medium border border-white/5" aria-current="page">
                            <div class="w-[34px] h-[34px] rounded-lg bg-[#112F4A] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/>
                                    <rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                                </svg>
                            </div>
                            <span class="text-sm">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 h-12 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <div class="w-[34px] h-[34px] rounded-lg bg-white/5 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect width="8" height="4" x="8" y="2" rx="1"/>
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                    <path d="M12 11h4M12 16h4M8 11h.01M8 16h.01"/>
                                </svg>
                            </div>
                            <span class="text-sm">Ordens de Serviço</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 h-12 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <div class="w-[34px] h-[34px] rounded-lg bg-white/5 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </div>
                            <span class="text-sm">Clientes</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 h-12 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <div class="w-[34px] h-[34px] rounded-lg bg-white/5 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                                    <path d="M8 21h8M12 17v4"/>
                                </svg>
                            </div>
                            <span class="text-sm">Equipamentos</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 h-12 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <div class="w-[34px] h-[34px] rounded-lg bg-white/5 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <circle cx="18" cy="15" r="3"/><circle cx="9" cy="7" r="4"/>
                                    <path d="M10 15H6a4 4 0 0 0-4 4v2"/>
                                    <path d="m21.7 16.4-.9-.3m-6.5-2.5-.9-.3m2.3 4.8.3-.9m2.5-6.5.3-.9m-.4 6.9-.4-1m-2.8-6.4-.4-1m-2.5 4.3 1-.4m6.4-2 1-.4"/>
                                </svg>
                            </div>
                            <span class="text-sm">Técnicos</span>
                        </a>
                    </li>

                </ul>
            </div>

            {{-- Financeiro --}}
            <div>
                <p class="text-[10px] uppercase tracking-[0.18em] text-gray-500 font-semibold px-3 mb-2">Financeiro</p>
                <ul class="space-y-1">

                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 h-12 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <div class="w-[34px] h-[34px] rounded-lg bg-white/5 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>
                                </svg>
                            </div>
                            <span class="text-sm">Relatórios</span>
                        </a>
                    </li>

                </ul>
            </div>

            {{-- Configurações --}}
            <div>
                <p class="text-[10px] uppercase tracking-[0.18em] text-gray-500 font-semibold px-3 mb-2">Configurações</p>
                <ul class="space-y-1">
                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 h-12 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <div class="w-[34px] h-[34px] rounded-lg bg-white/5 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </div>
                            <span class="text-sm">Configurações</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- User --}}
        <div class="p-4 border-t border-white/5">
            <div class="flex items-center gap-3 p-3 rounded-lg bg-[#0A2238] border border-white/5">
                <div class="w-10 h-10 rounded-full bg-cyan-500/10 text-cyan-400 font-semibold text-sm flex items-center justify-center shrink-0">CJ</div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-white truncate">Carlos Junior</h3>
                    <p class="text-xs text-gray-500 truncate">Administrador</p>
                </div>
                <button class="text-gray-500 hover:text-red-400 transition" aria-label="Sair">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                    </svg>
                </button>
            </div>
        </div>
    </aside>

    
        <main class="bg-background relative flex min-h-screen w-full flex-1 flex-col md:peer-data-[variant=inset]:m-2 md:peer-data-[variant=inset]:ml-0 md:peer-data-[variant=inset]:rounded-xl md:peer-data-[variant=inset]:shadow-sm md:peer-data-[variant=inset]:peer-data-[state=collapsed]:ml-2">
            <div class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto w-full max-w-7xl space-y-8">
                    <header class="rounded-md bg-white/80 p-4 shadow-sm backdrop-blur sm:p-6">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                                <a href="" aria-label="Voltar para a lista de ordens de serviço" class="text-white bg-sky-800 inline-flex h-10 w-fit items-center justify-center gap-2 rounded-md border border-sky-700/40 px-4 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m12 19-7-7 7-7" />
                                        <path d="M19 12H5" />
                                    </svg>
                                    <span class="text-white">Voltar</span>
                                </a>

                                <div class="min-w-0 space-y-2">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h1 class="font-mono text-2xl font-semibold tracking-tight text-foreground sm:text-3xl">
                                            OS202600057
                                        </h1>
                                        <span class="inline-flex items-center gap-2 rounded-2xl border border-emerald-500/20 bg-gradient-to-r from-[#065F46] to-[#047857] px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm">
                                            <span class="relative flex h-2 w-2">
                                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-75"></span>
                                                <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-300"></span>
                                            </span>

                                            Finalizado
                                        </span>
                                    </div>

                                    <p class="text-sm text-muted-foreground">
                                        Criada em <time datetime="2026-04-28">28 de abril de 2026</time>
                                    </p>
                                </div>
                            </div>

                        <!-- Ações principais -->
                        <nav class="flex flex-wrap items-center gap-3 lg:justify-end" aria-label="Ações da ordem de serviço">

    <!-- Recibo -->
    <a href=""
        class="inline-flex h-11 items-center justify-center gap-2 rounded-md border border-zinc-200 bg-white px-4 text-sm font-medium text-zinc-700 shadow-sm transition-all hover:border-zinc-300 hover:bg-zinc-50 ">

        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
            <path d="M10 9H8" />
            <path d="M16 13H8" />
            <path d="M16 17H8" />
        </svg>

        <span>Recibo</span>
    </a>

    <!-- Registrar entrada -->
    <a href="/assistencia/ordens/30001/entrada"
        class="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-gradient-to-r from-[#0F766E] to-[#115E59] px-4 text-sm font-semibold text-white shadow-md transition-all">

        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
            <polyline points="10 17 15 12 10 7" />
            <line x1="15" x2="3" y1="12" y2="12" />
        </svg>

        <span>Registrar entrada</span>
    </a>

    <!-- WhatsApp -->
    <button type="button"
        class="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-gradient-to-r from-[#065F46] to-[#047857] px-4 text-sm font-semibold text-white shadow-md transition-all">

        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z" />
            <path d="m21.854 2.147-10.94 10.939" />
        </svg>

        <span>WhatsApp</span>
    </button>

    <!-- Imprimir -->
    <button type="button"
        class="inline-flex h-11 items-center justify-center gap-2 rounded-md border border-zinc-200 bg-white px-4 text-sm font-medium text-zinc-700 shadow-sm transition-all hover:border-zinc-300 hover:bg-zinc-50 hover:shadow-md">

        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round">
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
            <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" />
            <rect x="6" y="14" width="12" height="8" rx="1" />
        </svg>

        <span>Imprimir</span>
    </button>

</nav>
                    </div>
                </header>

                <!-- Conteúdo principal -->
                <section class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_380px]" aria-label="Detalhes da ordem de serviço">
                    <div class="space-y-6">
                        <article class="rounded-md bg-white p-5 shadow-sm sm:p-6">
                            <div class="mb-5 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold tracking-tight">Informações do cliente</h2>
                                    <p class="text-sm text-muted-foreground">Dados principais associados a esta ordem de serviço.</p>
                                </div>
                            </div>

                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-md bg-muted/40 p-4">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Nome</dt>
                                    <dd class="mt-1 font-medium text-foreground">João Silva</dd>
                                </div>

                                <div class="rounded-lg bg-muted/40 p-4">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Telefone</dt>
                                    <dd class="mt-1 font-medium text-foreground">(11) 99999-9999</dd>
                                </div>

                                <div class="rounded-lg bg-muted/40 p-4">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-muted-foreground">E-mail</dt>
                                    <dd class="mt-1 truncate font-medium text-foreground">joao@email.com</dd>
                                </div>

                                <div class="rounded-lg bg-muted/40 p-4">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Endereço</dt>
                                    <dd class="mt-1 font-medium text-foreground">Rua Exemplo, 123</dd>
                                </div>
                            </dl>
                        </article>

                        <!-- Equipamento -->
                        <article class="rounded-md bg-white p-5 shadow-sm sm:p-6">
                            <div class="mb-5 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold tracking-tight">Equipamento</h2>
                                    <p class="text-sm text-muted-foreground">Informações técnicas do equipamento associado à ordem de serviço.</p>
                                </div>
                                <span class="inline-flex w-fit items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold text-white dark:border-blue-900/60 dark:bg-blue-950/40 dark:text-blue-300">
                                    Em garantia
                                </span>
                            </div>

                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                <div class="rounded-lg bg-muted/40 p-4">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Tipo</dt>
                                    <dd class="mt-1 font-medium text-foreground">Notebook</dd>
                                </div>

                                <div class="rounded-lg bg-muted/40 p-4">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Marca / Modelo</dt>
                                    <dd class="mt-1 font-medium text-foreground">Dell Inspiron 15</dd>
                                </div>

                                <div class="rounded-lg bg-muted/40 p-4">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-muted-foreground">N.º de série</dt>
                                    <dd class="mt-1 font-mono text-sm font-medium text-foreground">SN-4589-2026</dd>
                                </div>

                                <div class="rounded-lg bg-muted/40 p-4">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Acessórios recebidos</dt>
                                    <dd class="mt-1 font-medium text-foreground">Carregador e mochila</dd>
                                </div>

                                <div class="rounded-lg bg-muted/40 p-4">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Condição de entrada</dt>
                                    <dd class="mt-1 font-medium text-foreground">Sem avarias externas visíveis</dd>
                                </div>

                                <div class="rounded-lg bg-muted/40 p-4">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Património</dt>
                                    <dd class="mt-1 font-medium text-foreground">PAT-00057</dd>
                                </div>
                            </dl>
                        </article>

                        <!-- Detalhes -->
                        <article class="rounded-md bg-white p-5 shadow-sm sm:p-6">
                            <div class="mb-5">
                                <h2 class="text-lg font-semibold tracking-tight">Detalhes</h2>
                                <p class="text-sm text-muted-foreground">Descrição do problema, diagnóstico e solução aplicada.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                                <section class="rounded-md bg-green-800 p-4 text-white">
                                    <h3 class="text-sm font-semibold text-foreground">Problema relatado</h3>
                                    <p class="mt-2 text-sm leading-6 text-muted-foreground">
                                        Cliente informou lentidão constante, aquecimento acima do normal e desligamentos inesperados durante o uso.
                                    </p>
                                </section>

                                <section class="rounded-md  bg-green-500/40 p-4">
                                    <h3 class="text-sm font-semibold text-foreground">Diagnóstico técnico</h3>
                                    <p class="mt-2 text-sm leading-6 text-muted-foreground">
                                        Identificado acúmulo de poeira no sistema de ventilação, pasta térmica ressecada e inicialização com excesso de programas.
                                    </p>
                                </section>
                            </div>
                        </article>

                        <article class="rounded-md bg-white p-5 shadow-sm sm:p-6">
                            <div class="mb-5 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold tracking-tight">Serviços realizados</h2>
                                    <p class="text-sm text-muted-foreground">Itens executados e respectivos valores.</p>
                                </div>
                            </div>

                            <div class="overflow-hidden rounded-sm border border-gray-300">
                                <div class="grid grid-cols-1 gap-3 bg-muted/40 p-4 sm:grid-cols-[1fr_auto] sm:items-center">
                                    <div>
                                        <h3 class="font-medium text-foreground">Manutenção preventiva</h3>
                                        <p class="mt-1 text-sm text-muted-foreground">Realizada em <time datetime="2026-04-28">28/04/2026</time></p>
                                    </div>
                                    <p class="text-left text-base font-semibold text-foreground sm:text-right">R$ 150,00</p>
                                </div>
                            </div>
                        </article>

                        <article class="rounded-xl bg-white p-5 shadow-sm sm:p-6">
                            <div class="mb-5">
                                <h2 class="text-lg font-semibold tracking-tight">Histórico</h2>
                                <p class="text-sm text-muted-foreground">Linha do tempo com as principais movimentações desta ordem.</p>
                            </div>

                            <ol class="relative space-y-5 border-l border-border pl-5">
                                <li class="relative">
                                    <span class="absolute -left-[29px] top-1 flex h-4 w-4 items-center justify-center rounded-full border-2 border-green-500 bg-background"></span>
                                    <div class="rounded-lg bg-muted/40 p-4">
                                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                            <p class="font-medium text-foreground">Ordem finalizada</p>
                                            <time datetime="2026-04-28T16:30" class="text-xs text-muted-foreground">28/04/2026 às 16:30</time>
                                        </div>
                                        <p class="mt-1 text-sm text-muted-foreground">Serviço concluído e recibo disponibilizado para impressão.</p>
                                    </div>
                                </li>

                                <li class="relative">
                                    <span class="absolute -left-[29px] top-1 flex h-4 w-4 items-center justify-center rounded-full border-2 border-blue-500 bg-background"></span>
                                        <div class="rounded-lg bg-muted/40 p-4">
                                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                            <p class="font-medium text-foreground">Serviço em execução</p>
                                            <time datetime="2026-04-28T10:15" class="text-xs text-muted-foreground">28/04/2026 às 10:15</time>
                                        </div>
                                        <p class="mt-1 text-sm text-muted-foreground">Técnico iniciou manutenção preventiva e testes de estabilidade.</p>
                                    </div>
                                </li>

                                <li class="relative">
                                    <span class="absolute -left-[29px] top-1 flex h-4 w-4 items-center justify-center rounded-full border-2 border-amber-500 bg-background"></span>
                                        <div class="rounded-lg bg-muted/40 p-4">
                                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                            <p class="font-medium text-foreground">Entrada registrada</p>
                                            <time datetime="2026-04-28T09:00" class="text-xs text-muted-foreground">28/04/2026 às 09:00</time>
                                        </div>
                                        <p class="mt-1 text-sm text-muted-foreground">Equipamento recebido, conferido e vinculado ao cliente.</p>
                                    </div>
                                </li>
                            </ol>
                        </article>
                    </div>

                    <aside class="space-y-6">
                        <section class="rounded-md bg-white p-5 shadow-sm sm:p-6" aria-labelledby="atualizar-status-title">
                            <div class="mb-5">
                                <h2 id="atualizar-status-title" class="text-lg font-semibold tracking-tight">Atualizar status</h2>
                                <p class="text-sm text-muted-foreground">Altere a etapa atual e registre uma observação interna.</p>
                            </div>

                            <form class="space-y-4" action="" method="POST">
                                <div class="space-y-2">
                                    <label for="status" class="text-sm font-medium text-foreground">Novo status</label>
                                    <select id="status" name="status" class="flex h-10 w-full rounded-md border border-input border-gray-200 bg-white px-3 py-2 text-sm ring-offset-background transition-colors focus-visible:outline-none  focus-visible:ring-ring focus-visible:ring-offset-2">
                                        <option value="entrada">Entrada registrada</option>
                                        <option value="analise">Em análise</option>
                                        <option value="execucao">Em execução</option>
                                        <option value="aguardando_cliente">Aguardando cliente</option>
                                        <option value="finalizado" selected>Finalizado</option>
                                        <option value="cancelado">Cancelado</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label for="observacao_status" class="text-sm font-medium text-foreground">Observação</label>
                                    <textarea id="observacao_status" name="observacao_status" rows="4" placeholder="Ex.: Cliente avisado sobre a finalização do serviço." class="min-h-24 w-full rounded-md border border-input border-gray-200 px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground transition-colors focus-visible:outline-none focus-visible:ring-ring focus-visible:ring-offset-2"></textarea>
                                </div>

                                <button type="submit" class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-md bg-primary px-4 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-ring focus-visible:ring-offset-2" >
                                    Atualizar status
                                </button>
                            </form>
                        </section>

                        <section class="rounded-xl bg-white p-5 shadow-sm sm:p-6" aria-labelledby="resumo-os-title">
                            <h2 id="resumo-os-title" class="text-lg font-semibold tracking-tight">Resumo financeiro</h2>
                            <div class="mt-5 space-y-3 text-sm">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-muted-foreground">Subtotal</span>
                                    <span class="font-medium">R$ 150,00</span>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-muted-foreground">Desconto</span>
                                    <span class="font-medium">R$ 0,00</span>
                                </div>

                                <div class="border-t pt-4">
                                    <div class="flex items-center justify-between gap-4">
                                    <span class="font-semibold text-foreground">Total</span>
                                    <span class="text-xl font-bold text-foreground">R$ 150,00</span>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="rounded-md border border-emerald-900/30 bg-gradient-to-r from-[#065F46] to-[#047857] p-5 text-white shadow-lg" aria-label="Estado da ordem de serviço" >
                            <div class="flex items-start gap-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 6 9 17l-5-5" />
                                    </svg>
                                </div>

                                <!-- Conteúdo -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>

                                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">
                                            Finalizado
                                        </p>
                                    </div>

                                    <h3 class="mt-1 text-lg font-semibold text-white">
                                        Ordem de serviço concluída
                                    </h3>

                                    <p class="mt-1 text-sm text-emerald-100/80">
                                        O recibo já está disponível para impressão ou envio ao cliente.
                                    </p>
                                </div>
                            </div>
                        </section>
                    </aside>
                </section>
            </div>
        </div>
    </main>
</div>

</body>
</html>