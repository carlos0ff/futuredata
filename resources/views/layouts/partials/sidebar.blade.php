@php
$notifCount = auth()->user()->unreadNotifications()->count();
@endphp

<aside :class="[ collapsed ? 'w-[68px]' : 'w-[252px]', mobileOpen ? 'translate-x-0 shadow-[24px_0_80px_rgba(0,0,0,.7)]' : '-translate-x-full lg:translate-x-0' ]"
    class="fixed inset-y-0 left-0 z-50 flex flex-col bg-[#001121] transition-[width,transform] duration-300 ease-in-out" style="border-right: 1px solid rgba(255,255,255,0.05);">

    <div class="relative flex h-[64px] shrink-0 items-center justify-center px-4" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
        
        <a href="{{ route('app.dashboard') }}" class="group">
            <img 
                src="{{ asset('images/futuredata.png') }}"
                class="h-7 w-auto object-contain brightness-0 invert opacity-90 transition duration-300"
                alt="Future Data"
            />
        </a>

        <button
            @click="collapsed = !collapsed"
            class="absolute -right-3.5 top-1/2 hidden h-7 w-7 -translate-y-1/2 items-center justify-center rounded-full border border-white/[0.1] bg-[#0b0d14] text-slate-500 shadow-lg shadow-black/50 transition-all duration-200 hover:border-blue-500/50 hover:text-blue-400 lg:flex">
            <svg
                :class="collapsed ? 'rotate-180' : ''"
                class="h-3.5 w-3.5 transition-transform duration-300"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
    </div>

    <nav
    class="relative flex-1 overflow-y-auto overflow-x-hidden py-3"
    :class="collapsed ? '[&>div>a]:justify-center [&>div>a]:px-0' : ''"
