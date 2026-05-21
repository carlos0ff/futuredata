@extends('layouts.app')

@section('breadcrumbs')
    <a href="{{ route('app.dashboard') }}" class="transition hover:text-slate-700">Início</a>
    <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
    <span class="font-medium text-slate-900">Notificações</span>
@endsection

@section('content')
<div class="mx-auto max-w-3xl space-y-5">

    {{-- Cabeçalho --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-[18px] font-semibold text-slate-900">Notificações</h1>
            @if($totalNaoLidas > 0)
                <p class="text-[13px] text-slate-500 mt-0.5">{{ $totalNaoLidas }} {{ $totalNaoLidas === 1 ? 'não lida' : 'não lidas' }}</p>
            @endif
        </div>
        <div class="flex items-center gap-2">
            @if($totalNaoLidas > 0)
                <form action="{{ route('app.notificacoes.read-all') }}" method="POST">
                    @csrf @method('PUT')
                    <button type="submit" class="flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[13px] font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 13 4 4L19 7"/></svg>
                        Marcar todas como lidas
                    </button>
                </form>
            @endif
            @if($notificacoes->total() > 0)
                <form action="{{ route('app.notificacoes.destroy-all') }}" method="POST" onsubmit="return confirm('Remover todas as notificações?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-[13px] font-medium text-red-500 transition hover:bg-red-50">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6l-1 14H6L5 6M10 11v6M14 11v6M9 6V4h6v2"/></svg>
                        Limpar tudo
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Filtros --}}
    <div class="flex items-center gap-1 rounded-xl border border-slate-200 bg-white p-1 shadow-sm w-fit">
        @foreach(['todas' => 'Todas', 'nao_lidas' => 'Não lidas', 'lidas' => 'Lidas'] as $valor => $label)
            <a
                href="{{ route('app.notificacoes.index', ['filtro' => $valor]) }}"
                class="rounded-lg px-4 py-1.5 text-[13px] font-medium transition
                    {{ $filtro === $valor
                        ? 'bg-blue-600 text-white shadow-sm'
                        : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}"
            >
                {{ $label }}
                @if($valor === 'nao_lidas' && $totalNaoLidas > 0)
                    <span class="ml-1 rounded-full bg-blue-500/20 px-1.5 text-[11px] font-semibold text-blue-700">{{ $totalNaoLidas }}</span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Lista de notificações --}}
    @if($notificacoes->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-white py-20">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 mb-4">
                <svg class="h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
            </div>
            <p class="text-[15px] font-medium text-slate-700">Nenhuma notificação</p>
            <p class="mt-1 text-[13px] text-slate-400">
                @if($filtro === 'nao_lidas') Você está em dia com tudo.
                @elseif($filtro === 'lidas') Nenhuma notificação lida ainda.
                @else Nenhuma notificação por aqui.
                @endif
            </p>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <ul class="divide-y divide-slate-100">
                @foreach($notificacoes as $notificacao)
                    @php
                        $data    = $notificacao->data;
                        $lida    = !is_null($notificacao->read_at);
                        $tipo    = $data['tipo'] ?? 'outro';
                        $url     = $data['url'] ?? '#';
                    @endphp
                    <li class="group relative flex items-start gap-4 px-5 py-4 transition {{ $lida ? '' : 'bg-blue-50/40' }}">

                        {{-- Ícone --}}
                        <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                            {{ $tipo === 'os_criada'       ? 'bg-blue-100 text-blue-600' : '' }}
                            {{ $tipo === 'os_status'       ? 'bg-amber-100 text-amber-600' : '' }}
                            {{ $tipo === 'mensagem_portal' ? 'bg-green-100 text-green-600' : '' }}
                            {{ !in_array($tipo, ['os_criada','os_status','mensagem_portal']) ? 'bg-slate-100 text-slate-500' : '' }}
                        ">
                            @if($tipo === 'os_criada')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>
                            @elseif($tipo === 'os_status')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                            @elseif($tipo === 'mensagem_portal')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            @else
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            @endif
                        </div>

                        {{-- Conteúdo --}}
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-[13.5px] font-semibold text-slate-900 leading-snug">
                                    {{ $data['titulo'] ?? 'Notificação' }}
                                    @if(!$lida)
                                        <span class="ml-1.5 inline-block h-1.5 w-1.5 rounded-full bg-blue-500 align-middle"></span>
                                    @endif
                                </p>
                                <span class="shrink-0 text-[11px] text-slate-400 mt-0.5">{{ $notificacao->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-0.5 text-[13px] text-slate-500 leading-relaxed">{{ $data['mensagem'] ?? '' }}</p>

                            {{-- Ações --}}
                            <div class="mt-2 flex items-center gap-3">
                                <a href="{{ route('app.notificacoes.open', $notificacao->id) }}" class="text-[12px] font-medium text-blue-600 hover:text-blue-700">
                                    Ver detalhes →
                                </a>
                                @if(!$lida)
                                    <form action="{{ route('app.notificacoes.open', $notificacao->id) }}" method="GET">
                                        <span class="text-slate-300 select-none">·</span>
                                    </form>
                                    <a href="{{ route('app.notificacoes.open', $notificacao->id) }}" class="text-[12px] text-slate-400 hover:text-slate-600">
                                        Marcar como lida
                                    </a>
                                @endif
                                <span class="text-slate-300 select-none">·</span>
                                <form action="{{ route('app.notificacoes.destroy', $notificacao->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[12px] text-slate-400 hover:text-red-500 transition">
                                        Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Paginação --}}
        @if($notificacoes->hasPages())
            <div class="flex justify-center">
                {{ $notificacoes->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
