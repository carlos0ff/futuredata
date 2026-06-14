@extends('layouts.app')
@section('title', 'Serviços')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', ['items' => [['label' => 'Serviços']]])
@endsection

@section('content')

@php
$total      = $services->count();
$avgPrice   = $total > 0 ? $services->avg('base_price') : 0;
$maxPrice   = $total > 0 ? $services->max('base_price') : 0;
$totalValue = $services->sum('base_price');

$catCount = $services->groupBy('category')->map->count()->sortDesc();
$topCat   = $catCount->keys()->first() ?? '—';

$categoryMeta = [
    'Diagnóstico'            => ['color' => 'bg-amber-500',   'light' => 'bg-amber-50 text-amber-700 ring-amber-200',   'icon_color' => 'text-amber-500'],
    'Reparo de Hardware'     => ['color' => 'bg-blue-500',    'light' => 'bg-blue-50 text-blue-700 ring-blue-200',       'icon_color' => 'text-blue-500'],
    'Reparo de Software'     => ['color' => 'bg-violet-500',  'light' => 'bg-violet-50 text-violet-700 ring-violet-200', 'icon_color' => 'text-violet-500'],
    'Limpeza'                => ['color' => 'bg-cyan-500',    'light' => 'bg-cyan-50 text-cyan-700 ring-cyan-200',       'icon_color' => 'text-cyan-500'],
    'Instalação'             => ['color' => 'bg-emerald-500', 'light' => 'bg-emerald-50 text-emerald-700 ring-emerald-200', 'icon_color' => 'text-emerald-500'],
    'Manutenção Preventiva'  => ['color' => 'bg-orange-500',  'light' => 'bg-orange-50 text-orange-700 ring-orange-200', 'icon_color' => 'text-orange-500'],
    'Recuperação de Dados'   => ['color' => 'bg-rose-500',    'light' => 'bg-rose-50 text-rose-700 ring-rose-200',       'icon_color' => 'text-rose-500'],
    'Outros'                 => ['color' => 'bg-slate-400',   'light' => 'bg-slate-100 text-slate-600 ring-slate-200',   'icon_color' => 'text-slate-400'],
];
$getMeta = fn($cat) => $categoryMeta[$cat] ?? $categoryMeta['Outros'];
@endphp

