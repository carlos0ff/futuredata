@extends('layouts.app')
@section('title', 'Notificações')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', ['items' => [
        ['label' => 'Início', 'href' => route('app.dashboard')],
        ['label' => 'Notificações'],
    ]])
@endsection

@push('styles')
<style>
@keyframes slide-in {
  from { opacity: 0; transform: translateY(6px); }
  to   { opacity: 1; transform: translateY(0); }
}
.notif-card {
  animation: slide-in 220ms ease both;
  transition: box-shadow 150ms, transform 150ms;
}
.notif-card:hover {
  box-shadow: 0 6px 20px -4px rgba(0,0,0,0.10);
  transform: translateY(-1px);
}
.notif-btn {
  transition: background-color 130ms ease, color 130ms ease,
              box-shadow 130ms ease, transform 130ms ease;
}
.notif-btn:hover  { transform: scale(1.12); }
.notif-btn:active { transform: scale(0.92); }
</style>
@endpush

@section('content')

@php
$tipoCfg = [
    'os_criada' => [
        'label'    => 'Nova OS',
        'icon_bg'  => 'bg-blue-100',
        'icon_col' => 'text-blue-600',
        'dot_ping' => 'bg-blue-400',
        'dot_sol'  => 'bg-blue-500',
        'chip_bg'  => 'bg-blue-50',
        'chip_txt' => 'text-blue-700',
        'chip_brd' => 'border-blue-200',
        'accent'   => 'border-l-blue-400',
        'card_bg'  => 'bg-blue-50/25',
    ],
    'os_status' => [
        'label'    => 'Atualização',
        'icon_bg'  => 'bg-amber-100',
        'icon_col' => 'text-amber-600',
        'dot_ping' => 'bg-amber-400',
        'dot_sol'  => 'bg-amber-500',
        'chip_bg'  => 'bg-amber-50',
        'chip_txt' => 'text-amber-700',
        'chip_brd' => 'border-amber-200',
        'accent'   => 'border-l-amber-400',
        'card_bg'  => 'bg-amber-50/25',
    ],
    'mensagem_portal' => [
        'label'    => 'Mensagem',
        'icon_bg'  => 'bg-emerald-100',
        'icon_col' => 'text-emerald-600',
        'dot_ping' => 'bg-emerald-400',
        'dot_sol'  => 'bg-emerald-500',
        'chip_bg'  => 'bg-emerald-50',
        'chip_txt' => 'text-emerald-700',
        'chip_brd' => 'border-emerald-200',
        'accent'   => 'border-l-emerald-400',
        'card_bg'  => 'bg-emerald-50/20',
    ],
    'aprovado' => [
        'label'    => 'Aprovado',
        'icon_bg'  => 'bg-teal-100',
        'icon_col' => 'text-teal-600',
        'dot_ping' => 'bg-teal-400',
        'dot_sol'  => 'bg-teal-500',
        'chip_bg'  => 'bg-teal-50',
        'chip_txt' => 'text-teal-700',
        'chip_brd' => 'border-teal-200',
        'accent'   => 'border-l-teal-400',
        'card_bg'  => 'bg-teal-50/20',
    ],
    'recusado' => [
        'label'    => 'Recusado',
        'icon_bg'  => 'bg-red-100',
        'icon_col' => 'text-red-600',
        'dot_ping' => 'bg-red-400',
        'dot_sol'  => 'bg-red-500',
        'chip_bg'  => 'bg-red-50',
        'chip_txt' => 'text-red-700',
        'chip_brd' => 'border-red-200',
        'accent'   => 'border-l-red-400',
        'card_bg'  => 'bg-red-50/20',
    ],
    'whatsapp' => [
        'label'    => 'WhatsApp',
        'icon_bg'  => 'bg-green-100',
        'icon_col' => 'text-green-600',
        'dot_ping' => 'bg-green-400',
        'dot_sol'  => 'bg-green-500',
        'chip_bg'  => 'bg-green-50',
        'chip_txt' => 'text-green-700',
        'chip_brd' => 'border-green-200',
        'accent'   => 'border-l-green-400',
        'card_bg'  => 'bg-green-50/20',
    ],
    'outro' => [
        'label'    => 'Sistema',
        'icon_bg'  => 'bg-slate-100',
        'icon_col' => 'text-slate-500',
        'dot_ping' => 'bg-slate-300',
        'dot_sol'  => 'bg-slate-400',
        'chip_bg'  => 'bg-slate-50',
        'chip_txt' => 'text-slate-600',
        'chip_brd' => 'border-slate-200',
        'accent'   => 'border-l-slate-300',
        'card_bg'  => 'bg-white',
    ],
];

