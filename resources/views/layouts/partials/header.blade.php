@php
    $notificacoesRecentes = auth()->user()->unreadNotifications()->latest()->take(5)->get();
    $totalNaoLidas        = auth()->user()->unreadNotifications()->count();
@endphp

<header class="sticky top-0 z-30 flex h-[64px] items-center gap-3 border-b border-slate-200/70 bg-white/[0.97] px-4 shadow-[0_1px_0_rgba(0,0,0,0.05)] backdrop-blur-md sm:px-6">

    {{-- Mobile menu button --}}
    <button @click="mobileOpen = true" type="button"
            class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 lg:hidden"
            aria-label="Abrir menu">
        <svg class="h-[17px] w-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/>
        </svg>
    </button>

    {{-- Global search --}}
    <div class="hidden md:block">
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
            </div>
            <input type="search"
                   placeholder="Buscar OS, cliente…"
                   class="h-9 w-[240px] rounded-xl border border-slate-200 bg-slate-50/80 pl-9 pr-4 text-[13px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-500/10">
        </div>
    </div>

    {{-- Right: actions --}}
    <div class="ml-auto flex items-center gap-1">

        {{-- Notification bell --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button"
                    class="relative flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900"
                    aria-label="Notificações">
                <svg class="h-[17px] w-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                @if($totalNaoLidas > 0)
                    <span class="absolute right-1 top-1 flex h-[14px] min-w-[14px] items-center justify-center rounded-full bg-red-500 px-1 text-[8.5px] font-bold leading-none text-white">
                        {{ $totalNaoLidas > 9 ? '9+' : $totalNaoLidas }}
                    </span>
                @endif
            </button>

            <div x-show="open" @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-[0.97] translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-[0.97]"
                 class="absolute right-0 top-full mt-2 w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-black/[0.07]"
                 style="display:none">

                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3.5">
                    <p class="text-[13px] font-bold text-slate-900">Notificações</p>
                    @if($totalNaoLidas > 0)
                    <form action="{{ route('app.notificacoes.read-all') }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="text-[11.5px] font-semibold text-blue-600 transition hover:text-blue-700">
                            Marcar todas como lidas
                        </button>
                    </form>
                    @else
                    <span class="text-[11.5px] text-slate-400">Tudo lido</span>
                    @endif
                </div>

                @if($notificacoesRecentes->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 px-4">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                        <svg class="h-5 w-5 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                    </div>
                    <p class="text-[12.5px] font-medium text-slate-400">Nenhuma notificação nova</p>
                </div>
                @else
                <ul class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                    @foreach($notificacoesRecentes as $notificacao)
                    @php $data = $notificacao->data; $tipo = $data['tipo'] ?? 'outro'; @endphp
                    <li>
                        <a href="{{ route('app.notificacoes.open', $notificacao->id) }}"
                           class="flex items-start gap-3 px-4 py-3 transition hover:bg-slate-50/80">
                            <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-xl
                                {{ $tipo === 'os_criada'       ? 'bg-blue-100 text-blue-600' : '' }}
                                {{ $tipo === 'os_status'       ? 'bg-amber-100 text-amber-600' : '' }}
                                {{ $tipo === 'mensagem_portal' ? 'bg-emerald-100 text-emerald-600' : '' }}
                                {{ !in_array($tipo, ['os_criada','os_status','mensagem_portal']) ? 'bg-slate-100 text-slate-500' : '' }}">
                                @if($tipo === 'os_criada')
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                                </svg>
                                @elseif($tipo === 'os_status')
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/>
                                    <path d="M21 3v5h-5"/>
                                </svg>
                                @elseif($tipo === 'mensagem_portal')
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                                @else
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                </svg>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-[12.5px] font-semibold leading-snug text-slate-800">
                                    {{ $data['titulo'] ?? 'Notificação' }}
                                </p>
                                <p class="mt-0.5 line-clamp-2 text-[11.5px] leading-snug text-slate-500">{{ $data['mensagem'] ?? '' }}</p>
                                <p class="mt-1 text-[11px] text-slate-400">{{ $notificacao->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-blue-500"></span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif

                <div class="border-t border-slate-100 p-2">
                    <a href="{{ route('app.notificacoes.index') }}" @click="open = false"
                       class="flex w-full items-center justify-center gap-1.5 rounded-xl px-3 py-2 text-[12.5px] font-semibold text-slate-500 transition hover:bg-slate-50 hover:text-slate-900">
                        Ver todas as notificações
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="mx-1 h-5 w-px bg-slate-200"></div>

        {{-- User avatar --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button"
                    class="flex items-center gap-2 rounded-xl px-2 py-1.5 transition hover:bg-slate-100"
                    aria-label="Menu do usuário">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-[11px] font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="hidden flex-col items-start sm:flex">
                    <span class="text-[12.5px] font-semibold leading-tight text-slate-800">{{ auth()->user()->name ?? 'Usuário' }}</span>
                    <span class="text-[11px] leading-tight text-slate-400">{{ auth()->user()->email ?? '' }}</span>
                </div>
                <svg class="hidden h-3.5 w-3.5 shrink-0 text-slate-400 sm:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                </svg>
            </button>

            <div x-show="open" @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-[0.97] translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-[0.97]"
                 class="absolute right-0 top-full mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-black/[0.07]"
                 style="display: none">

                <div class="flex items-center gap-3 border-b border-slate-100 px-4 py-3.5">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-[12px] font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-[13px] font-bold text-slate-900">{{ auth()->user()->name ?? 'Usuário' }}</p>
                        <p class="truncate text-[11px] text-slate-400">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                </div>

                <div class="p-1.5 space-y-0.5">
                    <a href="{{ route('app.perfil.index') }}"
                       class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-[12.5px] font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                        <svg class="h-[14px] w-[14px] shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        Meu Perfil
                    </a>
                    <a href="{{ route('app.configuracoes.index') }}"
                       class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-[12.5px] font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                        <svg class="h-[14px] w-[14px] shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>
                        </svg>
                        Configurações
                    </a>
                </div>

                <div class="border-t border-slate-100 p-1.5">
                    <form action="{{ route('auth.sair') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-[12.5px] font-medium text-red-500 transition hover:bg-red-50 hover:text-red-600">
                            <svg class="h-[14px] w-[14px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Sair da conta
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>
