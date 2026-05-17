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
<body
    x-data="{
        collapsed: false,
        mobileOpen: false,
        tooltip: { visible: false, text: '', x: 0, y: 0 },
        showTooltip(el, text) {
            if (!this.collapsed) return;
            const r = el.getBoundingClientRect();
            this.tooltip = { visible: true, text, x: r.right + 10, y: r.top + r.height / 2 };
        },
        hideTooltip() { this.tooltip.visible = false; }
    }"
    @keydown.escape.window="mobileOpen = false"
    class="min-h-screen bg-[#f4f5f7] text-slate-900 antialiased [font-family:'DM_Sans',sans-serif]"
>

{{-- ═══ TOOLTIP GLOBAL (sidebar recolhida) ═══ --}}
<div
    x-show="tooltip.visible && collapsed"
    x-transition:enter="transition ease-out duration-100"
    x-transition:enter-start="opacity-0 translate-x-1"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    :style="`position:fixed;top:${tooltip.y}px;left:${tooltip.x}px;transform:translateY(-50%);z-index:9999`"
    class="pointer-events-none flex items-center rounded-lg border border-white/10 bg-slate-800 px-3 py-1.5 text-[12px] font-medium text-white shadow-2xl"
    style="display:none"
>
    <div class="absolute -left-[5px] top-1/2 h-2.5 w-2.5 -translate-y-1/2 rotate-45 border-b border-l border-white/10 bg-slate-800"></div>
    <span x-text="tooltip.text"></span>
</div>

{{-- ═══ MOBILE OVERLAY ═══ --}}
<div
    x-show="mobileOpen"
    x-transition:enter="transition-opacity ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="mobileOpen = false"
    class="fixed inset-0 z-40 bg-black/70 backdrop-blur-sm lg:hidden"
    style="display:none"
></div>