$total    = $notificacoes->total();
$lidas    = $total - $totalNaoLidas;

/* Agrupa a página atual por data */
$grupos = [];
foreach ($notificacoes as $n) {
    $key = match(true) {
        $n->created_at->isToday()       => 'Hoje',
        $n->created_at->isYesterday()   => 'Ontem',
        $n->created_at->isCurrentWeek() => 'Esta semana',
        default => $n->created_at->translatedFormat('d \d\e F'),
    };
    $grupos[$key][] = $n;
}

$dataFiltro  = request()->get('data', '');
$filtroAtivo = ($periodo !== 'tudo') || $dataFiltro !== '';
@endphp

{{-- ── Header ── --}}
<div class="mb-7">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-slate-900 shadow-lg">
                <svg class="h-[22px] w-[22px] text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                @if($totalNaoLidas > 0)
                <span class="absolute -right-1.5 -top-1.5 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-blue-500 px-1 text-[10px] font-black text-white ring-2 ring-[#f0f2f6]">
                    {{ $totalNaoLidas > 99 ? '99+' : $totalNaoLidas }}
                </span>
                @endif
            </div>
            <div>
                <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Notificações</h1>
                <p class="text-[12.5px] text-slate-400 mt-0.5">
                    @if($totalNaoLidas > 0)
                        <span class="font-semibold text-blue-600">{{ $totalNaoLidas > 99 ? '99+' : $totalNaoLidas }} não {{ $totalNaoLidas === 1 ? 'lida' : 'lidas' }}</span>
                        <span class="mx-1.5 text-slate-300">·</span>
                    @endif
                    {{ $total }} no total
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if($totalNaoLidas > 0)
            <form action="{{ route('app.notificacoes.read-all') }}" method="POST">
                @csrf @method('PUT')
                <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-[12.5px] font-semibold text-white shadow-sm shadow-emerald-200 transition hover:bg-emerald-600 active:scale-[0.98]">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/>
                    </svg>
                    Marcar todas como lidas
                </button>
            </form>
            @endif

            @if($total > 0)
            <form action="{{ route('app.notificacoes.destroy-all') }}" method="POST"
                  onsubmit="return confirm('Remover todas as notificações permanentemente?')">
                @csrf @method('DELETE')
                <button class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2 text-[12.5px] font-semibold text-red-500 shadow-sm transition hover:bg-red-50 hover:border-red-300 hover:text-red-600 active:scale-[0.98]">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    </svg>
                    Limpar tudo
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- ── Filtros ── --}}
    <div class="mt-5 flex items-center gap-2 overflow-x-auto pb-0.5">
        @foreach(['todas' => 'Todas', 'nao_lidas' => 'Não lidas', 'lidas' => 'Lidas'] as $val => $label)
        @php $active = $filtro === $val; @endphp
        <a href="{{ request()->fullUrlWithQuery(['filtro' => $val, 'page' => 1]) }}"
           class="inline-flex shrink-0 items-center gap-2 rounded-full px-4 py-1.5 text-[13px] font-semibold transition-all
               {{ $active ? 'bg-slate-900 text-white shadow-sm' : 'border border-slate-200 bg-white text-slate-500 hover:border-slate-300 hover:text-slate-700' }}">
            {{ $label }}
            <span class="flex h-[18px] min-w-[18px] items-center justify-center rounded-full px-1 text-[10px] font-black
                {{ $active ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                @if($val === 'todas') {{ $total > 99 ? '99+' : $total }}
                @elseif($val === 'nao_lidas') {{ $totalNaoLidas > 99 ? '99+' : $totalNaoLidas }}
                @else {{ $lidas }}
                @endif
            </span>
        </a>
        @endforeach

        <div class="mx-1 h-5 w-px shrink-0 bg-slate-200"></div>

        <form method="GET" action="{{ route('app.notificacoes.index') }}" class="ml-auto flex shrink-0 items-center gap-2">
            <input type="hidden" name="filtro" value="{{ $filtro }}">
            <input type="hidden" name="page" value="1">

            <input type="date" name="data" value="{{ $dataFiltro }}" max="{{ now()->toDateString() }}" onchange="this.form.submit()"
                   title="Filtrar por data"
                   class="h-8 rounded-xl border px-2.5 text-[12px] font-medium outline-none transition
                       {{ $dataFiltro ? 'border-violet-300 bg-violet-50 text-violet-700' : 'border-slate-200 bg-white text-slate-500 hover:border-slate-300' }}
                       focus:border-violet-400 focus:ring-2 focus:ring-violet-100">

            <div class="relative">
                <select name="periodo" onchange="this.form.submit()"
                        class="h-8 appearance-none cursor-pointer rounded-xl border py-0 pl-3 pr-7 text-[12px] font-semibold outline-none transition
                            {{ $periodo !== 'tudo' ? 'border-violet-300 bg-violet-50 text-violet-700' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300' }}
                            focus:border-violet-400 focus:ring-2 focus:ring-violet-100">
                    <option value="tudo"   {{ $periodo === 'tudo'   ? 'selected' : '' }}>Período</option>
                    <option value="hoje"   {{ $periodo === 'hoje'   ? 'selected' : '' }}>Hoje</option>
                    <option value="ontem"  {{ $periodo === 'ontem'  ? 'selected' : '' }}>Ontem</option>
                    <option value="semana" {{ $periodo === 'semana' ? 'selected' : '' }}>7 dias</option>
                    <option value="mes"    {{ $periodo === 'mes'    ? 'selected' : '' }}>30 dias</option>
                </select>
                <svg class="pointer-events-none absolute right-2 top-1/2 h-3 w-3 -translate-y-1/2 {{ $periodo !== 'tudo' ? 'text-violet-500' : 'text-slate-400' }}"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            @if($filtroAtivo)
            <a href="{{ route('app.notificacoes.index', ['filtro' => $filtro]) }}"
               title="Limpar filtros"
               class="flex h-8 w-8 items-center justify-center rounded-xl border border-red-200 bg-white text-red-400 transition hover:bg-red-50 hover:text-red-500">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </a>
            @endif
        </form>
    </div>
</div>

{{-- ── Lista ── --}}
@if($notificacoes->isEmpty())
<div class="flex flex-col items-center justify-center rounded-3xl bg-white py-28 text-center shadow-sm ring-1 ring-black/[0.06]">
    <div class="mb-5 flex h-20 w-20 items-center justify-center rounded-3xl bg-slate-100">
        <svg class="h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
    </div>
    <p class="text-[16px] font-bold text-slate-700">
        @if($filtro === 'nao_lidas') Tudo em dia!
        @elseif($filtro === 'lidas') Nenhuma lida ainda.
        @else Sem notificações.
        @endif
    </p>
    <p class="mt-1.5 max-w-xs text-[13.5px] leading-relaxed text-slate-400">
        @if($filtro === 'nao_lidas') Você não tem notificações pendentes.
        @elseif($filtro === 'lidas') As notificações que você ler aparecerão aqui.
        @else Quando houver atividade no sistema, você verá aqui.
        @endif
    </p>
    @if($filtro !== 'todas')
    <a href="{{ route('app.notificacoes.index') }}"
       class="mt-5 inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-600 transition hover:bg-slate-50">
        Ver todas as notificações
    </a>
    @endif
</div>

@else

<div class="space-y-6">
@php $rowIndex = 0; @endphp

@foreach($grupos as $grupoLabel => $itens)
<div class="flex items-center gap-3">
    <span class="text-[11.5px] font-bold uppercase tracking-widest text-slate-400">{{ $grupoLabel }}</span>
    <div class="flex-1 h-px bg-slate-200/70"></div>
    <span class="text-[11px] font-semibold text-slate-400">{{ count($itens) }} {{ count($itens) === 1 ? 'item' : 'itens' }}</span>
</div>

<div class="space-y-2.5">
@foreach($itens as $notificacao)
@php
    $data  = $notificacao->data;
    $lida  = ! is_null($notificacao->read_at);
    $tipo  = $data['tipo'] ?? 'outro';
    $cfg   = $tipoCfg[$tipo] ?? $tipoCfg['outro'];
    $delay = $rowIndex * 30;
    $rowIndex++;
@endphp

<div class="notif-card group relative overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]
    {{ ! $lida ? 'border-l-[3px] '.$cfg['accent'].' !rounded-l-none' : '' }}"
    style="animation-delay: {{ $delay }}ms">

    @if(! $lida)
    <div class="absolute inset-0 pointer-events-none {{ $cfg['card_bg'] }}"></div>
    @endif

    <div class="relative flex items-start gap-4 px-5 py-4">

        {{-- Ícone --}}
        <div class="relative mt-0.5 shrink-0">
            @if(! $lida)
            <span class="absolute -top-1 -right-1 z-10 flex h-3 w-3">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-60 {{ $cfg['dot_ping'] }}"></span>
                <span class="relative inline-flex h-3 w-3 rounded-full {{ $cfg['dot_sol'] }} ring-2 ring-white"></span>
            </span>
            @endif
            <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $cfg['icon_bg'] }}">
                @if($tipo === 'os_criada')
                <svg class="h-5 w-5 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v4M10 13h4"/>
                </svg>
                @elseif($tipo === 'os_status')
                <svg class="h-5 w-5 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 0 0 4.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 0 1-15.357-2m15.357 2H15"/>
                </svg>
                @elseif($tipo === 'mensagem_portal')
                <svg class="h-5 w-5 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                @elseif($tipo === 'aprovado')
                <svg class="h-5 w-5 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                </svg>
                @elseif($tipo === 'recusado')
                <svg class="h-5 w-5 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9l-6 6M9 9l6 6"/>
                </svg>
                @elseif($tipo === 'whatsapp')
                <svg class="h-5 w-5 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12.05 2C6.495 2 2 6.495 2 12.05c0 1.885.518 3.654 1.421 5.173L2 22l4.878-1.407A9.996 9.996 0 0 0 12.05 22C17.605 22 22 17.505 22 11.95 22 6.495 17.505 2 12.05 2zm0 18.1a8.073 8.073 0 0 1-4.116-1.124l-.295-.175-3.057.881.848-3.092-.192-.317A8.1 8.1 0 1 1 12.05 20.1z"/>
                </svg>
                @else
                <svg class="h-5 w-5 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                </svg>
                @endif
            </div>
        </div>

        {{-- Conteúdo --}}
        <div class="min-w-0 flex-1">
            <div class="mb-1.5 flex flex-wrap items-center gap-1.5">
                <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-[10.5px] font-bold uppercase tracking-wide
                    {{ $cfg['chip_bg'] }} {{ $cfg['chip_txt'] }} {{ $cfg['chip_brd'] }}">
                    {{ $cfg['label'] }}
                </span>
                @if(! empty($data['numero']))
                <a href="{{ $data['url'] ?? '#' }}"
                   class="inline-flex items-center gap-1 rounded-md border border-slate-200 bg-white px-2 py-0.5 font-mono text-[10.5px] font-bold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900">
                    <svg class="h-2.5 w-2.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                        <rect x="9" y="3" width="6" height="4" rx="1"/>
                    </svg>
                    {{ $data['numero'] }}
                </a>
                @endif
                @if($lida)
                <span class="inline-flex items-center gap-1 text-[10.5px] text-slate-400">
                    <svg class="h-3 w-3 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/>
                    </svg>
                    Lida
                </span>
                @endif
            </div>

            <p class="text-[14px] font-semibold leading-snug text-slate-900">
                {{ $data['titulo'] ?? 'Notificação' }}
            </p>

            @if(! empty($data['mensagem']))
            <p class="mt-0.5 text-[12.5px] leading-relaxed text-slate-400">
                {{ $data['mensagem'] }}
            </p>
            @endif

            <time class="mt-1.5 block text-[11.5px] tabular-nums text-slate-400"
                  title="{{ $notificacao->created_at->format('d/m/Y H:i') }}">
                {{ $notificacao->created_at->diffForHumans() }}
            </time>
        </div>

        {{-- Ações --}}
        <div class="shrink-0 self-center ml-4
                    flex items-center gap-0.5 rounded-[14px] bg-white px-1 py-1
                    shadow-md shadow-black/[0.08] ring-1 ring-black/[0.06]
                    opacity-0 translate-x-1
                    group-hover:opacity-100 group-hover:translate-x-0
                    transition-all duration-150 ease-out">

            <a href="{{ route('app.notificacoes.open', $notificacao->id) }}"
               class="notif-btn flex h-7 w-7 items-center justify-center rounded-[10px]
                      text-slate-400 hover:bg-slate-900 hover:text-white hover:shadow-sm hover:shadow-slate-500/30"
               title="Abrir">
                <svg class="h-[14px] w-[14px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </a>

            @if(! $lida)
            <a href="{{ route('app.notificacoes.open', $notificacao->id) }}"
               class="notif-btn flex h-7 w-7 items-center justify-center rounded-[10px]
                      text-emerald-500 hover:bg-emerald-500 hover:text-white hover:shadow-sm hover:shadow-emerald-500/30"
               title="Marcar como lida">
                <svg class="h-[14px] w-[14px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/>
                </svg>
            </a>
            @endif

            <span class="h-4 w-px bg-slate-100 mx-0.5"></span>

            <form action="{{ route('app.notificacoes.destroy', $notificacao->id) }}" method="POST"
                  onsubmit="return confirm('Remover esta notificação?')">
                @csrf @method('DELETE')
                <button class="notif-btn flex h-7 w-7 items-center justify-center rounded-[10px]
                               text-slate-300 hover:bg-red-500 hover:text-white hover:shadow-sm hover:shadow-red-500/30"
                        title="Remover">
                    <svg class="h-[14px] w-[14px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

@endforeach
</div>

@endforeach
</div>

{{-- Paginação --}}
@if($notificacoes->hasPages())
<div class="mt-6 flex items-center justify-between rounded-2xl bg-white px-5 py-3.5 shadow-sm ring-1 ring-black/[0.06]">
    <p class="text-[12.5px] text-slate-400">
        Exibindo <span class="font-semibold text-slate-700">{{ $notificacoes->firstItem() }}–{{ $notificacoes->lastItem() }}</span>
        de <span class="font-semibold text-slate-700">{{ $total }}</span>
    </p>
    <div class="[&_.pagination]:flex [&_.pagination]:items-center [&_.pagination]:gap-1
                [&_a]:inline-flex [&_a]:h-8 [&_a]:min-w-[2rem] [&_a]:items-center [&_a]:justify-center [&_a]:rounded-lg [&_a]:border [&_a]:border-slate-200 [&_a]:bg-white [&_a]:px-2 [&_a]:text-[12.5px] [&_a]:font-semibold [&_a]:text-slate-600 [&_a]:transition [&_a]:hover:bg-slate-50
                [&_span.page-link]:inline-flex [&_span.page-link]:h-8 [&_span.page-link]:min-w-[2rem] [&_span.page-link]:items-center [&_span.page-link]:justify-center [&_span.page-link]:rounded-lg [&_span.page-link]:px-2 [&_span.page-link]:text-[12.5px] [&_span.page-link]:font-bold [&_span.page-link]:bg-slate-900 [&_span.page-link]:text-white
                [&_.disabled]:opacity-40 [&_.disabled_a]:pointer-events-none">
        {{ $notificacoes->withQueryString()->links() }}
    </div>
</div>
@endif

@endif

@endsection
