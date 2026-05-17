<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal do Cliente') — Future Data</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap" rel="stylesheet">

    @stack('styles')
</head>
<body
    x-data="{ mobileMenuOpen: false }"
    @keydown.escape.window="mobileMenuOpen = false"
    class="min-h-screen bg-slate-50 text-slate-900 antialiased [font-family:'DM_Sans',sans-serif]"
>

{{-- Mobile overlay --}}
<div
    x-show="mobileMenuOpen"
    @click="mobileMenuOpen = false"
    x-transition:enter="transition-opacity ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 bg-black/60 lg:hidden"
    style="display:none"
></div>

{{-- Sidebar --}}
<aside
    :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-slate-200 bg-white transition-transform duration-300 ease-in-out lg:translate-x-0"
>
    {{-- Brand --}}
    <div class="flex h-16 items-center gap-3 border-b border-slate-100 px-5">
        <div class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-xl bg-[#0d0f16]">
            <img src="{{ asset('images/futuredata.png') }}" class="h-6 w-auto object-contain brightness-0 invert" alt="Future Data">
        </div>
        <div>
            <h2 class="text-[13.5px] font-bold text-slate-900 leading-none">Future Data</h2>
            <p class="text-[11px] text-slate-500 mt-0.5">Portal do Cliente</p>
        </div>
        <button @click="mobileMenuOpen = false" class="ml-auto flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 lg:hidden">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
        @php
            $portalNavItems = [
                ['href' => '/portal', 'label' => 'Minhas OS', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2M9 12h6M9 16h4"/>', 'match' => 'portal'],
                ['href' => '/portal/mensagens', 'label' => 'Mensagens', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>', 'match' => 'portal/mensagens'],
                ['href' => '/portal/perfil', 'label' => 'Meu Perfil', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>', 'match' => 'portal/perfil'],
            ];
        @endphp

        @foreach($portalNavItems as $item)
            @php $isActive = request()->is($item['match']); @endphp
            <a href="{{ $item['href'] }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-[13.5px] font-medium transition-all duration-150 {{ $isActive ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-[17px] w-[17px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $item['icon'] !!}</svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    {{-- User --}}
    <div class="border-t border-slate-100 p-3">
        <div class="flex items-center gap-3 rounded-xl px-3 py-2">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-[11px] font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name ?? 'C', 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="truncate text-[12.5px] font-semibold text-slate-900">{{ auth()->user()->name ?? 'Cliente' }}</p>
                <p class="text-[11px] text-slate-500">Portal do cliente</p>
            </div>
        </div>
        <form action="{{ route('auth.sair') }}" method="POST" class="mt-1">
            @csrf
            <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-[12.5px] font-medium text-red-500 transition hover:bg-red-50">
                <svg class="h-[14px] w-[14px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Sair
            </button>
        </form>
    </div>
</aside>

{{-- Main content area --}}
<div class="flex min-h-screen flex-col lg:pl-64">

    {{-- Portal header --}}
    <header class="sticky top-0 z-30 flex h-14 items-center gap-3 border-b border-slate-200 bg-white/95 px-4 backdrop-blur-sm sm:px-6">
        <button @click="mobileMenuOpen = true" type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 transition hover:bg-slate-100 lg:hidden">
            <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
        </button>
        <div class="flex-1 text-[13px] font-semibold text-slate-900">@yield('title', 'Portal do Cliente')</div>
        <div class="flex items-center gap-2 text-[12.5px] text-slate-500">
            Olá, <span class="font-semibold text-slate-900">{{ explode(' ', auth()->user()->name ?? 'Cliente')[0] }}</span>
        </div>
    </header>

    {{-- Flash alerts --}}
    @include('layouts.partials.alerts')

    {{-- Content --}}
    <main class="flex-1 px-4 py-5 pb-10 sm:px-6">
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
