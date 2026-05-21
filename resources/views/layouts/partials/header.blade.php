{{-- ═══ HEADER ═══ --}}
@php
    $notificacoesRecentes = auth()->user()->unreadNotifications()->latest()->take(5)->get();
    $totalNaoLidas        = auth()->user()->unreadNotifications()->count();
@endphp

<header class="sticky top-0 z-30 flex h-14 items-center gap-3 border-b border-slate-200 bg-white/95 px-4 backdrop-blur-sm sm:px-6">

    <button @click="mobileOpen = true" type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 transition hover:bg-slate-100 lg:hidden" aria-label="Abrir menu" >
        <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/>
        </svg>
    </button>

    <div class="flex flex-1 items-center gap-1.5 text-[13px] text-slate-500 min-w-0">
        @yield('breadcrumbs')
    </div>


    <div class="flex items-center gap-1.5">


        <button type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900" aria-label="Buscar">
            <svg class="h-[16px] w-[16px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
        </button>

        {{-- Notifications --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                type="button"
                class="relative flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900"
                aria-label="Notificações"
            >
                <svg class="h-[17px] w-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                @if($totalNaoLidas > 0)
                    <span class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full border-2 border-white bg-red-500 text-[9px] font-bold leading-none text-white">
                        {{ $totalNaoLidas > 9 ? '9+' : $totalNaoLidas }}
                    </span>
                @endif
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
                class="absolute right-0 top-full mt-2 w-80 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg shadow-slate-200/60"
                style="display:none"
            >
                {{-- Dropdown header --}}
                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                    <p class="text-[13px] font-semibold text-slate-900">Notificações</p>
                    @if($totalNaoLidas > 0)
                        <form action="{{ route('app.notificacoes.read-all') }}" method="POST">
                            @csrf @method('PUT')
                            <button type="submit" class="text-[11px] font-medium text-blue-600 hover:text-blue-700 transition">
                                Marcar todas como lidas
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Notification list --}}
                @if($notificacoesRecentes->isEmpty())
                    <div class="flex flex-col items-center justify-center py-10 px-4">
                        <svg class="h-8 w-8 text-slate-300 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <p class="text-[13px] text-slate-400">Nenhuma notificação nova</p>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
                        @foreach($notificacoesRecentes as $notificacao)
                            @php
                                $data = $notificacao->data;
                                $tipo = $data['tipo'] ?? 'outro';
                            @endphp
                            <li>
                                <a
                                    href="{{ route('app.notificacoes.open', $notificacao->id) }}"
                                    class="flex items-start gap-3 px-4 py-3 transition hover:bg-slate-50"
                                >
                                    {{-- Ícone --}}
                                    <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full
                                        {{ $tipo === 'os_criada'       ? 'bg-blue-100 text-blue-600' : '' }}
                                        {{ $tipo === 'os_status'       ? 'bg-amber-100 text-amber-600' : '' }}
                                        {{ $tipo === 'mensagem_portal' ? 'bg-green-100 text-green-600' : '' }}
                                        {{ !in_array($tipo, ['os_criada','os_status','mensagem_portal']) ? 'bg-slate-100 text-slate-500' : '' }}
                                    ">
                                        @if($tipo === 'os_criada')
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                                        @elseif($tipo === 'os_status')
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                                        @elseif($tipo === 'mensagem_portal')
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                        @else
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                                        @endif
                                    </div>

                                    {{-- Texto --}}
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[12.5px] font-semibold text-slate-800 leading-snug truncate">
                                            {{ $data['titulo'] ?? 'Notificação' }}
                                            <span class="ml-1 inline-block h-1.5 w-1.5 rounded-full bg-blue-500 align-middle"></span>
                                        </p>
                                        <p class="text-[11.5px] text-slate-500 leading-snug line-clamp-2 mt-0.5">{{ $data['mensagem'] ?? '' }}</p>
                                        <p class="text-[11px] text-slate-400 mt-1">{{ $notificacao->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                {{-- Footer --}}
                <div class="border-t border-slate-100 p-2">
                    <a
                        href="{{ route('app.notificacoes.index') }}"
                        @click="open = false"
                        class="flex w-full items-center justify-center rounded-lg px-3 py-2 text-[12.5px] font-medium text-slate-500 transition hover:bg-slate-50 hover:text-slate-900"
                    >
                        Ver todas as notificações
                    </a>
                </div>
            </div>
        </div>

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
