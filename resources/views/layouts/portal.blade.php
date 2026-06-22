<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Portal do Cliente') — Future Data</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    @stack('styles')
</head>
<body class="min-h-screen bg-[#f2f4f7] text-slate-900 antialiased [font-family:'DM_Sans',sans-serif]">

@php
    $portalCliente  = \App\Models\Cliente::find(session('portal_cliente_id'));
    $portalNome     = $portalCliente?->nome ?? 'Cliente';
    $portalLogado   = session()->has('portal_cliente_id');
    $portalTelefone = preg_replace('/\D/', '', $portalCliente?->telefone ?? '');
    $portalOrdem    = $portalCliente
        ? \App\Models\Ordem::where('cliente_id', $portalCliente->id)
            ->whereNotIn('status', ['cancelado'])
            ->latest()->first()
            ?? \App\Models\Ordem::where('cliente_id', $portalCliente->id)->latest()->first()
        : null;
@endphp

@php $navTransparente = trim($__env->yieldContent('transparent-nav')) !== ''; @endphp
<nav x-data="{ scrolled: window.scrollY > 24 }"
     @scroll.window="scrolled = window.scrollY > 24"
     @if($navTransparente)
     :class="scrolled ? 'bg-[#0d1117]/85 backdrop-blur-md border-white/[0.06]' : 'bg-transparent border-transparent'"
     class="fixed top-0 inset-x-0 z-50 border-b transition-all duration-300"
     @else
     class="fixed top-0 inset-x-0 z-50 border-b bg-[#0d1117]/85 backdrop-blur-md border-white/[0.06]"
     @endif>
    <div class="mx-auto flex h-16 max-w-7xl items-center gap-4 px-4 sm:px-6">

        {{-- Logo --}}
        <a href="{{ $portalLogado ? route('portal.index') : '#' }}"
           class="flex items-center gap-3 shrink-0 transition-opacity hover:opacity-80">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 shadow-lg shadow-blue-600/30">
                <img src="{{ asset('images/futuredata.png') }}" class="h-4 w-auto brightness-0 invert" alt="Future Data">
            </div>
            <div class="hidden min-[420px]:block">
                <p class="text-[13.5px] font-bold text-white leading-none">Future Data</p>
                <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500 mt-1">Portal do Cliente</p>
            </div>
        </a>

        <div class="ml-auto flex items-center gap-2.5">

            {{-- Nav: Minhas OS --}}
            @if($portalLogado)
            <a href="{{ route('portal.index') }}"
               class="hidden sm:flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-[12.5px] font-semibold transition-colors
                      {{ request()->routeIs('portal.index') ? 'bg-white/10 text-white ring-1 ring-white/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/>
                </svg>
                Minhas OS
            </a>
            @endif

            {{-- WhatsApp --}}
            @if($portalTelefone)
            <a href="https://wa.me/55{{ $portalTelefone }}" target="_blank" rel="noopener"
               class="hidden sm:flex items-center gap-2 rounded-xl bg-[#22c55e] hover:bg-[#16a34a] px-4 py-2 text-[12.5px] font-bold text-white transition-all shadow-lg shadow-[#22c55e]/25 hover:shadow-[#22c55e]/40 active:scale-[0.97]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                </svg>
                WhatsApp
            </a>
            @endif

            @if($portalLogado)
            @php $primeiroLetra = strtoupper(substr($portalNome, 0, 1)); $primeiroNomeNav = explode(' ', $portalNome)[0]; @endphp
            {{-- User chip --}}
            <div class="flex items-center gap-2 sm:gap-2.5 sm:pl-3 sm:ml-0.5 sm:border-l sm:border-white/10">
                <div class="flex items-center gap-2.5 rounded-xl bg-white/5 ring-1 ring-white/10 py-1.5 pl-1.5 pr-3">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 text-[11px] font-bold text-white shrink-0 shadow-md shadow-blue-600/30">
                        {{ $primeiroLetra }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[12px] font-semibold text-white leading-none max-w-[90px] truncate">{{ $primeiroNomeNav }}</p>
                        <p class="text-[9.5px] font-medium uppercase tracking-wider text-slate-500 mt-0.5">Cliente</p>
                    </div>
                </div>

                {{-- Logout --}}
                <form action="{{ route('portal.sair') }}" method="POST">
                    @csrf
                    <button type="submit" title="Sair do portal"
                        class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 hover:text-red-400 hover:bg-white/5 ring-1 ring-transparent hover:ring-white/10 transition-all">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline stroke-linecap="round" stroke-linejoin="round" points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </button>
                </form>
            </div>
            @endif

        </div>
    </div>
</nav>

@unless($navTransparente)
{{-- Compensa o header fixo nas páginas sem hero escura --}}
<div class="h-16"></div>
@endunless

@include('layouts.partials.alerts')

<main class="min-h-[calc(100vh-64px)]">
    @yield('content')
</main>


<div class="bg-white border-t border-slate-200 py-10 mt-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
            @foreach([
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0 1 12 2.944a11.955 11.955 0 0 1-8.618 3.04A12.02 12.02 0 0 0 3 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
                    'title' => 'Seguro e confiável',
                    'desc'  => 'Seus dados estão protegidos com segurança e criptografia.',
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V5a2 2 0 1 0-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9"/>',
                    'title' => 'Notificações automáticas',
                    'desc'  => 'Você recebe atualizações por WhatsApp a cada mudança.',
                ],
                [
                    'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
                    'title' => 'Atendimento rápido',
                    'desc'  => 'Nossa equipe está sempre pronta para te atender.',
                ],
                [
                    'icon' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
                    'title' => 'Sua opinião importa',
                    'desc'  => 'Avalie nosso atendimento quando retirar o equipamento.',
                ],
            ] as $f)
            <div class="flex flex-col items-center text-center sm:flex-row sm:items-start sm:text-left gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-100">
                    <svg class="h-5 w-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $f['icon'] !!}</svg>
                </div>
                <div>
                    <p class="text-[13px] font-semibold text-slate-800">{{ $f['title'] }}</p>
                    <p class="text-[12px] text-slate-500 mt-0.5 leading-relaxed">{{ $f['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


<footer class="bg-[#0d1117] text-slate-400 py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-4">

            <div>
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#22c55e]">
                        <img src="{{ asset('images/futuredata.png') }}" class="h-4 w-auto brightness-0 invert" alt="Future Data">
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-white leading-none">Future Data</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">Assistência Técnica</p>
                    </div>
                </div>
                <p class="text-[11.5px] text-slate-600">CNPJ: 00.000.000/0001-00</p>
            </div>

            <div>
                <h4 class="text-[11.5px] font-semibold text-slate-300 uppercase tracking-widest mb-3">Horário</h4>
                <p class="text-[12.5px]">Seg a Sex: 08:00 às 18:00</p>
                <p class="text-[12.5px]">Sáb: 08:00 às 12:00</p>
            </div>

            <div>
                <h4 class="text-[11.5px] font-semibold text-slate-300 uppercase tracking-widest mb-3">Endereço</h4>
                <p class="text-[12.5px]">Rua das Flores, 123 - Centro</p>
                <p class="text-[12.5px]">São Paulo - SP</p>
            </div>

            <div>
                <h4 class="text-[11.5px] font-semibold text-slate-300 uppercase tracking-widest mb-3">Siga-nos</h4>
                <div class="flex gap-2">
                    <a href="#" class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 transition text-slate-400 hover:text-white">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 0 1 1.772 1.153 4.902 4.902 0 0 1 1.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 0 1-1.153 1.772 4.902 4.902 0 0 1-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 0 1-1.772-1.153 4.902 4.902 0 0 1-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 0 1 1.153-1.772A4.902 4.902 0 0 1 5.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 0 0-.748-1.15 3.098 3.098 0 0 0-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 1 1 0 10.27 5.135 5.135 0 0 1 0-10.27zm0 1.802a3.333 3.333 0 1 0 0 6.666 3.333 3.333 0 0 0 0-6.666zm5.338-3.205a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4z"/>
                        </svg>
                    </a>
                    <a href="#" class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/5 hover:bg-white/10 transition text-slate-400 hover:text-white">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#22c55e]/20 hover:bg-[#22c55e]/30 transition text-[#22c55e]">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-8 border-t border-white/5 pt-6 text-center text-[11.5px] text-slate-600">
            © {{ date('Y') }} Future Data Assistência Técnica. Todos os direitos reservados.
        </div>
    </div>
</footer>

@include('layouts.partials.live-refresh')

{{-- ── Widget de chat flutuante (apenas para clientes logados com OS) ──────── --}}
@if($portalLogado && $portalOrdem)
<div
    x-data="chatWidget({
        ordemId:  {{ $portalOrdem->id }},
        ordemNum: '{{ $portalOrdem->numero }}',
        nome:     '{{ addslashes(explode(' ', $portalNome)[0]) }}',
        threadUrl: '{{ route('portal.mensagens.thread', $portalOrdem) }}',
        storeUrl:  '{{ route('portal.mensagens.store') }}',
        csrfToken: '{{ csrf_token() }}'
    })"
    class="fixed bottom-5 right-5 z-50 flex flex-col items-end gap-3"
>
    {{-- ── Janela do chat ───────────────────────────────────────────────── --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="w-[360px] overflow-hidden rounded-2xl shadow-2xl shadow-slate-900/20 border border-slate-200 bg-white origin-bottom-right"
        style="display:none"
    >
        {{-- Header --}}
        <div class="relative flex items-center gap-3 bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3.5">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[13.5px] font-bold text-white leading-tight">Future Data</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                    </span>
                    <span class="text-[11px] font-medium text-blue-100">Há operadores online!</span>
                </div>
            </div>
            <button @click="open = false" class="flex h-7 w-7 items-center justify-center rounded-full text-white/70 hover:text-white hover:bg-white/10 transition-colors">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- OS badge --}}
        <div class="flex items-center gap-2 border-b border-slate-100 bg-slate-50 px-4 py-2">
            <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="text-[11.5px] font-semibold text-slate-500">OS {{ $portalOrdem->numero }}</span>
        </div>

        {{-- Mensagens --}}
        <div
            x-ref="messagesArea"
            class="flex h-64 flex-col gap-3 overflow-y-auto px-4 py-4 scroll-smooth"
        >
            {{-- Loading --}}
            <template x-if="loading && messages.length === 0">
                <div class="flex items-center justify-center py-8">
                    <svg class="h-5 w-5 animate-spin text-slate-300" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </div>
            </template>

            {{-- Empty state (antes do primeiro carregamento) --}}
            <template x-if="!loading && messages.length === 0">
                <div class="flex flex-col items-center justify-center gap-2 py-8 text-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-[12.5px] font-semibold text-slate-600">Iniciando chat...</p>
                </div>
            </template>

            {{-- Mensagens --}}
            <template x-for="msg in messages" :key="msg.id">
                <div :class="msg.from === 'me' ? 'flex justify-end' : 'flex justify-start items-end'" class="gap-2">

                    {{-- Avatar do bot / operador (lado esquerdo) --}}
                    <template x-if="msg.from !== 'me'">
                        <div class="shrink-0 self-end">
                            {{-- Operador humano: círculo colorido com iniciais --}}
                            <template x-if="msg.from === 'operator'">
                                <div
                                    class="flex h-7 w-7 items-center justify-center rounded-full text-[10px] font-bold text-white"
                                    :style="'background-color:' + (msg.author_color || '#2563eb')"
                                    :title="msg.author_name"
                                    x-text="msg.author_initials"
                                ></div>
                            </template>
                            {{-- Bot: ícone FD --}}
                            <template x-if="msg.from === 'bot'">
                                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-[9px] font-bold text-white">FD</div>
                            </template>
                        </div>
                    </template>

                    <div class="max-w-[78%]">
                        {{-- Nome do operador acima da mensagem --}}
                        <template x-if="msg.from === 'operator'">
                            <p class="mb-1 text-[10.5px] font-semibold text-slate-500" x-text="msg.author_name"></p>
                        </template>
                        <div
                            :class="msg.from === 'me'
                                ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm'
                                : 'bg-slate-100 text-slate-800 rounded-2xl rounded-bl-sm'"
                            class="px-3.5 py-2.5"
                        >
                            <p class="text-[13px] leading-relaxed whitespace-pre-line" x-text="msg.text"></p>
                        </div>
                        <p :class="msg.from === 'me' ? 'text-right' : ''" class="mt-1 text-[10.5px] text-slate-400" x-text="msg.time"></p>
                    </div>
                </div>
            </template>
        </div>

        {{-- Chips --}}
        <div class="border-t border-slate-100 bg-slate-50/80 px-4 py-3">
            {{-- Sugestões rápidas (antes do cliente enviar a primeira mensagem) --}}
            <template x-if="clientMessages() === 0">
                <div class="flex flex-wrap gap-2">
                    <template x-for="chip in chips" :key="chip">
                        <button
                            @click="sendChip(chip)"
                            class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-medium text-slate-700 shadow-sm transition hover:border-blue-300 hover:text-blue-600"
                            x-text="chip"
                        ></button>
                    </template>
                </div>
            </template>
            {{-- Falar com técnico: só aparece após o bot ter respondido ao menos uma vez --}}
            <template x-if="clientMessages() > 0">
                <button
                    @click="sendChip('Desejo falar com o técnico')"
                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-[12px] font-semibold text-emerald-700 transition hover:bg-emerald-100 active:scale-[0.98]"
                >
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Falar com o técnico
                </button>
            </template>
        </div>

        {{-- Input --}}
        <div class="flex items-center gap-2.5 border-t border-slate-100 bg-white px-4 py-3">
            <input
                x-model="input"
                @keydown.enter.prevent="send()"
                type="text"
                placeholder="Digite sua mensagem..."
                class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-[13px] text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100"
            />
            <button
                @click="send()"
                :disabled="!input.trim() || sending"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm transition hover:bg-blue-700 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ── Botão flutuante ─────────────────────────────────────────────────── --}}
    <button
        @click="toggleChat()"
        class="group relative flex items-center gap-3 overflow-hidden rounded-2xl bg-blue-600 py-3 pl-4 pr-5 text-white shadow-lg shadow-blue-600/40 transition-all duration-300 hover:bg-blue-700 hover:shadow-blue-600/50 active:scale-[0.97]"
    >
        {{-- Dot de notificação --}}
        <template x-if="unread > 0 && !open">
            <span class="absolute -right-0.5 -top-0.5 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white" x-text="unread"></span>
        </template>

        <span class="relative flex h-8 w-8 shrink-0 items-center justify-center">
            {{-- Ícone chat (quando fechado) --}}
            <svg
                x-show="!open"
                class="h-5 w-5 transition-transform group-hover:scale-110"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            {{-- Ícone fechar (quando aberto) --}}
            <svg
                x-show="open"
                class="h-5 w-5"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                style="display:none"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </span>

        <div x-show="!open" class="leading-tight" style="">
            <p class="text-[13px] font-bold leading-none">Fale conosco</p>
            <div class="mt-0.5 flex items-center gap-1">
                <span class="relative flex h-1.5 w-1.5">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-75"></span>
                    <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                </span>
                <span class="text-[11px] font-medium text-blue-100">estamos online!</span>
            </div>
        </div>
    </button>
</div>

<script>
function chatWidget({ ordemId, ordemNum, nome, threadUrl, storeUrl, csrfToken }) {
    return {
        open: false,
        loading: false,
        sending: false,
        input: '',
        messages: [],
        unread: 0,
        nome,
        chips: ['Ver status da OS', 'Qual o prazo?', 'Informações do orçamento'],
        _poll: null,

        toggleChat() {
            this.open = !this.open;
            if (this.open) {
                this.unread = 0;
                this.loadMessages();
                this.startPolling();
            } else {
                this.stopPolling();
            }
        },

        async loadMessages() {
            this.loading = true;
            try {
                const res  = await fetch(threadUrl);
                const data = await res.json();
                const prev = this.messages.length;
                this.messages = data;
                if (!this.open && data.length > prev) this.unread += (data.length - prev);
                this.$nextTick(() => this.scrollBottom());
            } catch {}
            this.loading = false;
        },

        async send() {
            const text = this.input.trim();
            if (!text || this.sending) return;

            this.messages.push({
                id: Date.now(), from: 'me', text,
                time: new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' }),
            });
            this.input = '';
            this.$nextTick(() => this.scrollBottom());

            this.sending = true;
            try {
                await fetch(storeUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ conteudo: text, ordem_id: ordemId }),
                });
                await this.loadMessages();
            } catch {}
            this.sending = false;
        },

        sendChip(chip) {
            this.input = chip;
            this.send();
        },

        clientMessages() {
            return this.messages.filter(m => m.from === 'me').length;
        },

        scrollBottom() {
            const el = this.$refs.messagesArea;
            if (el) el.scrollTop = el.scrollHeight;
        },

        startPolling() {
            this.stopPolling();
            this._poll = setInterval(() => this.loadMessages(), 5000);
        },

        stopPolling() {
            if (this._poll) { clearInterval(this._poll); this._poll = null; }
        },
    };
}
</script>
@endif

@stack('scripts')
</body>
</html>
