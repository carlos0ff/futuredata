<!DOCTYPE html>
<html lang="pt-BR" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $ordem->codigo_publico }} — Portal do Cliente · Future Data</title>
    <meta name="description" content="Acompanhe sua Ordem de Serviço {{ $ordem->codigo_publico }} em tempo real.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700;9..40,800;9..40,900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-mono-code { font-family: 'JetBrains Mono', monospace; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { animation: fadeInUp 0.4s ease both; }
        .animate-fade-up-1 { animation: fadeInUp 0.4s ease 0.05s both; }
        .animate-fade-up-2 { animation: fadeInUp 0.4s ease 0.1s both; }
        .animate-fade-up-3 { animation: fadeInUp 0.4s ease 0.15s both; }
        .animate-fade-up-4 { animation: fadeInUp 0.4s ease 0.2s both; }
        .hero-bg { background: radial-gradient(ellipse at 70% 0%, rgba(59,130,246,0.15) 0%, transparent 60%), radial-gradient(ellipse at 0% 100%, rgba(99,102,241,0.10) 0%, transparent 60%), linear-gradient(135deg, #090b12 0%, #0d1120 40%, #111827 100%); }
        .card-shine { position: relative; overflow: hidden; }
        .card-shine::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent); }
        .msg-scroll { scroll-behavior: smooth; }
        .msg-scroll::-webkit-scrollbar { width: 4px; }
        .msg-scroll::-webkit-scrollbar-track { background: transparent; }
        .msg-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }
    </style>
</head>

@php
    $statusBg = [
        'entrada'            => 'bg-slate-500/20 text-slate-200 ring-1 ring-slate-400/30',
        'analise'            => 'bg-amber-500/20 text-amber-200 ring-1 ring-amber-400/30',
        'execucao'           => 'bg-blue-500/20 text-blue-200 ring-1 ring-blue-400/30',
        'aguardando_cliente' => 'bg-purple-500/20 text-purple-200 ring-1 ring-purple-400/30',
        'em_teste'           => 'bg-cyan-500/20 text-cyan-200 ring-1 ring-cyan-400/30',
        'finalizado'         => 'bg-emerald-500/20 text-emerald-200 ring-1 ring-emerald-400/30',
        'cancelado'          => 'bg-red-500/20 text-red-200 ring-1 ring-red-400/30',
    ];
    $statusDotHero = [
        'entrada'            => 'bg-slate-400',
        'analise'            => 'bg-amber-400',
        'execucao'           => 'bg-blue-400',
        'aguardando_cliente' => 'bg-purple-400',
        'em_teste'           => 'bg-cyan-400',
        'finalizado'         => 'bg-emerald-400',
        'cancelado'          => 'bg-red-400',
    ];
    $statusBadgeLight = [
        'entrada'            => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
        'analise'            => 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
        'execucao'           => 'bg-blue-100 text-blue-700 ring-1 ring-blue-200',
        'aguardando_cliente' => 'bg-purple-100 text-purple-700 ring-1 ring-purple-200',
        'em_teste'           => 'bg-cyan-100 text-cyan-700 ring-1 ring-cyan-200',
        'finalizado'         => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
        'cancelado'          => 'bg-red-100 text-red-600 ring-1 ring-red-200',
    ];

    $timelineSteps = [
        ['label' => 'Recebimento',   'desc' => 'Equipamento recebido na assistência',  'statuses' => ['entrada']],
        ['label' => 'Diagnóstico',   'desc' => 'Análise técnica do problema',           'statuses' => ['analise']],
        ['label' => 'Em execução',   'desc' => 'Reparo em andamento pela equipe',       'statuses' => ['execucao', 'aguardando_cliente']],
        ['label' => 'Em teste',      'desc' => 'Testes e controle de qualidade',        'statuses' => ['em_teste']],
        ['label' => 'Concluído',     'desc' => 'Pronto para retirada',                  'statuses' => ['finalizado']],
    ];

    $statusToStep = [
        'entrada'            => 0,
        'analise'            => 1,
        'execucao'           => 2,
        'aguardando_cliente' => 2,
        'em_teste'           => 3,
        'finalizado'         => 4,
        'cancelado'          => -1,
    ];
    $currentStep = $statusToStep[$ordem->status] ?? 0;

    $stepLabels = ['Entrada', 'Diagnóstico', 'Execução', 'Teste', 'Concluído'];

    $whatsappTecnico = $ordem->cliente?->telefone
        ? 'https://wa.me/55' . preg_replace('/\D/', '', $ordem->cliente->telefone) . '?text=' . urlencode('Olá! Consultei o portal e tenho uma dúvida sobre minha OS ' . $ordem->codigo_publico)
        : null;
@endphp

