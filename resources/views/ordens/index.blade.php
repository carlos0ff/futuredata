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

    <main class="flex-1 p-6 lg:p-8">
        
        {{-- --}}
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-foreground">Ordens de Serviço</h1>
                    <p class="text-sm text-muted-foreground mt-0.5">57 ordens encontradas</p>
                </div>

                <a href="">
                    <button class="inline-flex items-center gap-2 px-4 h-9 rounded-md text-sm font-medium bg-[#00294e] text-white hover:bg-[#00294e]/90 transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Nova Ordem
                    </button>
                </a>
            </div>

            <div class="bg-white text-[#030c15] flex flex-col gap-6 rounded-xl shadow-sm">
                <div class="px-6 pt-4 pb-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" data-loc="client/src/pages/ServiceOrders.tsx:57">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                           <input type="text" class="h-9 w-full rounded-md border border-input border-gray-800/40 bg-transparent pl-9 pr-3 text-sm placeholder:text-[#576570] outline-none transition-shadow " placeholder="Buscar..." />
                        </div>
                        
                        <div class="relative w-full sm:w-56">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 shrink-0 text-[#576570] pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                            </svg>

                            <select class="h-9 w-full rounded-md border border-input border-gray-800/40 bg-transparent pl-9 pr-8 text-sm text-[#576570] outline-none appearance-none transition-shadow ">
                                <option>Todos os status</option>
                                <option>Aberta</option>
                                <option>Em andamento</option>
                                <option>Concluída</option>
                                <option>Cancelada</option>
                            </select>

                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 shrink-0 opacity-50 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- OS --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <!-- Cabeçalho opcional -->
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Ordens de Serviço</h2>
                        <p class="">Lista atualizada em tempo real</p>
                    </div>
                    <span class="text-xs text-gray-500">Total: 3 OS</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="text-left px-6 py-4 font-medium text-gray-400 text-xs uppercase tracking-wider">Nº OS</th>
                                <th class="text-left px-6 py-4 font-medium text-gray-400 text-xs uppercase tracking-wider">Cliente</th>
                                <th class="text-left px-6 py-4 font-medium text-gray-400 text-xs uppercase tracking-wider">Equipamento / Defeito</th>
                                <th class="text-left px-6 py-4 font-medium text-gray-400 text-xs uppercase tracking-wider">Status</th>
                                <th class="text-left px-6 py-4 font-medium text-gray-400 text-xs uppercase tracking-wider">Data de Abertura</th>
                                <th class="text-center px-6 py-4 font-medium text-gray-400 text-xs uppercase tracking-wider w-32">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            <!-- Item 1 -->
                            <tr class="group hover:bg-gray-50 transition-all duration-200">
                                <td class="px-6 py-5">
                                    <span class="font-mono font-semibold text-[#001121] text-sm">OS202600057</span>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-md flex items-center justify-center text-blue-600 font-medium text-sm">
                                            MS
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">Mariana Souza</p>
                                            <p class="text-xs text-gray-500">(81) 98888-0000</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-700">iPhone 13</span>
                                        <span class="text-xs text-gray-500">Apple • Tela quebrada</span>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-blue-100 text-blue-700 text-xs font-semibold">
                                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                                        Em teste
                                    </span>
                                </td>

                                <td class="px-6 py-5 text-sm text-gray-500 tabular-nums">
                                    10/05/2026
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <button class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-sm font-medium whitespace-nowrap text-[#001121] hover:bg-[#f0f4f8] hover:text-[#0ea5e9] cursor-pointer transition-colors group">
                                        Ver detalhes
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 -translate-x-0.5 opacity-0 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M5 12h14M12 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>

                            <!-- Item 2 -->
                            <tr class="group hover:bg-gray-50 transition-all duration-200">
                                <td class="px-6 py-5">
                                    <span class="font-mono font-semibold text-[#001121] text-sm">OS202600058</span>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-rose-100 to-pink-100 rounded-md flex items-center justify-center text-rose-600 font-medium text-sm">
                                            MS
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">Mariana Souza</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-700">iPhone 13</span>
                                        <span class="text-xs text-gray-500">Apple</span>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-blue-100 text-blue-700 text-xs font-semibold">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        Em teste
                                    </span>
                                </td>

                                <td class="px-6 py-5 text-sm text-gray-500 tabular-nums">
                                    09/05/2026
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <button class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-sm font-medium whitespace-nowrap text-[#001121] hover:bg-[#f0f4f8] hover:text-[#0ea5e9] cursor-pointer transition-colors group">
                                        Ver detalhes
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 -translate-x-0.5 opacity-0 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M5 12h14M12 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>

                            <!-- Item 3 -->
                            <tr class="group hover:bg-gray-50 transition-all duration-200">
                                <td class="px-6 py-5">
                                    <span class="font-mono font-semibold text-[#001121] text-sm">OS202600059</span>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-md flex items-center justify-center text-emerald-600 font-medium text-sm">
                                            LA
                                        </div>
                                        <p class="font-semibold text-gray-800">Lucas Almeida</p>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-700">PlayStation 5</span>
                                        <span class="text-xs text-gray-500">Sony</span>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        Finalizado
                                    </span>
                                </td>

                                <td class="px-6 py-5 text-sm text-gray-500 tabular-nums">
                                    08/05/2026
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <button class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-sm font-medium whitespace-nowrap text-[#001121] hover:bg-[#f0f4f8] hover:text-[#0ea5e9] cursor-pointer transition-colors group">
                                        Ver detalhes
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 -translate-x-0.5 opacity-0 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M5 12h14M12 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
                    <p class="text-xs text-gray-400">
                        Exibindo <span class="font-medium text-gray-600">1–10</span> de <span class="font-medium text-gray-600">38</span> registros
                    </p>

                    <div class="flex items-center gap-1">

                        {{-- Anterior --}}
                        <button class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium text-gray-400 hover:bg-gray-100 hover:text-gray-600 disabled:opacity-40 disabled:pointer-events-none cursor-pointer transition-colors" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M15 18l-6-6 6-6"/>
                            </svg>
                            Anterior
                        </button>

                        {{-- Páginas --}}
                        <button class="w-8 h-8 rounded-md text-xs font-semibold bg-[#001121] text-white cursor-pointer transition-colors">1</button>
                        <button class="w-8 h-8 rounded-md text-xs font-medium text-gray-500 hover:bg-gray-100 cursor-pointer transition-colors">2</button>
                        <button class="w-8 h-8 rounded-md text-xs font-medium text-gray-500 hover:bg-gray-100 cursor-pointer transition-colors">3</button>
                        <span class="w-8 h-8 flex items-center justify-center text-xs text-gray-400">...</span>
                        <button class="w-8 h-8 rounded-md text-xs font-medium text-gray-500 hover:bg-gray-100 cursor-pointer transition-colors">8</button>

                        {{-- Próxima --}}
                        <button class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 cursor-pointer transition-colors">
                            Próxima
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>