{{-- ═══════════════════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════════════════ --}}
    <aside :class="[ collapsed ? 'w-[68px]' : 'w-[240px]', mobileOpen ? 'translate-x-0 shadow-[24px_0_80px_rgba(0,0,0,.6)]' : '-translate-x-full lg:translate-x-0']" class="fixed inset-y-0 left-0 z-50 flex flex-col border-r border-white/[0.05] bg-[#0d0f16] transition-[width,transform] duration-300 ease-in-out" >
        {{-- ─── Marca ─── --}}
        <div class="relative flex h-[64px] shrink-0 items-center justify-center border-b border-white/[0.06] bg-gradient-to-b from-white/[0.02] to-transparent px-5 backdrop-blur-xl">

            <a href="/" class="group flex items-center justify-center">
                <img 
                    src="{{ asset('images/futuredata.png') }}" 
                    class="relative h-8 w-auto object-contain brightness-0 invert transition duration-300 group-hover:scale-105" 
                    alt="Future Data" 
                />
            </a>

            {{-- Collapse Button --}}
            <button  @click="collapsed = !collapsed" class="absolute -right-3 top-1/2 hidden h-7 w-7 -translate-y-1/2 items-center justify-center rounded-full border border-white/[0.08] bg-[#0f1117] text-slate-500 shadow-[0_4px_20px_rgba(0,0,0,0.35)] backdrop-blur-xl transition-all duration-300 hover:scale-110 hover:border-blue-500/40 hover:bg-slate-900 hover:text-slate-200 lg:flex">
                <svg :class="collapsed ? 'rotate-180' : ''" class="h-3.5 w-3.5 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" qustroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

        </div>

    {{-- ─── Navegação ─── --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-2.5 [scrollbar-width:thin] [scrollbar-color:rgba(255,255,255,.05)_transparent]">

        {{-- ·· Grupo: Principal ·· --}}
        <div class="mb-1 px-3.5">
            <span x-show="!collapsed" class="block px-1 py-1 text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-600">Principal</span>
            <div x-show="collapsed" class="my-1 border-t border-white/[0.06]"></div>
        </div>

        {{-- Dashboard --}}
        @php $isDash = request()->is('/') || request()->is('dashboard'); @endphp
        <div class="px-2 mb-0.5">
            <a href="/" @mouseenter="showTooltip($el, 'Dashboard')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-xl px-2.5 py-[7px] text-[13px] font-medium transition-all duration-150
                   {{ $isDash ? 'bg-blue-600/[0.13] text-blue-400' : 'text-slate-500 hover:bg-white/[0.05] hover:text-slate-200' }}">
                @if($isDash)<div class="absolute left-0 top-1/2 h-4 w-0.5 -translate-y-1/2 rounded-r-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 transition-colors {{ $isDash ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>

                <span x-show="!collapsed" class="whitespace-nowrap">Dashboard</span>
            </a>
        </div>

        {{-- Ordens de Serviço (dropdown) --}}
        @php $isOS = request()->is('ordens*'); @endphp
        <div x-data="{ open: {{ $isOS ? 'true' : 'false' }} }" class="px-2 mb-0.5">
            <button @click="!collapsed && (open = !open)" @mouseenter="showTooltip($el, 'Ordens de Serviço')" @mouseleave="hideTooltip()"
                    class="group relative flex w-full items-center gap-3 rounded-xl px-2.5 py-[7px] text-[13px] font-medium transition-all duration-150
                        {{ $isOS ? 'bg-blue-600/[0.13] text-blue-400' : 'text-slate-500 hover:bg-white/[0.05] hover:text-slate-200' }}">
                @if($isOS)<div class="absolute left-0 top-1/2 h-4 w-0.5 -translate-y-1/2 rounded-r-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 transition-colors {{ $isOS ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2M9 12h6M9 16h4"/>
                </svg>

                <span x-show="!collapsed" class="flex-1 text-left whitespace-nowrap">Ordens de Serviço</span>

                <svg x-show="!collapsed" :class="open ? 'rotate-180' : ''" class="h-3.5 w-3.5 shrink-0 text-slate-600 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                </svg>
                @if($isOS)<span x-show="collapsed" class="absolute right-2 top-2 h-1.5 w-1.5 rounded-full bg-blue-500"></span>@endif
            </button>
            <div x-show="open && !collapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mt-0.5 ml-[15px] space-y-0.5 border-l border-white/[0.07] pb-1 pl-3" style="display:none">
                @foreach([
                    ['href' => '/ordens',                  'label' => 'Todas as OS'],
                    ['href' => '/ordens/nova',             'label' => 'Nova OS'],
                    ['href' => '/ordens?status=aberto',    'label' => 'Em Aberto'],
                    ['href' => '/ordens?status=concluido', 'label' => 'Concluídas'],
                    ['href' => '/ordens?status=cancelado', 'label' => 'Canceladas'],
                ] as $sub)
                @php $subOn = request()->fullUrl() === url($sub['href']); @endphp
                <a href="{{ $sub['href'] }}" class="flex items-center gap-2.5 rounded-lg px-2.5 py-[5px] text-[12.5px] font-medium transition-all duration-100 {{ $subOn ? 'text-blue-400' : 'text-slate-500 hover:bg-white/[0.04] hover:text-slate-200' }}">
                    <div class="h-[5px] w-[5px] rounded-full {{ $subOn ? 'bg-blue-400' : 'bg-slate-700' }}"></div>
                    {{ $sub['label'] }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- Clientes --}}
        @php $isCli = request()->is('clientes*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="/clientes" @mouseenter="showTooltip($el, 'Clientes')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-xl px-2.5 py-[7px] text-[13px] font-medium transition-all duration-150 {{ $isCli ? 'bg-blue-600/[0.13] text-blue-400' : 'text-slate-500 hover:bg-white/[0.05] hover:text-slate-200' }}">
                @if($isCli)<div class="absolute left-0 top-1/2 h-4 w-0.5 -translate-y-1/2 rounded-r-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 transition-colors {{ $isCli ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Clientes</span>
            </a>
        </div>

        {{-- Equipamentos --}}
        @php $isEq = request()->is('equipamentos*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="/equipamentos" @mouseenter="showTooltip($el, 'Equipamentos')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-xl px-2.5 py-[7px] text-[13px] font-medium transition-all duration-150 {{ $isEq ? 'bg-blue-600/[0.13] text-blue-400' : 'text-slate-500 hover:bg-white/[0.05] hover:text-slate-200' }}">
                @if($isEq)<div class="absolute left-0 top-1/2 h-4 w-0.5 -translate-y-1/2 rounded-r-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 transition-colors {{ $isEq ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                    <line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Equipamentos</span>
            </a>
        </div>

        {{-- Estoque --}}
        @php $isEst = request()->is('estoque*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="/estoque" @mouseenter="showTooltip($el, 'Estoque')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-xl px-2.5 py-[7px] text-[13px] font-medium transition-all duration-150 {{ $isEst ? 'bg-blue-600/[0.13] text-blue-400' : 'text-slate-500 hover:bg-white/[0.05] hover:text-slate-200' }}">
                @if($isEst)<div class="absolute left-0 top-1/2 h-4 w-0.5 -translate-y-1/2 rounded-r-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 transition-colors {{ $isEst ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3.3 7 8.7 5 8.7-5M12 22V12"/>
                </svg>

                <span x-show="!collapsed" class="whitespace-nowrap">Estoque</span>
            </a>
        </div>

        {{-- ·· Grupo: Financeiro ·· --}}
        <div class="mb-1 mt-4 px-3.5">
            <span x-show="!collapsed" class="block px-1 py-1 text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-600">Financeiro</span>
            <div x-show="collapsed" class="my-2 border-t border-white/[0.06]"></div>
        </div>

        {{-- Financeiro (dropdown) --}}
        @php $isFin = request()->is('financeiro*'); @endphp
        <div x-data="{ open: {{ $isFin ? 'true' : 'false' }} }" class="px-2 mb-0.5">
            <button @click="!collapsed && (open = !open)"
                    @mouseenter="showTooltip($el, 'Financeiro')" @mouseleave="hideTooltip()"
                    class="group relative flex w-full items-center gap-3 rounded-xl px-2.5 py-[7px] text-[13px] font-medium transition-all duration-150 {{ $isFin ? 'bg-blue-600/[0.13] text-blue-400' : 'text-slate-500 hover:bg-white/[0.05] hover:text-slate-200' }}">
                @if($isFin)<div class="absolute left-0 top-1/2 h-4 w-0.5 -translate-y-1/2 rounded-r-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 transition-colors {{ $isFin ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>

                <span x-show="!collapsed" class="flex-1 text-left whitespace-nowrap">Financeiro</span>
                
                <svg x-show="!collapsed" :class="open ? 'rotate-180' : ''" class="h-3.5 w-3.5 shrink-0 text-slate-600 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                </svg>
                @if($isFin)<span x-show="collapsed" class="absolute right-2 top-2 h-1.5 w-1.5 rounded-full bg-blue-500"></span>@endif
            </button>
            <div x-show="open && !collapsed"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mt-0.5 ml-[15px] space-y-0.5 border-l border-white/[0.07] pb-1 pl-3" style="display:none">
                @foreach([
                    ['href' => '/financeiro',            'label' => 'Resumo'],
                    ['href' => '/financeiro/receitas',   'label' => 'Receitas'],
                    ['href' => '/financeiro/despesas',   'label' => 'Despesas'],
                    ['href' => '/financeiro/relatorios', 'label' => 'Relatórios'],
                ] as $sub)
                @php $subOn = request()->fullUrl() === url($sub['href']); @endphp
                <a href="{{ $sub['href'] }}" class="flex items-center gap-2.5 rounded-lg px-2.5 py-[5px] text-[12.5px] font-medium transition-all duration-100 {{ $subOn ? 'text-blue-400' : 'text-slate-500 hover:bg-white/[0.04] hover:text-slate-200' }}">
                    <div class="h-[5px] w-[5px] rounded-full {{ $subOn ? 'bg-blue-400' : 'bg-slate-700' }}"></div>
                    {{ $sub['label'] }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- ·· Grupo: Sistema ·· --}}
        <div class="mb-1 mt-4 px-3.5">
            <span x-show="!collapsed" class="block px-1 py-1 text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-600">Sistema</span>
            <div x-show="collapsed" class="my-2 border-t border-white/[0.06]"></div>
        </div>

        {{-- Relatórios --}}
        @php $isRel = request()->is('relatorios*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="/relatorios" @mouseenter="showTooltip($el, 'Relatórios')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-xl px-2.5 py-[7px] text-[13px] font-medium transition-all duration-150 {{ $isRel ? 'bg-blue-600/[0.13] text-blue-400' : 'text-slate-500 hover:bg-white/[0.05] hover:text-slate-200' }}">
                @if($isRel)<div class="absolute left-0 top-1/2 h-4 w-0.5 -translate-y-1/2 rounded-r-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 transition-colors {{ $isRel ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18"/><path stroke-linecap="round" stroke-linejoin="round" d="m19 9-5 5-4-4-3 3"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Relatórios</span>
            </a>
        </div>

        {{-- Configurações (dropdown) --}}
        @php $isConf = request()->is('configuracoes*'); @endphp
        <div x-data="{ open: {{ $isConf ? 'true' : 'false' }} }" class="px-2 mb-0.5">
            <button @click="!collapsed && (open = !open)"
                    @mouseenter="showTooltip($el, 'Configurações')" @mouseleave="hideTooltip()"
                    class="group relative flex w-full items-center gap-3 rounded-xl px-2.5 py-[7px] text-[13px] font-medium transition-all duration-150 {{ $isConf ? 'bg-blue-600/[0.13] text-blue-400' : 'text-slate-500 hover:bg-white/[0.05] hover:text-slate-200' }}">
                @if($isConf)<div class="absolute left-0 top-1/2 h-4 w-0.5 -translate-y-1/2 rounded-r-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 transition-colors {{ $isConf ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                <span x-show="!collapsed" class="flex-1 text-left whitespace-nowrap">Configurações</span>
                <svg x-show="!collapsed" :class="open ? 'rotate-180' : ''" class="h-3.5 w-3.5 shrink-0 text-slate-600 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                </svg>
            </button>
            <div x-show="open && !collapsed"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mt-0.5 ml-[15px] space-y-0.5 border-l border-white/[0.07] pb-1 pl-3" style="display:none">
                @foreach([
                    ['href' => '/configuracoes',             'label' => 'Empresa'],
                    ['href' => '/configuracoes/usuarios',    'label' => 'Usuários'],
                    ['href' => '/configuracoes/integracoes', 'label' => 'Integrações'],
                ] as $sub)
                @php $subOn = request()->fullUrl() === url($sub['href']); @endphp
                <a href="{{ $sub['href'] }}" class="flex items-center gap-2.5 rounded-lg px-2.5 py-[5px] text-[12.5px] font-medium transition-all duration-100 {{ $subOn ? 'text-blue-400' : 'text-slate-500 hover:bg-white/[0.04] hover:text-slate-200' }}">
                    <div class="h-[5px] w-[5px] rounded-full {{ $subOn ? 'bg-blue-400' : 'bg-slate-700' }}"></div>
                    {{ $sub['label'] }}
                </a>
                @endforeach
            </div>
        </div>

    </nav>

    {{-- ─── Perfil do usuário ─── --}}
    <div class="shrink-0 border-t border-white/[0.05] p-2" x-data="{ userMenu: false }">
        <div class="relative">
            <button
                @click="!collapsed && (userMenu = !userMenu)"
                @mouseenter="showTooltip($el, 'Carlos Junior')" @mouseleave="hideTooltip()"
                class="group flex w-full items-center gap-2.5 rounded-xl px-2.5 py-2 transition-all duration-150 hover:bg-white/[0.06]"
            >
                <div class="relative shrink-0">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 via-blue-600 to-indigo-700 text-[11px] font-bold tracking-wide text-white ring-2 ring-white/10">CJ</div>
                    <span class="absolute -bottom-0.5 -right-0.5 h-[9px] w-[9px] rounded-full border-[2px] border-[#0d0f16] bg-emerald-400"></span>
                </div>
                <div x-show="!collapsed" class="min-w-0 flex-1 text-left">
                    <p class="truncate text-[12.5px] font-semibold leading-[1.25] text-slate-200">Carlos Junior</p>
                    <p class="text-[11px] leading-[1.3] text-slate-500">Administrador</p>
                </div>
                <svg x-show="!collapsed" :class="userMenu ? 'rotate-180' : ''"
                     class="ml-auto h-3.5 w-3.5 shrink-0 text-slate-600 transition-transform duration-200 group-hover:text-slate-400"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                </svg>
            </button>

            {{-- Dropdown do usuário --}}
            <div
                x-show="userMenu && !collapsed"
                @click.outside="userMenu = false"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-2 scale-[0.97]"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-2 scale-[0.97]"
                class="absolute bottom-full left-2 right-2 mb-2 overflow-hidden rounded-2xl border border-white/[0.08] bg-[#13151f] shadow-2xl shadow-black/60"
                style="display:none"
            >
                <div class="flex items-center gap-3 border-b border-white/[0.06] px-4 py-3.5">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 via-blue-600 to-indigo-700 text-[12px] font-bold text-white">CJ</div>
                    <div class="min-w-0">
                        <p class="truncate text-[13px] font-semibold text-white">Carlos Junior</p>
                        <p class="truncate text-[11px] text-slate-500">carlos@futuredata.com.br</p>
                    </div>
                </div>
                <div class="p-1.5 space-y-0.5">
                    <a href="/perfil" class="flex items-center gap-3 rounded-xl px-3 py-[7px] text-[12.5px] font-medium text-slate-400 transition-all hover:bg-white/[0.06] hover:text-slate-100">
                        <svg class="h-[15px] w-[15px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Meu Perfil
                    </a>
                    <a href="/configuracoes" class="flex items-center gap-3 rounded-xl px-3 py-[7px] text-[12.5px] font-medium text-slate-400 transition-all hover:bg-white/[0.06] hover:text-slate-100">
                        <svg class="h-[15px] w-[15px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                        Configurações
                    </a>
                    <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-[7px] text-[12.5px] font-medium text-slate-400 transition-all hover:bg-white/[0.06] hover:text-slate-100">
                        <svg class="h-[15px] w-[15px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01"/></svg>
                        Suporte
                    </a>
                </div>
                <div class="border-t border-white/[0.06] p-1.5">
                    <a href="/logout" class="flex items-center gap-3 rounded-xl px-3 py-[7px] text-[12.5px] font-medium text-red-400/80 transition-all hover:bg-red-500/10 hover:text-red-400">
                        <svg class="h-[15px] w-[15px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Sair da conta
                    </a>
                </div>
            </div>
        </div>
    </div>
</aside>

{{-- ═══ CONTEÚDO PRINCIPAL ═══ --}}
<div
    :class="collapsed ? 'lg:ml-[68px]' : 'lg:ml-[240px]'"
    class="flex min-h-screen flex-1 flex-col transition-[margin] duration-300 ease-in-out"
>
    <header class="flex h-14 items-center gap-3 border-b border-slate-200 bg-white px-6">
        <button @click="mobileOpen = true" type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 transition hover:bg-slate-100 lg:hidden">
            <i data-lucide="menu" class="h-[18px] w-[18px]"></i>
        </button>
        <div class="flex flex-1 items-center gap-1.5 text-[13px] text-slate-500">
            <a href="#" class="text-slate-500 no-underline hover:text-slate-900">Ordens de Serviço</a>
            <span class="text-slate-400">›</span>
            <span class="font-semibold text-slate-900">OS #12458</span>
        </div>
        <!-- ... resto do header ... -->
    </header>

    <main class="flex-1 px-6 py-5 pb-8">
        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3.5">
                <h1 class="text-[26px] font-bold tracking-[-0.5px] text-slate-900">OS #12458</h1>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 12 2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9S3 16.97 3 12 7.03 3 12 3s9 4.03 9 9"/></svg>
                    Em teste
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <!-- Botões de ação (mantidos) -->
                <button class="inline-flex cursor-pointer items-center gap-[7px] rounded-lg border border-slate-200 bg-white px-4 py-2 text-[13.5px] font-semibold text-slate-900 transition hover:bg-slate-50">
                    Imprimir
                </button>
                <button class="inline-flex cursor-pointer items-center gap-[7px] rounded-lg bg-blue-600 px-4 py-2 text-[13.5px] font-semibold text-white transition hover:bg-blue-700">
                    Editar OS
                </button>
            </div>
        </div>

        <!-- Stepper -->
        <div class="mb-6 overflow-x-auto rounded-xl border border-slate-200 bg-white px-6 py-5">
            <!-- ... seu stepper (mantido igual) ... -->
        </div>

        <!-- === GRID PRINCIPAL AJUSTADO === -->
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_380px] gap-6">
            
            <!-- COLUNA ESQUERDA -->
            <div class="space-y-6">
                
                <!-- Cliente + Equipamento em 2 colunas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    
                    <!-- Card Cliente -->
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <!-- Seu card de cliente (copie aqui o conteúdo completo) -->
                        <div class="flex items-center justify-between border-b border-slate-100 px-[18px] py-3.5">
                            <span class="text-sm font-bold text-slate-900">Informações do cliente</span>
                            <a href="#" class="inline-flex items-center gap-1 text-[12px] font-medium text-blue-600 hover:text-blue-800">
                                Editar
                            </a>
                        </div>
                        <div class="px-[18px] py-4">
                            <!-- ... resto do card cliente ... -->
                        </div>
                    </div>

                    <!-- Card Equipamento -->
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <!-- Seu card de equipamento -->
                        <div class="flex items-center justify-between border-b border-slate-100 px-[18px] py-3.5">
                            <span class="text-sm font-bold text-slate-900">Informações do equipamento</span>
                            <a href="#" class="inline-flex items-center gap-1 text-[12px] font-medium text-blue-600 hover:text-blue-800">
                                Editar
                            </a>
                        </div>
                        <div class="px-[18px] py-4">
                            <!-- ... resto do card equipamento ... -->
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <!-- Seu código das tabs (Histórico, Orçamento, Arquivos, Mensagens) -->
                    <!-- ... cole aqui o conteúdo completo das tabs ... -->
                </div>
            </div>

            <!-- COLUNA DIREITA -->
            <aside class="space-y-5">
                <!-- Previsão de entrega -->
                <div class="rounded-xl border border-slate-200 bg-white px-[18px] py-4">
                    <!-- ... seu card de previsão ... -->
                </div>

                <!-- Resumo Financeiro -->
                <div class="rounded-xl border border-slate-200 bg-white px-[18px] py-4">
                    <!-- ... seu resumo financeiro ... -->
                </div>

                <!-- Contato WhatsApp -->
                <div class="rounded-xl border border-emerald-900 bg-gradient-to-br from-emerald-800 to-emerald-700 px-[18px] py-4">
                    <!-- ... seu card WhatsApp ... -->
                </div>
            </aside>
        </div>
    </main>
</div>


<script src="{{ asset('js/main.js') }}" type="text/javascript"></script>
</body>
</html>