>
        <div class="mb-2 px-4">
            <span x-show="!collapsed" class="block text-[10px] font-semibold uppercase tracking-[0.13em] text-slate-600">Principal</span>
            <div x-show="collapsed" class="my-1.5 border-t border-white/[0.05]"></div>
        </div>

        @php $isDash = request()->routeIs('app.dashboard'); @endphp
        <div class="px-2 mb-0.5">
            <a href="{{ route('app.dashboard') }}" @mouseenter="showTooltip($el, 'Dashboard')" @mouseleave="hideTooltip()" class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150 {{ $isDash ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isDash)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isDash ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Dashboard</span>
                <svg x-show="!collapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto h-3 w-3 shrink-0 opacity-0 transition-opacity duration-150 group-hover:opacity-60 {{ $isDash ? 'opacity-60' : '' }}">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </a>
        </div>

        @php $isOS = request()->is('app/ordens-servico*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="{{ route('app.os.index') }}" @mouseenter="showTooltip($el, 'Ordens de Serviço')" @mouseleave="hideTooltip()" class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150 {{ $isOS ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isOS)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isOS ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2M9 12h6M9 16h4"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Ordens de Serviço</span>
                <svg x-show="!collapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto h-3 w-3 shrink-0 opacity-0 transition-opacity duration-150 group-hover:opacity-60 {{ $isOS ? 'opacity-60' : '' }}"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        @php $isCli = request()->is('app/clientes*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="{{ route('app.clientes.index') }}"
               @mouseenter="showTooltip($el, 'Clientes')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150
                      {{ $isCli ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isCli)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isCli ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Clientes</span>
                <svg x-show="!collapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto h-3 w-3 shrink-0 opacity-0 transition-opacity duration-150 group-hover:opacity-60 {{ $isCli ? 'opacity-60' : '' }}"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
      
        @php $isSrv = request()->is('app/servicos*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="{{ route('app.servicos.index') }}"
               @mouseenter="showTooltip($el, 'Serviços')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150
                      {{ $isSrv ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isSrv)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isSrv ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Serviços</span>
                <svg x-show="!collapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto h-3 w-3 shrink-0 opacity-0 transition-opacity duration-150 group-hover:opacity-60 {{ $isSrv ? 'opacity-60' : '' }}"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        @php $isMsg = request()->is('app/mensagens*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="{{ route('app.mensagens.index') }}" @mouseenter="showTooltip($el, 'Mensagens')" @mouseleave="hideTooltip()" class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150 {{ $isMsg ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isMsg)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isMsg ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Mensagens</span>
                <svg x-show="!collapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto h-3 w-3 shrink-0 opacity-0 transition-opacity duration-150 group-hover:opacity-60 {{ $isMsg ? 'opacity-60' : '' }}">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </div>

        <div class="mb-2 mt-5 px-4">
            <span x-show="!collapsed" class="block text-[10px] font-semibold uppercase tracking-[0.13em] text-slate-600">Operação</span>
            <div x-show="collapsed" class="my-1.5 border-t border-white/[0.05]"></div>
        </div>

        @php $isEst = request()->is('app/estoque*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="/app/estoque"
               @mouseenter="showTooltip($el, 'Estoque')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150
                      {{ $isEst ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isEst)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isEst ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3.3 7 8.7 5 8.7-5M12 22V12"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Estoque</span>
                <svg x-show="!collapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto h-3 w-3 shrink-0 opacity-0 transition-opacity duration-150 group-hover:opacity-60 {{ $isEst ? 'opacity-60' : '' }}"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        @php $isNotif = request()->is('app/notificacoes*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="{{ route('app.notificacoes.index') }}"
               @mouseenter="showTooltip($el, 'Notificações')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150
                      {{ $isNotif ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isNotif)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isNotif ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span x-show="!collapsed" class="flex-1 whitespace-nowrap">Notificações</span>
                @if($notifCount > 0)
                <span x-show="!collapsed" class="flex h-5 min-w-[20px] items-center justify-center rounded-full bg-red-500/80 px-1.5 text-[10px] font-bold text-white">
                    {{ $notifCount > 9 ? '9+' : $notifCount }}
                </span>
                <span x-show="collapsed" class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-red-500"></span>
                @endif
            </a>
        </div>

        {{-- Portal do Cliente --}}
        @php $isPortal = request()->is('app/portal*') || request()->is('portal*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="/portal"
               target="_blank"
               @mouseenter="showTooltip($el, 'Portal do Cliente')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150 text-slate-400 hover:bg-white/[0.06] hover:text-slate-100">
                <svg class="h-[17px] w-[17px] shrink-0 text-slate-600 group-hover:text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                </svg>
                <span x-show="!collapsed" class="flex-1 whitespace-nowrap">Portal do Cliente</span>
                <svg x-show="!collapsed" class="h-3 w-3 shrink-0 text-slate-700 group-hover:text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/>
                </svg>
            </a>
        </div>

        @if(auth()->user()->isGerente())
        <div class="mb-2 mt-5 px-4">
            <span x-show="!collapsed" class="block text-[10px] font-semibold uppercase tracking-[0.13em] text-slate-600">Financeiro</span>
            <div x-show="collapsed" class="my-1.5 border-t border-white/[0.05]"></div>
        </div>

        @php $isFin = request()->is('app/financeiro*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="{{ route('app.financeiro.index') }}"
               @mouseenter="showTooltip($el, 'Financeiro')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150
                      {{ $isFin ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isFin)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isFin ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Financeiro</span>
                <svg x-show="!collapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto h-3 w-3 shrink-0 opacity-0 transition-opacity duration-150 group-hover:opacity-60 {{ $isFin ? 'opacity-60' : '' }}"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        @php $isRel = request()->is('app/relatorios*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="{{ route('app.relatorios.index') }}"
               @mouseenter="showTooltip($el, 'Relatórios')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150
                      {{ $isRel ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isRel)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isRel ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-5 5-4-4-3 3"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Relatórios</span>
                <svg x-show="!collapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto h-3 w-3 shrink-0 opacity-0 transition-opacity duration-150 group-hover:opacity-60 {{ $isRel ? 'opacity-60' : '' }}"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
        @endif

        {{-- ══ GRUPO: ADMINISTRAÇÃO ══ --}}
        @if(auth()->user()->isGerente())
        <div class="mb-2 mt-5 px-4">
            <span x-show="!collapsed" class="block text-[10px] font-semibold uppercase tracking-[0.13em] text-slate-600">Administração</span>
            <div x-show="collapsed" class="my-1.5 border-t border-white/[0.05]"></div>
        </div>

        @php $isUsers = request()->is('app/usuarios*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="{{ route('app.usuarios.index') }}"
               @mouseenter="showTooltip($el, 'Usuários')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150
                      {{ $isUsers ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isUsers)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isUsers ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Usuários</span>
                <svg x-show="!collapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto h-3 w-3 shrink-0 opacity-0 transition-opacity duration-150 group-hover:opacity-60 {{ $isUsers ? 'opacity-60' : '' }}"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        @php $isUsers = request()->is('app/usuarios*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="{{ route('app.usuarios.index') }}"
               @mouseenter="showTooltip($el, 'Usuários')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150
                      {{ $isUsers ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isUsers)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isUsers ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Serviços</span>
                <svg x-show="!collapsed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-auto h-3 w-3 shrink-0 opacity-0 transition-opacity duration-150 group-hover:opacity-60 {{ $isUsers ? 'opacity-60' : '' }}"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        @php $isWa = request()->is('app/whatsapp*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="{{ route('app.whatsapp.index') }}" @mouseenter="showTooltip($el, 'WhatsApp')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150
                      {{ $isWa ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isWa)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isWa ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">WhatsApp</span>
                <svg x-show="!collapsed" class="ml-auto h-3 w-3 shrink-0 opacity-0 transition-opacity duration-150 group-hover:opacity-60 {{ $isWa ? 'opacity-60' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        @php $isConf = request()->is('app/configuracoes*'); @endphp
        <div class="px-2 mb-0.5">
            <a href="{{ route('app.configuracoes.index') }}" @mouseenter="showTooltip($el, 'Configurações')" @mouseleave="hideTooltip()"
               class="group relative flex items-center gap-3 rounded-md px-2.5 py-[8px] text-[13px] font-medium transition-all duration-150
                      {{ $isConf ? 'bg-blue-500/[0.15] text-blue-300' : 'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' }}">
                @if($isConf)<div class="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.5)]"></div>@endif
                <svg class="h-[17px] w-[17px] shrink-0 {{ $isConf ? 'text-blue-400' : 'text-slate-600 group-hover:text-slate-300' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                <span x-show="!collapsed" class="whitespace-nowrap">Configurações</span>
                <svg x-show="!collapsed" class="ml-auto h-3 w-3 shrink-0 opacity-0 transition-opacity duration-150 group-hover:opacity-60 {{ $isConf ? 'opacity-60' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
        @endif
    </nav>
</aside>
