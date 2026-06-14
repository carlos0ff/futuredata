@extends('layouts.app')
@section('title', 'Notificação')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', ['items' => [
        ['label' => 'Início',        'href' => route('app.dashboard')],
        ['label' => 'Notificações',  'href' => route('app.notificacoes.index')],
        ['label' => 'Detalhe'],
    ]])
@endsection

@push('styles')
<style>
@keyframes slide-up {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}
.notif-detail { animation: slide-up 220ms ease both; }
</style>
@endpush

@section('content')

@php
$data = $notificacao->data;
$lida = !is_null($notificacao->read_at);
$tipo = $data['tipo'] ?? 'outro';

$tipoCfg = [
    'os_criada'       => ['label'=>'Nova OS',         'icon_bg'=>'bg-blue-100',    'icon_col'=>'text-blue-600',    'chip_bg'=>'bg-blue-50',    'chip_txt'=>'text-blue-700',    'chip_brd'=>'border-blue-200',    'dot_ping'=>'bg-blue-400',    'dot_sol'=>'bg-blue-500',    'accent'=>'bg-blue-500',    'card_top'=>'bg-blue-50/50'],
    'os_status'       => ['label'=>'Atualização',     'icon_bg'=>'bg-amber-100',   'icon_col'=>'text-amber-600',   'chip_bg'=>'bg-amber-50',   'chip_txt'=>'text-amber-700',   'chip_brd'=>'border-amber-200',   'dot_ping'=>'bg-amber-400',   'dot_sol'=>'bg-amber-500',   'accent'=>'bg-amber-500',   'card_top'=>'bg-amber-50/50'],
    'mensagem_portal' => ['label'=>'Mensagem',        'icon_bg'=>'bg-emerald-100', 'icon_col'=>'text-emerald-600', 'chip_bg'=>'bg-emerald-50', 'chip_txt'=>'text-emerald-700', 'chip_brd'=>'border-emerald-200', 'dot_ping'=>'bg-emerald-400', 'dot_sol'=>'bg-emerald-500', 'accent'=>'bg-emerald-500', 'card_top'=>'bg-emerald-50/50'],
    'aprovado'        => ['label'=>'Aprovado',        'icon_bg'=>'bg-teal-100',    'icon_col'=>'text-teal-600',    'chip_bg'=>'bg-teal-50',    'chip_txt'=>'text-teal-700',    'chip_brd'=>'border-teal-200',    'dot_ping'=>'bg-teal-400',    'dot_sol'=>'bg-teal-500',    'accent'=>'bg-teal-500',    'card_top'=>'bg-teal-50/50'],
    'recusado'        => ['label'=>'Recusado',        'icon_bg'=>'bg-red-100',     'icon_col'=>'text-red-600',     'chip_bg'=>'bg-red-50',     'chip_txt'=>'text-red-700',     'chip_brd'=>'border-red-200',     'dot_ping'=>'bg-red-400',     'dot_sol'=>'bg-red-500',     'accent'=>'bg-red-500',     'card_top'=>'bg-red-50/40'],
    'whatsapp'        => ['label'=>'WhatsApp',        'icon_bg'=>'bg-green-100',   'icon_col'=>'text-green-600',   'chip_bg'=>'bg-green-50',   'chip_txt'=>'text-green-700',   'chip_brd'=>'border-green-200',   'dot_ping'=>'bg-green-400',   'dot_sol'=>'bg-green-500',   'accent'=>'bg-green-500',   'card_top'=>'bg-green-50/50'],
    'app_update'      => ['label'=>'Atualização App', 'icon_bg'=>'bg-violet-100',  'icon_col'=>'text-violet-600',  'chip_bg'=>'bg-violet-50',  'chip_txt'=>'text-violet-700',  'chip_brd'=>'border-violet-200',  'dot_ping'=>'bg-violet-400',  'dot_sol'=>'bg-violet-500',  'accent'=>'bg-violet-500',  'card_top'=>'bg-violet-50/50'],
    'outro'           => ['label'=>'Sistema',         'icon_bg'=>'bg-slate-100',   'icon_col'=>'text-slate-500',   'chip_bg'=>'bg-slate-50',   'chip_txt'=>'text-slate-600',   'chip_brd'=>'border-slate-200',   'dot_ping'=>'bg-slate-300',   'dot_sol'=>'bg-slate-400',   'accent'=>'bg-slate-300',   'card_top'=>'bg-slate-50/30'],
];
$cfg = $tipoCfg[$tipo] ?? $tipoCfg['outro'];
@endphp

