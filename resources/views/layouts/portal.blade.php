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

@stack('scripts')
</body>
</html>