<body
    x-data="{
        copied: false,
        budgetConfirm: null,
        copyCode() {
            navigator.clipboard.writeText(window.location.href);
            this.copied = true;
            setTimeout(() => { this.copied = false; }, 2500);
        },
        shareWhatsApp() {
            const url = encodeURIComponent('Acompanhe minha OS {{ $ordem->codigo_publico }}: ' + window.location.href);
            window.open('https://wa.me/?text=' + url, '_blank');
        }
    }"
    class="min-h-screen bg-[#f1f5f9] antialiased"
>

{{-- ══════════════════════════════════════════════════════════
     HEADER PREMIUM
══════════════════════════════════════════════════════════ --}}
<header class="sticky top-0 z-50 border-b border-white/[0.08] bg-[#090b12]/95 backdrop-blur-md">
    <div class="mx-auto flex h-[58px] max-w-6xl items-center gap-4 px-4 sm:px-6">

        {{-- Logo / Brand --}}
        <div class="flex items-center gap-2.5">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 shadow-lg shadow-blue-500/25">
                <img src="{{ asset('images/futuredata.png') }}" class="h-[18px] w-auto object-contain brightness-0 invert" alt="Future Data" onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-white font-black text-[11px]\'>FD</span>'">
            </div>
            <div class="leading-none">
                <p class="text-[13px] font-bold text-white">Future Data</p>
                <p class="text-[10.5px] text-white/40">Assistência Técnica</p>
            </div>
            <div class="mx-2 hidden h-4 w-px bg-white/10 sm:block"></div>
            <span class="hidden rounded-md bg-white/8 px-2 py-0.5 text-[11px] font-medium text-white/50 ring-1 ring-white/10 sm:inline">
                Portal do Cliente
            </span>
        </div>

        {{-- Right actions --}}
        <div class="ml-auto flex items-center gap-2">
            {{-- Código badge com botão copiar --}}
            <button
                @click="copyCode()"
                class="group flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-1.5 text-[12px] font-medium text-white/70 transition-all duration-150 hover:border-white/20 hover:bg-white/10 hover:text-white"
            >
                <span class="font-mono-code text-[11.5px] font-semibold text-white">{{ $ordem->codigo_publico }}</span>
                <span x-show="!copied" class="text-white/30 transition group-hover:text-white/60">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                </span>
                <span x-show="copied" class="text-emerald-400" style="display:none">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </span>
            </button>

            {{-- WhatsApp --}}
            @if($whatsappTecnico)
            <a href="{{ $whatsappTecnico }}" target="_blank" rel="noopener"
               class="hidden items-center gap-1.5 rounded-xl bg-[#25d366] px-3.5 py-1.5 text-[12px] font-semibold text-white shadow-md shadow-[#25d366]/20 transition hover:bg-[#1ebe5d] sm:flex">
                <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                Falar conosco
            </a>
            @endif
        </div>
    </div>
</header>


