@extends('layouts.portal')
@section('title', 'Mensagens')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 py-8">

<div class="mb-5">
    <h1 class="text-[22px] font-bold text-slate-900">Mensagens</h1>
    <p class="mt-0.5 text-[13px] text-slate-500">Comunicação direta sobre suas ordens de serviço.</p>
</div>

<div class="grid grid-cols-1 gap-5 lg:grid-cols-[340px_1fr]">

    {{-- Thread list --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 px-4 py-3.5">
            <h2 class="text-[13.5px] font-bold text-slate-900">Conversas</h2>
        </div>

        @if(isset($mensagens) && $mensagens->count() > 0)
            <ul class="divide-y divide-slate-100">
                @foreach($mensagens as $msg)
                @php
                    $isActive = request('ordem') == ($msg->ordem_id ?? null);
                @endphp
                <li>
                    <a
                        href="{{ request()->fullUrlWithQuery(['ordem' => $msg->ordem_id ?? '']) }}"
                        class="flex items-start gap-3 px-4 py-3.5 transition-colors {{ $isActive ? 'bg-blue-50' : 'hover:bg-slate-50' }}"
                    >
                        <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-[11px] font-bold text-white">
                            OS
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-[13px] font-semibold {{ $isActive ? 'text-blue-700' : 'text-slate-900' }}">
                                    OS #{{ $msg->ordem->numero ?? '000000' }}
                                </p>
                                <time class="shrink-0 text-[11px] text-slate-400">
                                    {{ $msg->created_at?->diffForHumans() ?? '' }}
                                </time>
                            </div>
                            <p class="mt-0.5 truncate text-[12.5px] text-slate-500">
                                {{ Str::limit($msg->conteudo ?? 'Sem mensagens ainda.', 60) }}
                            </p>
                            @if(($msg->nao_lidas ?? 0) > 0)
                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-[10px] font-bold text-white">
                                    {{ $msg->nao_lidas }}
                                </span>
                            @endif
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        @else
            <div class="py-12 text-center">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                    <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <p class="text-[13px] font-semibold text-slate-700">Nenhuma conversa</p>
                <p class="text-[12px] text-slate-500">As mensagens das suas OS aparecem aqui.</p>
            </div>
        @endif
    </div>

    {{-- Message thread --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        @if(request('ordem') && isset($thread) && count($thread) > 0)
            {{-- Thread header --}}
            <div class="border-b border-slate-100 px-5 py-3.5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-[13.5px] font-bold text-slate-900">OS #{{ $ordemAtiva->numero ?? '' }}</h3>
                        <p class="text-[12px] text-slate-500">{{ $ordemAtiva->equipamento->modelo ?? '' }}</p>
                    </div>
                    <x-ui.badge :variant="'primary'" :dot="true">
                        {{ \App\Models\Ordem::STATUS[$ordemAtiva->status]['label'] ?? 'Em andamento' }}
                    </x-ui.badge>
                </div>
            </div>

            {{-- Messages --}}
            <div class="max-h-[420px] overflow-y-auto space-y-4 p-5">
                @foreach($thread as $msg)
                @php $isMe = $msg->tipo === 'cliente'; @endphp
                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} gap-3">
                    @if(!$isMe)
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-200 text-[10px] font-bold text-slate-600">
                            FD
                        </div>
                    @endif
                    <div class="max-w-[75%]">
                        <div class="rounded-2xl px-4 py-2.5 {{ $isMe ? 'rounded-tr-sm bg-blue-600 text-white' : 'rounded-tl-sm bg-slate-100 text-slate-800' }}">
                            <p class="text-[13px] leading-relaxed">{{ $msg->conteudo }}</p>
                        </div>
                        <p class="mt-1 text-[10.5px] {{ $isMe ? 'text-right text-slate-400' : 'text-slate-400' }}">
                            {{ $msg->created_at?->format('d/m/Y \à\s H:i') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Reply form --}}
            <div class="border-t border-slate-100 p-4">
                <form action="{{ route('portal.mensagens.store') }}" method="POST" class="flex items-end gap-3">
                    @csrf
                    <input type="hidden" name="ordem_id" value="{{ request('ordem') }}">
                    <div class="flex-1">
                        <textarea
                            name="conteudo"
                            rows="2"
                            placeholder="Escreva sua mensagem..."
                            class="w-full resize-none rounded-xl border border-slate-200 px-4 py-2.5 text-[13px] text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        ></textarea>
                    </div>
                    <button type="submit" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm transition hover:bg-blue-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>

        @else
            {{-- Empty state --}}
            <div class="flex h-full flex-col items-center justify-center py-20">
                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50">
                    <svg class="h-6 w-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <p class="text-[14px] font-bold text-slate-700">Selecione uma conversa</p>
                <p class="mt-1 text-[13px] text-slate-500">Escolha uma OS à esquerda para ver as mensagens.</p>
            </div>
        @endif
    </div>

</div>

</div>
@endsection