<div class="mx-auto max-w-2xl notif-detail">

    {{-- ── Voltar ── --}}
    <a href="{{ route('app.notificacoes.index') }}"
       class="mb-5 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2
              text-[12.5px] font-semibold text-slate-500 shadow-sm transition
              hover:border-slate-300 hover:text-slate-900 hover:shadow active:scale-[0.97]">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Notificações
    </a>

    {{-- ── Card ── --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/[0.06]">

        {{-- Faixa colorida superior --}}
        <div class="h-1 w-full {{ $cfg['accent'] }}"></div>

        {{-- ── Header ── --}}
        <div class="{{ $cfg['card_top'] }} px-6 py-6">
            <div class="flex items-start gap-4">

                {{-- Ícone --}}
                <div class="relative shrink-0">
                    @if(!$lida)
                    <span class="absolute -top-1 -right-1 z-10 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-70 {{ $cfg['dot_ping'] }}"></span>
                        <span class="relative inline-flex h-3 w-3 rounded-full ring-2 ring-white {{ $cfg['dot_sol'] }}"></span>
                    </span>
                    @endif
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl shadow-sm {{ $cfg['icon_bg'] }}">
                        @if($tipo === 'os_criada')
                        <svg class="h-7 w-7 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                            <rect x="9" y="3" width="6" height="4" rx="1"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v4M10 13h4"/>
                        </svg>
                        @elseif($tipo === 'os_status')
                        <svg class="h-7 w-7 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 0 0 4.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 0 1-15.357-2m15.357 2H15"/>
                        </svg>
                        @elseif($tipo === 'mensagem_portal')
                        <svg class="h-7 w-7 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        @elseif($tipo === 'aprovado')
                        <svg class="h-7 w-7 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                        @elseif($tipo === 'recusado')
                        <svg class="h-7 w-7 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="10"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9l-6 6M9 9l6 6"/>
                        </svg>
                        @elseif($tipo === 'whatsapp')
                        <svg class="h-7 w-7 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            <path d="M12.05 2C6.495 2 2 6.495 2 12.05c0 1.885.518 3.654 1.421 5.173L2 22l4.878-1.407A9.996 9.996 0 0 0 12.05 22C17.605 22 22 17.505 22 11.95 22 6.495 17.505 2 12.05 2zm0 18.1a8.073 8.073 0 0 1-4.116-1.124l-.295-.175-3.057.881.848-3.092-.192-.317A8.1 8.1 0 1 1 12.05 20.1z"/>
                        </svg>
                        @elseif($tipo === 'app_update')
                        <svg class="h-7 w-7 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-1m-4-8-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        @else
                        <svg class="h-7 w-7 {{ $cfg['icon_col'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                        @endif
                    </div>
                </div>

                {{-- Meta --}}
                <div class="min-w-0 flex-1">
                    {{-- Chips --}}
                    <div class="mb-2 flex flex-wrap items-center gap-1.5">
                        <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-[10.5px] font-bold uppercase tracking-wide
                            {{ $cfg['chip_bg'] }} {{ $cfg['chip_txt'] }} {{ $cfg['chip_brd'] }}">
                            {{ $cfg['label'] }}
                        </span>
                        @if(!empty($data['numero']))
                        <span class="inline-flex items-center gap-1 rounded-md border border-slate-200 bg-white px-2 py-0.5 font-mono text-[10.5px] font-bold text-slate-600">
                            <svg class="h-3 w-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                <rect x="9" y="3" width="6" height="4" rx="1"/>
                            </svg>
                            {{ $data['numero'] }}
                        </span>
                        @endif
                        @if($lida)
                        <span class="inline-flex items-center gap-1 rounded-md border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10.5px] font-semibold text-emerald-700">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/>
                            </svg>
                            Lida
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 rounded-md border border-blue-200 bg-blue-50 px-2 py-0.5 text-[10.5px] font-semibold text-blue-600">
                            <span class="inline-block h-1.5 w-1.5 animate-pulse rounded-full bg-blue-500"></span>
                            Não lida
                        </span>
                        @endif
                    </div>

                    {{-- Título --}}
                    <h1 class="text-[17px] font-extrabold leading-snug text-slate-900">
                        {{ $data['titulo'] ?? 'Notificação' }}
                    </h1>

                    {{-- Timestamp --}}
                    <div class="mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-0.5 text-[12px] text-slate-400">
                        <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <time title="{{ $notificacao->created_at->format('d/m/Y H:i') }}">
                            {{ $notificacao->created_at->format('d/m/Y') }} às {{ $notificacao->created_at->format('H:i') }}
                        </time>
                        <span class="text-slate-200">·</span>
                        <span>{{ $notificacao->created_at->diffForHumans() }}</span>
                        @if($lida && $notificacao->read_at)
                        <span class="text-slate-200">·</span>
                        <span class="flex items-center gap-1 text-emerald-500">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/>
                            </svg>
                            Lida {{ $notificacao->read_at->diffForHumans() }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Botões de ação ── --}}
            <div class="mt-5 flex flex-wrap items-center gap-2 border-t border-black/[0.05] pt-4">
                @if(!$lida && !$isDemo)
                <form action="{{ route('app.notificacoes.open', $notificacao->id) }}" method="GET">
                    <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2
                                   text-[12.5px] font-semibold text-white shadow-sm shadow-emerald-200
                                   transition hover:bg-emerald-600 active:scale-[0.97]">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/>
                        </svg>
                        Marcar como lida
                    </button>
                </form>
                @endif

                @if(!empty($data['url']) && $data['url'] !== '#')
                <a href="{{ $data['url'] }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2
                          text-[12.5px] font-semibold text-slate-700 shadow-sm
                          transition hover:border-slate-300 hover:bg-slate-50 active:scale-[0.97]">
                    <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                    Ver OS
                </a>
                @endif

                @if(!$isDemo)
                <form action="{{ route('app.notificacoes.destroy', $notificacao->id) }}" method="POST"
                      class="ml-auto"
                      onsubmit="return confirm('Remover esta notificação permanentemente?')">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2
                                   text-[12.5px] font-semibold text-red-500 shadow-sm
                                   transition hover:bg-red-50 hover:border-red-300 active:scale-[0.97]">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        </svg>
                        Remover
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- ── Mensagem ── --}}
        @if(!empty($data['mensagem']))
        <div class="border-t border-slate-100 px-6 py-5">
            <p class="mb-2.5 text-[11px] font-bold uppercase tracking-widest text-slate-400">Mensagem</p>
            <p class="text-[14px] leading-relaxed text-slate-700">{{ $data['mensagem'] }}</p>
        </div>
        @endif

        {{-- ── Card OS ── --}}
        @if(!empty($data['numero']))
        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-5">
            <p class="mb-3 text-[11px] font-bold uppercase tracking-widest text-slate-400">Ordem de Serviço</p>
            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3.5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900">
                        <svg class="h-[18px] w-[18px] text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                            <rect x="9" y="3" width="6" height="4" rx="1"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-mono text-[13.5px] font-bold text-slate-900">{{ $data['numero'] }}</p>
                        <p class="text-[11.5px] text-slate-400">Ordem de Serviço</p>
                    </div>
                </div>
                @if(!empty($data['url']) && $data['url'] !== '#')
                <a href="{{ $data['url'] }}"
                   class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-4 py-2
                          text-[12px] font-semibold text-white shadow-sm
                          transition hover:bg-slate-700 active:scale-[0.97]">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                    Ver OS
                </a>
                @else
                <span class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-[12px] font-semibold text-slate-400">
                    Demo
                </span>
                @endif
            </div>
        </div>
        @endif

    </div>

</div>

@endsection