{{-- ══════════════════════════════════════════════════════════
     HERO SECTION — DARK GRADIENT
══════════════════════════════════════════════════════════ --}}
<section class="hero-bg pb-14 pt-10 text-white">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">

        <div class="flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between">

            {{-- Left info --}}
            <div class="animate-fade-up">
                <div class="mb-3 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/8 px-3 py-1 text-[11.5px] font-medium text-white/60 ring-1 ring-white/10">
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDotHero[$ordem->status] ?? 'bg-slate-400' }} {{ $ordem->status !== 'finalizado' && $ordem->status !== 'cancelado' ? 'animate-pulse' : '' }}"></span>
                        Ordem de Serviço
                    </span>
                </div>

                <h1 class="font-mono-code text-[44px] font-bold leading-none tracking-tight text-white sm:text-[52px]">
                    {{ $ordem->codigo_publico }}
                </h1>

                <div class="mt-4 flex flex-wrap items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-[12.5px] font-semibold {{ $statusBg[$ordem->status] ?? 'bg-slate-500/20 text-slate-200' }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDotHero[$ordem->status] ?? 'bg-slate-400' }}"></span>
                        {{ $ordem->status_label }}
                    </span>
                    <span class="text-[13px] text-white/50">
                        Aberta em {{ $ordem->created_at->format('d/m/Y \à\s H\hi') }}
                    </span>
                </div>

                <div class="mt-5 flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-400/30 to-blue-600/30 text-[13px] font-bold text-white ring-1 ring-white/15">
                        {{ strtoupper(substr($ordem->cliente?->nome ?? 'C', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-[14.5px] font-semibold text-white">{{ $ordem->cliente?->nome ?? '—' }}</p>
                        @if($ordem->cliente?->telefone)
                        <p class="text-[12px] text-white/40">{{ $ordem->cliente->telefone }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right info cards --}}
            <div class="flex flex-row flex-wrap gap-3 lg:flex-col lg:min-w-[220px] animate-fade-up-1">
                @if($ordem->previsao_entrega)
                <div class="flex-1 rounded-2xl bg-white/6 p-4 ring-1 ring-white/10 lg:flex-none card-shine">
                    <p class="text-[10.5px] font-semibold uppercase tracking-wider text-white/40">Previsão de Entrega</p>
                    <p class="mt-1.5 font-mono-code text-[20px] font-bold text-white">{{ $ordem->previsao_entrega->format('d/m/Y') }}</p>
                    <p class="mt-0.5 text-[11.5px] text-white/50">
                        @if($ordem->previsao_entrega->isPast())
                            @if($ordem->status === 'finalizado') Entrega concluída
                            @else <span class="text-amber-400">Prazo encerrado</span>
                            @endif
                        @else
                            {{ now()->diffInDays($ordem->previsao_entrega) }} dia(s) restante(s)
                        @endif
                    </p>
                </div>
                @endif

                @if($ordem->total > 0)
                <div class="flex-1 rounded-2xl bg-white/6 p-4 ring-1 ring-white/10 lg:flex-none card-shine">
                    <p class="text-[10.5px] font-semibold uppercase tracking-wider text-white/40">Valor do Orçamento</p>
                    <p class="mt-1.5 font-mono-code text-[20px] font-bold text-white">R$ {{ number_format($ordem->total, 2, ',', '.') }}</p>
                    @if($ordem->status_orcamento)
                    <span class="mt-1 inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10.5px] font-semibold
                        {{ $ordem->status_orcamento === 'aprovado' ? 'bg-emerald-500/20 text-emerald-300' : ($ordem->status_orcamento === 'recusado' ? 'bg-red-500/20 text-red-300' : 'bg-amber-500/20 text-amber-300') }}">
                        {{ ucfirst($ordem->status_orcamento) }}
                    </span>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Mini progress timeline --}}
        @if($ordem->status !== 'cancelado')
        <div class="mt-10 animate-fade-up-2">
            <div class="flex items-center">
                @foreach($stepLabels as $i => $label)
                <div class="flex flex-1 flex-col items-center">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full text-[11px] font-bold transition-all
                        {{ $i < $currentStep ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30'
                           : ($i === $currentStep ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30 ring-4 ring-blue-500/20'
                           : 'bg-white/8 text-white/30 ring-1 ring-white/10') }}">
                        @if($i < $currentStep)
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <span class="mt-1.5 hidden text-[10px] font-medium sm:block
                        {{ $i < $currentStep ? 'text-emerald-400' : ($i === $currentStep ? 'text-blue-300' : 'text-white/25') }}">
                        {{ $label }}
                    </span>
                </div>
                @if(!$loop->last)
                <div class="mx-1 mb-6 h-px flex-1 sm:mb-7
                    {{ $i < $currentStep ? 'bg-gradient-to-r from-emerald-500 to-emerald-400' : 'bg-white/10' }}"></div>
                @endif
                @endforeach
            </div>
        </div>
        @else
        <div class="mt-8 flex items-center gap-3 rounded-2xl bg-red-500/10 px-5 py-4 ring-1 ring-red-500/20">
            <svg class="h-5 w-5 shrink-0 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M15 9l-6 6M9 9l6 6"/></svg>
            <p class="text-[13.5px] font-medium text-red-300">Esta ordem de serviço foi cancelada.</p>
        </div>
        @endif
    </div>
</section>


{{-- ══════════════════════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════════════════════ --}}
<main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">

    {{-- Flash message --}}
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-[13.5px] font-medium text-emerald-700 shadow-sm">
        <svg class="h-4 w-4 shrink-0 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-[13.5px] font-medium text-red-700 shadow-sm">
        <svg class="h-4 w-4 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ $errors->first() }}
    </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- ─────────────────────────────────────────────────────
             COLUNA ESQUERDA (span 2)
        ───────────────────────────────────────────────────── --}}
        <div class="space-y-6 lg:col-span-2">

            {{-- ── DETALHES DA OS ── --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm animate-fade-up-2">
                <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h4m-7 5h10a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 2v5h5"/></svg>
                    </div>
                    <h2 class="text-[14px] font-bold text-slate-900">Detalhes da Ordem</h2>
                    <span class="ml-auto inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11.5px] font-semibold {{ $statusBadgeLight[$ordem->status] ?? 'bg-slate-100 text-slate-600' }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDotHero[$ordem->status] ?? 'bg-slate-400' }} {{ $ordem->status !== 'finalizado' && $ordem->status !== 'cancelado' ? 'animate-pulse' : '' }}"></span>
                        {{ $ordem->status_label }}
                    </span>
                </div>

                <div class="p-6">
                    <dl class="grid grid-cols-2 gap-x-6 gap-y-5 sm:grid-cols-3">
                        <div>
                            <dt class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Número da OS</dt>
                            <dd class="mt-1 font-mono-code text-[13px] font-semibold text-slate-900">{{ $ordem->numero }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Código público</dt>
                            <dd class="mt-1 font-mono-code text-[13px] font-semibold text-slate-900">{{ $ordem->codigo_publico }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Data de abertura</dt>
                            <dd class="mt-1 text-[13px] font-medium text-slate-700">{{ $ordem->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Cliente</dt>
                            <dd class="mt-1 text-[13px] font-medium text-slate-700">{{ $ordem->cliente?->nome ?? '—' }}</dd>
                        </div>
                        @if($ordem->equipamento)
                        <div>
                            <dt class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Equipamento</dt>
                            <dd class="mt-1 text-[13px] font-medium text-slate-700">{{ $ordem->equipamento->tipo }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Marca / Modelo</dt>
                            <dd class="mt-1 text-[13px] font-medium text-slate-700">{{ $ordem->equipamento->marca }} {{ $ordem->equipamento->modelo }}</dd>
                        </div>
                        @if($ordem->equipamento->numero_serie)
                        <div class="sm:col-span-2">
                            <dt class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Número de série</dt>
                            <dd class="mt-1 font-mono-code text-[13px] font-medium text-slate-700">{{ $ordem->equipamento->numero_serie }}</dd>
                        </div>
                        @endif
                        @endif
                        @if($ordem->tecnico)
                        <div>
                            <dt class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Técnico responsável</dt>
                            <dd class="mt-1 text-[13px] font-medium text-slate-700">{{ $ordem->tecnico->name }}</dd>
                        </div>
                        @endif
                    </dl>

                    {{-- Defeito relatado --}}
                    <div class="mt-5 rounded-xl bg-amber-50 p-4 ring-1 ring-amber-100">
                        <p class="mb-1.5 flex items-center gap-1.5 text-[10.5px] font-bold uppercase tracking-wider text-amber-600">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                            Defeito Relatado
                        </p>
                        <p class="text-[13.5px] leading-relaxed text-amber-900">{{ $ordem->problema_relatado }}</p>
                    </div>

                    {{-- Diagnóstico técnico --}}
                    @if($ordem->diagnostico)
                    <div class="mt-4 rounded-xl bg-blue-50 p-4 ring-1 ring-blue-100">
                        <p class="mb-1.5 flex items-center gap-1.5 text-[10.5px] font-bold uppercase tracking-wider text-blue-600">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Diagnóstico Técnico
                        </p>
                        <p class="text-[13.5px] leading-relaxed text-blue-900">{{ $ordem->diagnostico }}</p>
                    </div>
                    @endif

                    {{-- Solução aplicada --}}
                    @if($ordem->solucao)
                    <div class="mt-4 rounded-xl bg-emerald-50 p-4 ring-1 ring-emerald-100">
                        <p class="mb-1.5 flex items-center gap-1.5 text-[10.5px] font-bold uppercase tracking-wider text-emerald-600">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                            Solução Aplicada
                        </p>
                        <p class="text-[13.5px] leading-relaxed text-emerald-900">{{ $ordem->solucao }}</p>
                    </div>
                    @endif
                </div>
            </div>


            {{-- ── TIMELINE ── --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm animate-fade-up-3">
                <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                    </div>
                    <h2 class="text-[14px] font-bold text-slate-900">Progresso do Atendimento</h2>
                </div>

                <div class="p-6">
                    @if($ordem->status === 'cancelado')
                    <div class="flex items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-100">
                        <svg class="h-5 w-5 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M15 9l-6 6M9 9l6 6"/></svg>
                        <p class="text-[13.5px] font-medium text-red-700">Ordem cancelada — entre em contato para mais informações.</p>
                    </div>
                    @else
                    <div class="space-y-0">
                        @foreach($timelineSteps as $i => $step)
                        @php
                            $done    = $i < $currentStep;
                            $active  = $i === $currentStep;
                            $pending = $i > $currentStep;
                            $historicoEntry = $ordem->historico->first(fn($h) => in_array($h->status_novo, $step['statuses']));
                        @endphp
                        <div class="relative flex gap-4 {{ !$loop->last ? 'pb-7' : '' }}">
                            {{-- Connecting line --}}
                            @if(!$loop->last)
                            <div class="absolute left-4 top-8 bottom-0 w-0.5 {{ $done ? 'bg-gradient-to-b from-emerald-400 to-emerald-300' : 'bg-slate-100' }}"></div>
                            @endif

                            {{-- Icon circle --}}
                            <div class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full transition-all
                                {{ $done ? 'bg-emerald-500 shadow-lg shadow-emerald-500/25'
                                   : ($active ? 'bg-blue-600 shadow-lg shadow-blue-600/25 ring-4 ring-blue-100'
                                   : 'bg-slate-100') }}">
                                @if($done)
                                <svg class="h-3.5 w-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @elseif($active)
                                <span class="h-2.5 w-2.5 rounded-full bg-white animate-pulse"></span>
                                @else
                                <span class="h-2 w-2 rounded-full bg-slate-300"></span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 pt-0.5">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="text-[13.5px] font-semibold {{ $pending ? 'text-slate-400' : 'text-slate-900' }}">
                                            {{ $step['label'] }}
                                        </p>
                                        <p class="text-[12.5px] {{ $pending ? 'text-slate-300' : 'text-slate-500' }}">
                                            {{ $step['desc'] }}
                                        </p>
                                        @if($active && $ordem->status === 'aguardando_cliente')
                                        <span class="mt-1.5 inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-0.5 text-[11px] font-semibold text-purple-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                                            Aguardando sua aprovação
                                        </span>
                                        @elseif($active)
                                        <span class="mt-1.5 inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-0.5 text-[11px] font-semibold text-blue-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                            Em andamento
                                        </span>
                                        @endif
                                    </div>
                                    <div class="shrink-0 text-right">
                                        @if($done)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-600">
                                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            Concluído
                                        </span>
                                        @if($historicoEntry)
                                        <p class="mt-1 text-[10.5px] text-slate-400">{{ $historicoEntry->created_at->format('d/m/Y H:i') }}</p>
                                        @endif
                                        @elseif($active)
                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-[11px] font-semibold text-blue-600">
                                            Atual
                                        </span>
                                        @if($historicoEntry)
                                        <p class="mt-1 text-[10.5px] text-slate-400">desde {{ $historicoEntry->created_at->format('d/m/Y') }}</p>
                                        @endif
                                        @else
                                        <span class="text-[11px] text-slate-300">Pendente</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>


            {{-- ── ORÇAMENTO ── --}}
            @if($ordem->valor_servico > 0 || $ordem->valor_pecas > 0)
            <div id="orcamento" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm animate-fade-up-3"
                 x-data="{ confirming: false, action: null }">
                <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <h2 class="text-[14px] font-bold text-slate-900">Orçamento</h2>
                    @if($ordem->status_orcamento)
                    <span class="ml-auto inline-flex items-center rounded-full px-2.5 py-1 text-[11.5px] font-semibold
                        {{ $ordem->status_orcamento === 'aprovado' ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200'
                           : ($ordem->status_orcamento === 'recusado' ? 'bg-red-100 text-red-700 ring-1 ring-red-200'
                           : 'bg-amber-100 text-amber-700 ring-1 ring-amber-200') }}">
                        {{ ucfirst($ordem->status_orcamento) }}
                    </span>
                    @endif
                </div>

                <div class="p-6">
                    {{-- Tabela de valores --}}
                    <div class="overflow-hidden rounded-xl border border-slate-100 bg-slate-50">
                        <table class="w-full text-[13.5px]">
                            <tbody class="divide-y divide-slate-100">
                                @if($ordem->valor_servico > 0)
                                <tr class="hover:bg-slate-100/50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-slate-700">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                            Mão de obra / Serviço
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-slate-900">R$ {{ number_format($ordem->valor_servico, 2, ',', '.') }}</td>
                                </tr>
                                @endif
                                @if($ordem->valor_pecas > 0)
                                <tr class="hover:bg-slate-100/50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-slate-700">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                                            Peças e componentes
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-slate-900">R$ {{ number_format($ordem->valor_pecas, 2, ',', '.') }}</td>
                                </tr>
                                @endif
                                @if($ordem->desconto > 0)
                                <tr class="hover:bg-slate-100/50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-emerald-700">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m9 14 6-6m-3.5-.5h.01M14.5 14.5h.01"/><circle cx="12" cy="12" r="10"/></svg>
                                            Desconto aplicado
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-emerald-700">− R$ {{ number_format($ordem->desconto, 2, ',', '.') }}</td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot class="border-t-2 border-slate-200 bg-white">
                                <tr>
                                    <td class="px-4 py-4 text-[14px] font-bold text-slate-900">Total</td>
                                    <td class="px-4 py-4 text-right font-mono-code text-[18px] font-bold text-slate-900">R$ {{ number_format($ordem->total, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Botões de aprovação --}}
                    @if(!$ordem->status_orcamento || $ordem->status_orcamento === 'pendente')
                    <div class="mt-5">
                        <p class="mb-3 text-[12.5px] text-slate-500">Revise o orçamento acima e confirme sua decisão:</p>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <button @click="action = 'aprovado'; confirming = true"
                                class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-[13.5px] font-bold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-600/30 active:scale-[0.98]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Aprovar orçamento
                            </button>
                            <button @click="action = 'recusado'; confirming = true"
                                class="flex flex-1 items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-[13.5px] font-bold text-red-700 transition hover:bg-red-100 active:scale-[0.98]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
                                Recusar orçamento
                            </button>
                        </div>
                    </div>
                    @elseif($ordem->status_orcamento === 'aprovado')
                    <div class="mt-4 flex items-center gap-3 rounded-xl bg-emerald-50 p-4 ring-1 ring-emerald-100">
                        <svg class="h-5 w-5 shrink-0 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[13.5px] font-medium text-emerald-800">Orçamento aprovado! Em breve nossa equipe dará andamento ao serviço.</p>
                    </div>
                    @else
                    <div class="mt-4 flex items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-100">
                        <svg class="h-5 w-5 shrink-0 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M15 9l-6 6M9 9l6 6"/></svg>
                        <p class="text-[13.5px] font-medium text-red-800">Orçamento recusado. Nossa equipe entrará em contato.</p>
                    </div>
                    @endif
                </div>

                {{-- Modal de confirmação --}}
                <div x-show="confirming" x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     @click.self="confirming = false"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4 backdrop-blur-sm"
                     style="display:none">
                    <div x-show="confirming" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         class="w-full max-w-sm overflow-hidden rounded-2xl bg-white shadow-2xl">
                        <div class="p-6">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl"
                                 :class="action === 'aprovado' ? 'bg-emerald-50' : 'bg-red-50'">
                                <svg class="h-6 w-6" :class="action === 'aprovado' ? 'text-emerald-500' : 'text-red-500'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <template x-if="action === 'aprovado'"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></template>
                                    <template x-if="action === 'recusado'"><path stroke-linecap="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></template>
                                </svg>
                            </div>
                            <h3 class="text-[16px] font-bold text-slate-900"
                                x-text="action === 'aprovado' ? 'Confirmar aprovação?' : 'Confirmar recusa?'"></h3>
                            <p class="mt-1.5 text-[13px] text-slate-500"
                               x-text="action === 'aprovado' ? 'Ao aprovar, nossa equipe iniciará o serviço imediatamente.' : 'Ao recusar, nossa equipe entrará em contato para discutir outras opções.'"></p>
                        </div>
                        <div class="flex gap-3 border-t border-slate-100 px-6 py-4">
                            <button @click="confirming = false" type="button"
                                class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-[13px] font-semibold text-slate-700 transition hover:bg-slate-100">
                                Cancelar
                            </button>
                            <form method="POST" action="{{ route('portal.orcamento', $ordem->codigo_publico) }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="acao" :value="action">
                                <button type="submit"
                                    class="w-full rounded-xl px-4 py-2.5 text-[13px] font-bold text-white transition"
                                    :class="action === 'aprovado' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700'"
                                    x-text="action === 'aprovado' ? 'Aprovar' : 'Recusar'">
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif


            {{-- ── MENSAGENS ── --}}
            <div id="mensagens" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm animate-fade-up-4">
                <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h2 class="text-[14px] font-bold text-slate-900">Mensagens</h2>
                    @php $totalMsg = $ordem->mensagens->count(); @endphp
                    @if($totalMsg > 0)
                    <span class="ml-auto rounded-full bg-indigo-100 px-2.5 py-0.5 text-[11.5px] font-semibold text-indigo-700">{{ $totalMsg }}</span>
                    @endif
                </div>

                {{-- Chat area --}}
                <div class="msg-scroll h-80 overflow-y-auto bg-slate-50/50 p-4">
                    @if($ordem->mensagens->isEmpty())
                    <div class="flex h-full flex-col items-center justify-center text-center">
                        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">
                            <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <p class="text-[13.5px] font-semibold text-slate-700">Nenhuma mensagem ainda</p>
                        <p class="mt-1 text-[12.5px] text-slate-400">Envie uma mensagem para nossa equipe técnica.</p>
                    </div>
                    @else
                    <div class="space-y-3">
                        @foreach($ordem->mensagens as $msg)
                        @if($msg->tipo === 'tecnico')
                        {{-- Mensagem da assistência (esquerda) --}}
                        <div class="flex items-end gap-2">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-[10px] font-bold text-white shadow">
                                FD
                            </div>
                            <div class="max-w-[75%]">
                                <div class="rounded-2xl rounded-bl-sm bg-white px-4 py-2.5 shadow-sm ring-1 ring-slate-100">
                                    <p class="text-[13px] leading-relaxed text-slate-800">{{ $msg->conteudo }}</p>
                                </div>
                                <p class="mt-1 text-[10.5px] text-slate-400 ml-1">
                                    {{ $msg->autor?->name ?? 'Equipe Técnica' }} · {{ $msg->created_at->format('d/m H:i') }}
                                </p>
                            </div>
                        </div>
                        @else
                        {{-- Mensagem do cliente (direita) --}}
                        <div class="flex items-end justify-end gap-2">
                            <div class="max-w-[75%]">
                                <div class="rounded-2xl rounded-br-sm bg-blue-600 px-4 py-2.5 shadow-md shadow-blue-600/15">
                                    <p class="text-[13px] leading-relaxed text-white">{{ $msg->conteudo }}</p>
                                </div>
                                <p class="mt-1 text-right text-[10.5px] text-slate-400 mr-1">
                                    Você · {{ $msg->created_at->format('d/m H:i') }}
                                    @if($msg->lida_em)
                                    <span class="text-blue-400">✓✓</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-slate-400 to-slate-600 text-[10px] font-bold text-white shadow">
                                {{ strtoupper(substr($ordem->cliente?->nome ?? 'C', 0, 1)) }}
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Input de nova mensagem --}}
                <form method="POST" action="{{ route('portal.message.store', $ordem->codigo_publico) }}" class="border-t border-slate-100 p-4"
                      x-data="{ msg: '' }">
                    @csrf
                    <div class="flex gap-2">
                        <input
                            type="text"
                            name="mensagem"
                            x-model="msg"
                            placeholder="Digite sua mensagem para a equipe técnica..."
                            autocomplete="off"
                            maxlength="1000"
                            class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-[13.5px] text-slate-900 placeholder-slate-400 outline-none ring-0 transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-400/20"
                        >
                        <button
                            type="submit"
                            :disabled="!msg.trim()"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-40 active:scale-95"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m22 2-11 11M22 2 15 22l-4-9-9-4 20-7z"/></svg>
                        </button>
                    </div>
                    <p class="mt-1.5 text-[11px] text-slate-400">Nossa equipe responderá em breve. Mensagens são moderadas.</p>
                </form>
            </div>

        </div>{{-- fim coluna esquerda --}}


        {{-- ─────────────────────────────────────────────────────
             COLUNA DIREITA
        ───────────────────────────────────────────────────── --}}
        <div class="space-y-4">

            {{-- ── EQUIPAMENTO ── --}}
            @if($ordem->equipamento)
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm animate-fade-up-2">
                <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-3.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                    </div>
                    <h3 class="text-[13px] font-bold text-slate-900">Equipamento</h3>
                    @if($ordem->equipamento->em_garantia)
                    <span class="ml-auto rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10.5px] font-bold text-emerald-700 ring-1 ring-emerald-200">Garantia</span>
                    @endif
                </div>
                <div class="divide-y divide-slate-50 p-1">
                    @foreach([
                        ['Tipo', $ordem->equipamento->tipo],
                        ['Marca', $ordem->equipamento->marca],
                        ['Modelo', $ordem->equipamento->modelo],
                        ['N° de série', $ordem->equipamento->numero_serie],
                    ] as [$label, $value])
                    @if($value)
                    <div class="flex items-baseline justify-between gap-3 px-4 py-2.5">
                        <span class="shrink-0 text-[11.5px] font-medium text-slate-400">{{ $label }}</span>
                        <span class="text-right text-[12.5px] font-semibold text-slate-800 {{ $label === 'N° de série' ? 'font-mono-code text-[11.5px]' : '' }}">{{ $value }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── TÉCNICO ── --}}
            @if($ordem->tecnico)
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm animate-fade-up-2">
                <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-3.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <h3 class="text-[13px] font-bold text-slate-900">Técnico Responsável</h3>
                </div>
                <div class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 text-[13px] font-bold text-white shadow-md shadow-purple-500/20">
                            {{ strtoupper(substr($ordem->tecnico->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-[13.5px] font-semibold text-slate-900">{{ $ordem->tecnico->name }}</p>
                            <p class="text-[12px] text-slate-400">Técnico especialista</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── PREVISÃO DE ENTREGA ── --}}
            @if($ordem->previsao_entrega)
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm animate-fade-up-3">
                <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-3.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    </div>
                    <h3 class="text-[13px] font-bold text-slate-900">Previsão de Entrega</h3>
                </div>
                <div class="p-4 text-center">
                    <p class="font-mono-code text-[26px] font-bold text-slate-900">
                        {{ $ordem->previsao_entrega->format('d/m/Y') }}
                    </p>
                    <p class="mt-0.5 text-[12px] text-slate-500">
                        {{ ucfirst($ordem->previsao_entrega->translatedFormat('l')) }}
                    </p>
                    @php $diff = now()->diffInDays($ordem->previsao_entrega, false); @endphp
                    <div class="mt-3 rounded-lg {{ $diff < 0 ? 'bg-red-50 text-red-700' : ($diff === 0 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }} px-3 py-2 text-[12.5px] font-semibold">
                        @if($ordem->status === 'finalizado') Pronto para retirada!
                        @elseif($diff < 0) Prazo encerrado há {{ abs((int)$diff) }} dia(s)
                        @elseif($diff === 0) Entrega prevista para hoje!
                        @else Faltam {{ (int)$diff }} dia(s)
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- ── COMPARTILHAR ── --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm animate-fade-up-3">
                <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-3.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 text-slate-600">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                    </div>
                    <h3 class="text-[13px] font-bold text-slate-900">Compartilhar</h3>
                </div>
                <div class="space-y-2 p-4">
                    <button @click="copyCode()"
                        class="flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-[13px] font-semibold text-slate-700 transition hover:bg-slate-100 hover:border-slate-300 active:scale-[0.98]">
                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        <span x-text="copied ? '✓ Link copiado!' : 'Copiar link da OS'"></span>
                    </button>
                    <button @click="shareWhatsApp()"
                        class="flex w-full items-center gap-3 rounded-xl border border-[#25d366]/30 bg-[#25d366]/8 px-4 py-2.5 text-[13px] font-semibold text-[#128c4e] transition hover:bg-[#25d366]/15 active:scale-[0.98]">
                        <svg class="h-4 w-4 text-[#25d366]" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                        Compartilhar via WhatsApp
                    </button>
                </div>
            </div>

            {{-- ── SEGURANÇA ── --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-900 to-slate-800 p-5 text-center shadow-sm animate-fade-up-4">
                <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-white/10">
                    <svg class="h-5 w-5 text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </div>
                <p class="text-[12.5px] font-semibold text-white">Portal Seguro</p>
                <p class="mt-1 text-[11px] text-white/40">Conexão criptografada · Dados protegidos</p>
                <div class="mt-3 flex items-center justify-center gap-1.5">
                    <div class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-[10.5px] font-medium text-emerald-400">Sistema online</span>
                </div>
            </div>

        </div>{{-- fim coluna direita --}}
    </div>{{-- fim grid --}}


    {{-- ── FOTOS E DOCUMENTOS ── --}}
    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm animate-fade-up-4">
        <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-pink-50 text-pink-600">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0z"/></svg>
            </div>
            <h2 class="text-[14px] font-bold text-slate-900">Fotos e Documentos</h2>
            <span class="ml-auto rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-medium text-slate-500">Em breve</span>
        </div>

        <div class="p-6">
            {{-- Grid de placeholders fake --}}
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                @foreach(['bg-gradient-to-br from-blue-100 to-blue-200', 'bg-gradient-to-br from-purple-100 to-purple-200', 'bg-gradient-to-br from-slate-100 to-slate-200', 'bg-gradient-to-br from-amber-100 to-amber-200', 'bg-gradient-to-br from-emerald-100 to-emerald-200', 'bg-gradient-to-br from-rose-100 to-rose-200'] as $bg)
                <div class="group relative aspect-square overflow-hidden rounded-xl {{ $bg }} ring-1 ring-black/5">
                    <div class="flex h-full flex-col items-center justify-center gap-1 opacity-40">
                        <svg class="h-6 w-6 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0z"/></svg>
                    </div>
                    <div class="absolute inset-0 flex items-end justify-center pb-2 opacity-0 transition group-hover:opacity-100">
                        <span class="rounded-lg bg-black/50 px-2 py-0.5 text-[10px] font-medium text-white backdrop-blur-sm">Em breve</span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 flex items-center gap-2 rounded-xl bg-slate-50 px-4 py-3 ring-1 ring-slate-100">
                <svg class="h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 16v-4M12 8h.01"/></svg>
                <p class="text-[12.5px] text-slate-500">Nossa equipe adicionará fotos do equipamento em breve. Você será notificado via WhatsApp.</p>
            </div>
        </div>
    </div>

</main>


{{-- ══════════════════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════════════════ --}}
<footer class="mt-12 border-t border-slate-200 bg-white py-8">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
            <div class="flex items-center gap-2.5">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#0a0c15]">
                    <img src="{{ asset('images/futuredata.png') }}" class="h-4 w-auto brightness-0 invert" alt="" onerror="this.style.display='none'">
                </div>
                <div>
                    <p class="text-[12.5px] font-bold text-slate-900">Future Data</p>
                    <p class="text-[11px] text-slate-400">Assistência Técnica</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-[11.5px] text-slate-400">
                <span>OS: <span class="font-mono-code font-semibold text-slate-700">{{ $ordem->codigo_publico }}</span></span>
                <span class="h-3 w-px bg-slate-200"></span>
                <span>Portal do Cliente</span>
                <span class="h-3 w-px bg-slate-200"></span>
                <span>{{ date('Y') }}</span>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
