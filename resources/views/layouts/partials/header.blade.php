{{-- ═══ HEADER ═══ --}}
<header class="sticky top-0 z-30 flex h-14 items-center gap-3 border-b border-slate-200 bg-white/95 px-4 backdrop-blur-sm sm:px-6">

    {{-- Mobile menu button --}}
    <button
        @click="mobileOpen = true"
        type="button"
        class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 transition hover:bg-slate-100 lg:hidden"
        aria-label="Abrir menu"
    >
        <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/>
        </svg>
    </button>

    {{-- Breadcrumbs --}}
    <div class="flex flex-1 items-center gap-1.5 text-[13px] text-slate-500 min-w-0">
        @yield('breadcrumbs')
    </div>

    {{-- Right side actions --}}
    <div class="flex items-center gap-1.5">

        {{-- Search --}}
        <button type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900" aria-label="Buscar">
            <svg class="h-[16px] w-[16px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
        </button>

        {{-- Notifications --}}
        <button type="button" class="relative flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900" aria-label="Notificações">
            <svg class="h-[17px] w-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full border-2 border-white bg-red-500 text-[9px] font-bold leading-none text-white">3</span>
        </button>

        {{-- User avatar --}}
        <div class="relative" x-data="{ open: false }">
            <button
                @click="open = !open"
                type="button"
                class="flex h-9 w-9 items-center justify-center rounded-lg overflow-hidden border border-slate-200 bg-gradient-to-br from-blue-400 via-blue-600 to-indigo-700 text-[11px] font-bold text-white transition hover:opacity-90"
                aria-label="Menu do usuário"
            >
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
            </button>

            <div
                x-show="open"
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-[0.97] translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-[0.97]"
                class="absolute right-0 top-full mt-2 w-52 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg shadow-slate-200/60"
                style="display:none"
            >
                <div class="border-b border-slate-100 px-4 py-3">
                    <p class="text-[13px] font-semibold text-slate-900">{{ auth()->user()->name ?? 'Usuário' }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
                <div class="p-1">
                    <a href="/perfil" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                        <svg class="h-[14px] w-[14px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Meu Perfil
                    </a>
                    <a href="/configuracoes" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                        <svg class="h-[14px] w-[14px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/></svg>
                        Configurações
                    </a>
                </div>
                <div class="border-t border-slate-100 p-1">
                    <form action="{{ route('auth.sair') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-medium text-red-500 transition hover:bg-red-50">
                            <svg class="h-[14px] w-[14px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