<div x-data="servicosApp()" x-init="init()" class="space-y-5">

    {{-- ── Header ──────────────────────────────────────────────────────────── --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Serviços</h1>
            <p class="mt-0.5 text-[13px] text-slate-400">
                {{ $total }} serviço{{ $total !== 1 ? 's' : '' }} cadastrado{{ $total !== 1 ? 's' : '' }} · portfólio de R$ {{ number_format($totalValue, 2, ',', '.') }}
            </p>
        </div>
        @if(!$atLimit)
        <button @click="openModal()"
                class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-[13px] font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Novo Serviço
        </button>
        @else
        <button disabled class="inline-flex shrink-0 cursor-not-allowed items-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-[13px] font-semibold text-slate-400">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
            Limite atingido
        </button>
        @endif
    </div>

    {{-- ── Stats ───────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-black/[0.05]">
            <p class="text-[10.5px] font-semibold uppercase tracking-widest text-slate-400">Serviços</p>
            <p class="mt-1 text-[28px] font-black leading-none tabular-nums text-slate-900">{{ $total }}</p>
            <p class="mt-1 text-[11px] text-slate-400">de {{ $freeLimit }} disponíveis</p>
            {{-- barra de progresso --}}
            <div class="mt-2.5 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full transition-all duration-500
                            {{ $total >= $freeLimit ? 'bg-amber-400' : ($total >= $freeLimit - 1 ? 'bg-amber-400' : 'bg-blue-500') }}"
                     style="width: {{ $freeLimit > 0 ? min(100, round($total / $freeLimit * 100)) : 0 }}%"></div>
            </div>
        </div>

        <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-black/[0.05]">
            <p class="text-[10.5px] font-semibold uppercase tracking-widest text-slate-400">Preço Médio</p>
            <p class="mt-1 text-[22px] font-black leading-none tabular-nums text-blue-600">
                R$ {{ number_format($avgPrice, 2, ',', '.') }}
            </p>
            <p class="mt-1 text-[11px] text-slate-400">média do portfólio</p>
        </div>

        <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-black/[0.05]">
            <p class="text-[10.5px] font-semibold uppercase tracking-widest text-slate-400">Mais Caro</p>
            <p class="mt-1 text-[22px] font-black leading-none tabular-nums text-emerald-600">
                R$ {{ number_format($maxPrice, 2, ',', '.') }}
            </p>
            <p class="mt-1 text-[11px] text-slate-400">maior valor cadastrado</p>
        </div>

        <div class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-black/[0.05]">
            <p class="text-[10.5px] font-semibold uppercase tracking-widest text-slate-400">Top Categoria</p>
            <p class="mt-1.5 text-[13px] font-bold leading-tight text-slate-800">{{ $topCat }}</p>
            @if($topCat !== '—')
            <p class="mt-1 text-[11px] text-slate-400">{{ $catCount->first() }} serviço{{ $catCount->first() !== 1 ? 's' : '' }}</p>
            @else
            <p class="mt-1 text-[11px] text-slate-400">sem dados ainda</p>
            @endif
        </div>
    </div>

    {{-- ── Alerta de limite ────────────────────────────────────────────────── --}}
    @if($atLimit)
    <div class="flex flex-col gap-3 rounded-2xl border border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50 px-5 py-4 sm:flex-row sm:items-center">
        <div class="flex items-start gap-3 flex-1">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100">
                <svg class="h-5 w-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                </svg>
            </div>
            <div>
                <p class="text-[13px] font-bold text-amber-900">Limite do plano gratuito atingido</p>
                <p class="mt-0.5 text-[12px] text-amber-700">Você usou todos os {{ $freeLimit }} serviços disponíveis no plano gratuito. Faça upgrade para adicionar mais.</p>
            </div>
        </div>
        <button class="shrink-0 self-start rounded-xl bg-amber-500 px-4 py-2 text-[12.5px] font-semibold text-white transition hover:bg-amber-600 sm:self-auto">
            Ver planos
        </button>
    </div>
    @endif

    {{-- ── Busca + filtros ─────────────────────────────────────────────────── --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        {{-- Busca --}}
        <div class="relative flex-1">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" x-model="search" placeholder="Buscar por nome ou categoria..."
                   class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 text-[13px] text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            <button x-show="search" @click="search=''"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Tabs de categoria --}}
        <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5 sm:pb-0">
            <button @click="catFilter = ''"
                    :class="catFilter === '' ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-500 ring-1 ring-slate-200 hover:ring-slate-300'"
                    class="h-9 shrink-0 rounded-lg px-3.5 text-[12px] font-semibold transition">
                Todos
                <span :class="catFilter === '' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'"
                      class="ml-1.5 inline-flex h-4 min-w-[16px] items-center justify-center rounded-full px-1 text-[10px] font-bold">
                    {{ $total }}
                </span>
            </button>
            @foreach($categories as $cat)
            @php $cnt = $services->where('category', $cat)->count(); @endphp
            @if($cnt > 0)
            <button @click="catFilter = '{{ $cat }}'"
                    :class="catFilter === '{{ $cat }}' ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-500 ring-1 ring-slate-200 hover:ring-slate-300'"
                    class="h-9 shrink-0 rounded-lg px-3.5 text-[12px] font-semibold transition">
                {{ $cat }}
                <span :class="catFilter === '{{ $cat }}' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'"
                      class="ml-1.5 inline-flex h-4 min-w-[16px] items-center justify-center rounded-full px-1 text-[10px] font-bold">
                    {{ $cnt }}
                </span>
            </button>
            @endif
            @endforeach
        </div>
    </div>

    {{-- ── Grid de cards ───────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

        {{-- Card adicionar --}}
        @if(!$atLimit)
        <button @click="openModal()"
                class="group relative flex min-h-[190px] flex-col items-center justify-center gap-3 overflow-hidden rounded-2xl border-2 border-dashed border-slate-200 bg-white/60 transition-all duration-200 hover:border-blue-400 hover:bg-blue-50/40 hover:shadow-md">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 ring-8 ring-slate-50 transition duration-200 group-hover:bg-blue-100 group-hover:ring-blue-50">
                <svg class="h-5 w-5 text-slate-400 transition group-hover:text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
            </div>
            <div class="text-center">
                <p class="text-[13px] font-semibold text-slate-500 transition group-hover:text-blue-600">Adicionar serviço</p>
                <p class="mt-0.5 text-[11.5px] text-slate-400">{{ $total }}/{{ $freeLimit }} utilizados</p>
            </div>
        </button>
        @endif

        {{-- Cards de serviços --}}
        @foreach($services as $service)
        @php $meta = $getMeta($service->category); @endphp
        <div x-show="matchesService(@js(['name' => $service->name, 'category' => $service->category]))"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="group relative flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.05] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:ring-black/[0.08]">

            {{-- Acento de cor no topo --}}
            <div class="h-1 w-full {{ $meta['color'] }}"></div>

            <div class="flex flex-1 flex-col p-5">

                {{-- Ações (aparecem no hover) --}}
                <div class="absolute right-3 top-4 flex gap-1 opacity-0 transition-all duration-150 group-hover:opacity-100">
                    <button @click="openEdit(@js(['id' => $service->id, 'name' => $service->name, 'category' => $service->category, 'base_price' => $service->base_price]))"
                            title="Editar"
                            class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <button @click="confirmDelete({{ $service->id }}, '{{ addslashes($service->name) }}')"
                            title="Remover"
                            class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:border-red-300 hover:bg-red-50 hover:text-red-500">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                        </svg>
                    </button>
                </div>

                {{-- Ícone + categoria --}}
                <div class="mb-4 flex items-center gap-2.5">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl ring-1 ring-black/[0.06]
                                {{ str_replace('bg-', 'bg-', $meta['color']) }} bg-opacity-10">
                        @switch($service->category)
                            @case('Diagnóstico')
                                <svg class="h-5 w-5 {{ $meta['icon_color'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                                @break
                            @case('Reparo de Hardware')
                                <svg class="h-5 w-5 {{ $meta['icon_color'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l5.653-4.655m5.833-4.329a8.575 8.575 0 0 0-1.651-.93l-.094.083-1.58 1.58.108.135a9.869 9.869 0 0 1 1.274 2.005l.05.112 1.822-1.022.031-.02a4.5 4.5 0 0 0-.96-1.943Z"/></svg>
                                @break
                            @case('Reparo de Software')
                                <svg class="h-5 w-5 {{ $meta['icon_color'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0 0 21 18V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v12a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                @break
                            @case('Limpeza')
                                <svg class="h-5 w-5 {{ $meta['icon_color'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15m-6.075-5.196a24.301 24.301 0 0 0-3.45 0M9.75 3.104V3m4.5.104V3m0 5.814a5.25 5.25 0 0 1-4.5 0M5 14.5l-.75.75m14.55.75-.75-.75M5 14.5v6h14v-6"/></svg>
                                @break
                            @case('Instalação')
                                <svg class="h-5 w-5 {{ $meta['icon_color'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25"/></svg>
                                @break
                            @case('Manutenção Preventiva')
                                <svg class="h-5 w-5 {{ $meta['icon_color'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                @break
                            @case('Recuperação de Dados')
                                <svg class="h-5 w-5 {{ $meta['icon_color'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 2.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125m16.5 2.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/></svg>
                                @break
                            @default
                                <svg class="h-5 w-5 {{ $meta['icon_color'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/></svg>
                        @endswitch
                    </div>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold ring-1 {{ $meta['light'] }}">
                        {{ $service->category }}
                    </span>
                </div>

                {{-- Nome --}}
                <p class="mb-auto pr-14 text-[14px] font-bold leading-snug text-slate-900">{{ $service->name }}</p>

                {{-- Preço --}}
                <div class="mt-4 flex items-end justify-between border-t border-slate-100 pt-4">
                    <div>
                        <p class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Preço base</p>
                        <p class="mt-0.5 text-[20px] font-black tabular-nums leading-none text-slate-900">
                            R$ {{ number_format($service->base_price, 2, ',', '.') }}
                        </p>
                    </div>
                    <button @click="openEdit(@js(['id' => $service->id, 'name' => $service->name, 'category' => $service->category, 'base_price' => $service->base_price]))"
                            class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-400 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Estado vazio (sem serviços ou sem resultados) --}}
        @if($services->isEmpty())
        <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-white py-16 text-center shadow-sm">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
                <svg class="h-8 w-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                </svg>
            </div>
            <p class="mt-4 text-[15px] font-semibold text-slate-700">Nenhum serviço cadastrado</p>
            <p class="mt-1 text-[13px] text-slate-400">Clique em "Novo Serviço" para começar.</p>
            @if(!$atLimit)
            <button @click="openModal()"
                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-[13px] font-semibold text-white shadow-md shadow-blue-600/20 transition hover:bg-blue-700">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Adicionar primeiro serviço
            </button>
            @endif
        </div>
        @endif

    </div>

    {{-- Nenhum resultado na busca --}}
    <div x-show="hasServices && visibleCount === 0" class="rounded-2xl border border-dashed border-slate-200 bg-white py-12 text-center shadow-sm">
        <svg class="mx-auto h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <p class="mt-3 text-[14px] font-semibold text-slate-500">Nenhum resultado encontrado</p>
        <p class="mt-1 text-[13px] text-slate-400">Tente outros termos de busca ou remova o filtro de categoria.</p>
        <button @click="search=''; catFilter=''" class="mt-4 inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-4 py-2 text-[13px] font-medium text-slate-600 transition hover:bg-slate-50">
            Limpar filtros
        </button>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         MODAL — CRIAR / EDITAR
    ══════════════════════════════════════════════════════════════════════ --}}
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-end justify-center sm:items-center sm:px-4"
         @keydown.escape.window="closeModal()"
         style="display:none">

        {{-- Backdrop blur --}}
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]" @click="closeModal()"></div>

        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-[0.96]"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-180"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:scale-[0.97]"
             class="relative z-10 w-full max-w-2xl overflow-hidden rounded-t-3xl bg-white shadow-2xl sm:rounded-2xl">

            {{-- ── Banner de cabeçalho ─────────────────────────────────────── --}}
            <div :class="editId
                    ? 'from-amber-500 to-orange-500'
                    : 'from-blue-600 to-blue-500'"
                 class="relative overflow-hidden bg-gradient-to-r px-8 py-7">

                {{-- Círculos decorativos --}}
                <div class="pointer-events-none absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10"></div>
                <div class="pointer-events-none absolute -bottom-8 right-8 h-20 w-20 rounded-full bg-white/10"></div>

                {{-- Handle mobile --}}
                <div class="mx-auto mb-3 h-1 w-8 rounded-full bg-white/30 sm:hidden"></div>

                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        {{-- Ícone do modo --}}
                        <div class="flex h-13 w-13 shrink-0 items-center justify-center rounded-2xl bg-white/20 ring-1 ring-white/30">
                            <template x-if="!editId">
                                <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                            </template>
                            <template x-if="editId">
                                <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </template>
                        </div>
                        <div>
                            <h2 class="text-[20px] font-bold text-white"
                                x-text="editId ? 'Editar Serviço' : 'Novo Serviço'"></h2>
                            <p class="mt-1 text-[13px] text-white/70"
                               x-text="editId ? 'Atualize nome, categoria e preço.' : 'Cadastre um serviço no seu portfólio.'"></p>
                        </div>
                    </div>
                    <button @click="closeModal()"
                            class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-white/60 transition hover:bg-white/20 hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Preview do nome (atualiza em tempo real) --}}
                <div x-show="form.name"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mt-3 flex items-center gap-2 rounded-xl bg-white/15 px-3 py-2">
                    <svg class="h-3.5 w-3.5 shrink-0 text-white/70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/></svg>
                    <span class="truncate text-[12.5px] font-semibold text-white" x-text="form.name"></span>
                    <span x-show="form.base_price > 0"
                          class="ml-auto shrink-0 text-[12px] font-bold text-white/80"
                          x-text="'R$ ' + parseFloat(form.base_price || 0).toLocaleString('pt-BR', {minimumFractionDigits:2, maximumFractionDigits:2})"></span>
                </div>
            </div>

            {{-- ── Corpo do formulário ──────────────────────────────────────── --}}
            <div class="overflow-y-auto" style="max-height: calc(100dvh - 220px)">
            <form :action="editId ? '/app/servicos/' + editId : '/app/servicos'"
                  method="POST"
                  @submit="submitting = true"
                  class="space-y-7 p-8">
                @csrf
                <template x-if="editId">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                {{-- ── Campo: Nome ─────────────────────────────────────────── --}}
                <div>
                    <div class="mb-2.5 flex items-center justify-between">
                        <label class="text-[13.5px] font-semibold text-slate-700">
                            Nome do serviço <span class="text-red-500">*</span>
                        </label>
                        <span class="text-[12px] tabular-nums"
                              :class="form.name.length > 90 ? 'text-red-400 font-semibold' : 'text-slate-400'"
                              x-text="form.name.length + '/100'"></span>
                    </div>
                    <div class="relative">
                        <input type="text" name="name" x-model="form.name"
                               placeholder="Ex: Troca de tela, Formatação, Diagnóstico…"
                               required maxlength="100"
                               x-ref="nameInput"
                               class="w-full rounded-xl border px-4 py-3.5 text-[14px] text-slate-800 outline-none transition placeholder:text-slate-400
                                      @error('name')
                                          border-red-300 bg-red-50/40 focus:border-red-400 focus:ring-2 focus:ring-red-100
                                      @else
                                          border-slate-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100
                                      @enderror">
                        <div x-show="form.name.length > 0"
                             class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                        </div>
                    </div>
                    @error('name')
                    <p class="mt-1.5 flex items-center gap-1 text-[11.5px] font-medium text-red-500">
                        <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- ── Campo: Categoria (seletor visual) ───────────────────── --}}
                <div>
                    <label class="mb-3 block text-[13.5px] font-semibold text-slate-700">
                        Categoria <span class="text-red-500">*</span>
                    </label>
                    {{-- Input oculto para envio --}}
                    <input type="hidden" name="category" :value="form.category" required>

                    <div class="grid grid-cols-4 gap-3">
                        @php
                        $catCards = [
                            ['value' => 'Diagnóstico',           'short' => 'Diagnóstico',  'ring' => 'ring-amber-400',   'bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'icon_sel' => 'text-amber-600'],
                            ['value' => 'Reparo de Hardware',    'short' => 'Hardware',      'ring' => 'ring-blue-400',    'bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'icon_sel' => 'text-blue-600'],
                            ['value' => 'Reparo de Software',    'short' => 'Software',      'ring' => 'ring-violet-400',  'bg' => 'bg-violet-50',  'text' => 'text-violet-700',  'icon_sel' => 'text-violet-600'],
                            ['value' => 'Limpeza',               'short' => 'Limpeza',       'ring' => 'ring-cyan-400',    'bg' => 'bg-cyan-50',    'text' => 'text-cyan-700',    'icon_sel' => 'text-cyan-600'],
                            ['value' => 'Instalação',            'short' => 'Instalação',    'ring' => 'ring-emerald-400', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'icon_sel' => 'text-emerald-600'],
                            ['value' => 'Manutenção Preventiva', 'short' => 'Manutenção',    'ring' => 'ring-orange-400',  'bg' => 'bg-orange-50',  'text' => 'text-orange-700',  'icon_sel' => 'text-orange-600'],
                            ['value' => 'Recuperação de Dados',  'short' => 'Recuperação',   'ring' => 'ring-rose-400',    'bg' => 'bg-rose-50',    'text' => 'text-rose-700',    'icon_sel' => 'text-rose-600'],
                            ['value' => 'Outros',                'short' => 'Outros',        'ring' => 'ring-slate-300',   'bg' => 'bg-slate-50',   'text' => 'text-slate-600',   'icon_sel' => 'text-slate-500'],
                        ];
                        $catIcons = [
                            'Diagnóstico'            => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>',
                            'Reparo de Hardware'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l5.653-4.655m5.833-4.329a8.575 8.575 0 0 0-1.651-.93l-.094.083-1.58 1.58.108.135a9.869 9.869 0 0 1 1.274 2.005l.05.112 1.822-1.022.031-.02a4.5 4.5 0 0 0-.96-1.943Z"/>',
                            'Reparo de Software'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0 0 21 18V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v12a2.25 2.25 0 0 0 2.25 2.25Z"/>',
                            'Limpeza'                => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15m-6.075-5.196a24.301 24.301 0 0 0-3.45 0M9.75 3.104V3m4.5.104V3m0 5.814a5.25 5.25 0 0 1-4.5 0M5 14.5l-.75.75m14.55.75-.75-.75M5 14.5v6h14v-6"/>',
                            'Instalação'             => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25"/>',
                            'Manutenção Preventiva'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>',
                            'Recuperação de Dados'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 2.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125m16.5 2.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>',
                            'Outros'                 => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>',
                        ];
                        @endphp

                        @foreach($catCards as $card)
                        <button type="button"
                                @click="form.category = '{{ $card['value'] }}'"
                                :class="form.category === '{{ $card['value'] }}'
                                    ? '{{ $card['ring'] }} {{ $card['bg'] }} ring-2 shadow-sm'
                                    : 'ring-1 ring-slate-200 bg-white hover:ring-slate-300 hover:bg-slate-50'"
                                class="flex flex-col items-center gap-2.5 rounded-2xl p-4 transition-all duration-150">
                            <svg class="h-6 w-6 transition"
                                 :class="form.category === '{{ $card['value'] }}' ? '{{ $card['icon_sel'] }}' : 'text-slate-400'"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                {!! $catIcons[$card['value']] !!}
                            </svg>
                            <span class="text-center text-[11.5px] font-semibold leading-tight transition"
                                  :class="form.category === '{{ $card['value'] }}' ? '{{ $card['text'] }}' : 'text-slate-500'">
                                {{ $card['short'] }}
                            </span>
                        </button>
                        @endforeach
                    </div>

                    @error('category')
                    <p class="mt-1.5 flex items-center gap-1 text-[11.5px] font-medium text-red-500">
                        <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- ── Campo: Preço base ───────────────────────────────────── --}}
                <div>
                    <label class="mb-2.5 block text-[13.5px] font-semibold text-slate-700">
                        Preço base <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[15px] font-bold text-slate-400">R$</span>
                        <input type="number" name="base_price" x-model="form.base_price"
                               placeholder="0,00" required min="0" max="99999.99" step="0.01"
                               class="w-full rounded-xl border py-3.5 pl-12 pr-4 text-[17px] font-bold text-slate-900 outline-none transition placeholder:font-normal placeholder:text-slate-400
                                      @error('base_price')
                                          border-red-300 bg-red-50/40 focus:border-red-400 focus:ring-2 focus:ring-red-100
                                      @else
                                          border-slate-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100
                                      @enderror">
                    </div>
                    {{-- Preview do preço formatado --}}
                    <div x-show="form.base_price > 0"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mt-2 flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-[12px]">
                        <svg class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                        <span class="text-emerald-700">Será cobrado </span>
                        <span class="font-bold text-emerald-800"
                              x-text="'R$ ' + parseFloat(form.base_price || 0).toLocaleString('pt-BR', {minimumFractionDigits:2, maximumFractionDigits:2})"></span>
                        <span class="text-emerald-700"> por este serviço</span>
                    </div>
                    @error('base_price')
                    <p class="mt-1.5 flex items-center gap-1 text-[11.5px] font-medium text-red-500">
                        <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- ── Ações ───────────────────────────────────────────────── --}}
                <div class="flex gap-3 border-t border-slate-100 pt-6">
                    <button type="button" @click="closeModal()"
                            class="flex-1 rounded-xl border border-slate-200 py-3.5 text-[14px] font-semibold text-slate-600 transition hover:bg-slate-50 active:bg-slate-100">
                        Cancelar
                    </button>
                    <button type="submit"
                            :disabled="submitting || !form.name.trim() || !form.category || form.base_price === '' || form.base_price < 0"
                            :class="(submitting || !form.name.trim() || !form.category || form.base_price === '' || form.base_price < 0)
                                ? 'cursor-not-allowed bg-slate-200 text-slate-400 shadow-none'
                                : (editId
                                    ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-md shadow-amber-500/30 hover:from-amber-600 hover:to-orange-600'
                                    : 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/25 hover:from-blue-700 hover:to-blue-600')"
                            class="flex flex-1 items-center justify-center gap-2 rounded-xl py-3.5 text-[14px] font-semibold transition-all">
                        <template x-if="submitting">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                        </template>
                        <template x-if="!submitting && !editId">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                        </template>
                        <template x-if="!submitting && editId">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                        </template>
                        <span x-text="submitting ? 'A guardar…' : (editId ? 'Salvar alterações' : 'Adicionar serviço')"></span>
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         MODAL — CONFIRMAR EXCLUSÃO
    ══════════════════════════════════════════════════════════════════════ --}}
    <div x-show="deleteModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         style="display:none">

        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px]" @click="deleteModal = false"></div>

        <div x-show="deleteModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative z-10 w-full max-w-sm overflow-hidden rounded-2xl bg-white shadow-2xl">

            {{-- Faixa vermelha no topo --}}
            <div class="h-1.5 w-full bg-gradient-to-r from-red-500 to-rose-500"></div>

            <div class="p-6">
                <div class="mb-4 flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-100">
                        <svg class="h-6 w-6 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[15px] font-bold text-slate-900">Remover serviço?</h3>
                        <p class="mt-1 text-[13px] leading-relaxed text-slate-500">
                            O serviço <span class="font-semibold text-slate-800" x-text="'«' + deleteTarget.name + '»'"></span> será removido permanentemente. Esta ação não pode ser desfeita.
                        </p>
                    </div>
                </div>

                <div class="flex gap-2.5">
                    <button @click="deleteModal = false"
                            class="flex-1 rounded-xl border border-slate-200 py-2.5 text-[13px] font-semibold text-slate-600 transition hover:bg-slate-50">
                        Cancelar
                    </button>
                    <form :action="'/app/servicos/' + deleteTarget.id" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-red-500 to-rose-500 py-2.5 text-[13px] font-semibold text-white shadow-md shadow-red-500/25 transition hover:from-red-600 hover:to-rose-600">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            </svg>
                            Sim, remover
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function servicosApp() {
    return {
        search: '',
        catFilter: '',
        modalOpen: false,
        deleteModal: false,
        editId: null,
        deleteTarget: { id: null, name: '' },
        form: { name: '', category: '', base_price: '' },
        hasServices: {{ $total > 0 ? 'true' : 'false' }},
        visibleCount: {{ $total }},
        submitting: false,

        init() {
            @if($errors->any())
                this.modalOpen = true;
            @endif
            this.$watch('search',    () => this.updateCount());
            this.$watch('catFilter', () => this.updateCount());
            this.$watch('modalOpen', v => {
                if (v) this.$nextTick(() => this.$refs.nameInput?.focus());
                else   this.submitting = false;
            });
        },

        matchesService(service) {
            const q      = this.search.toLowerCase().trim();
            const nameOk = !q || service.name.toLowerCase().includes(q) || service.category.toLowerCase().includes(q);
            const catOk  = !this.catFilter || service.category === this.catFilter;
            return nameOk && catOk;
        },

        updateCount() {
            this.$nextTick(() => {
                this.visibleCount = document.querySelectorAll('[x-show][style]:not([style*="display: none"])').length;
            });
        },

        openModal() {
            this.editId = null;
            this.form   = { name: '', category: '', base_price: '' };
            this.modalOpen = true;
        },

        openEdit(service) {
            this.editId        = service.id;
            this.form.name     = service.name;
            this.form.category = service.category;
            this.form.base_price = service.base_price;
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
            this.editId    = null;
        },

        confirmDelete(id, name) {
            this.deleteTarget = { id, name };
            this.deleteModal  = true;
        },
    };
}
</script>
@endpush

@endsection
