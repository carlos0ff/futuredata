<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel') — Future Data</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&family=DM+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    @stack('styles')
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
    class="min-h-screen bg-[#f0f2f6] text-slate-900 antialiased [font-family:'DM_Sans',sans-serif]"
>

{{-- ═══ TOOLTIP GLOBAL ═══ --}}
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

{{-- ═══ SIDEBAR ═══ --}}
@include('layouts.partials.sidebar')

{{-- ═══ CONTEÚDO PRINCIPAL ═══ --}}
<div
    :class="collapsed ? 'lg:ml-[68px]' : 'lg:ml-[240px]'"
    class="flex min-h-screen flex-1 flex-col transition-[margin] duration-300 ease-in-out"
>
    {{-- Header --}}
    @include('layouts.partials.header')

    {{-- Flash alerts --}}
    @include('layouts.partials.alerts')

    {{-- Page content --}}
    <main class="flex-1 px-5 py-6 pb-12 sm:px-7">
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
