@extends('layouts.app')
@section('title', 'WhatsApp & Automação')

@section('content')
<div class="mx-auto max-w-6xl space-y-6 px-4 py-8 sm:px-6" x-data="whatsappPage()">

    {{-- ── Header ───────────────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100">
            <svg class="h-6 w-6 text-emerald-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900">WhatsApp & Automação</h1>
            <p class="text-[13px] text-slate-500">Evolution API · Chatbot IA · n8n Workflows</p>
        </div>
        <div class="ml-auto flex items-center gap-3">
            {{-- Status badge --}}
            <div :class="{
                'bg-emerald-100 text-emerald-700': status === 'open',
                'bg-amber-100 text-amber-700': status === 'connecting',
                'bg-red-100 text-red-700': status === 'close' || status === 'offline',
                'bg-slate-100 text-slate-500': status === 'not_configured' || status === 'unknown',
            }" class="inline-flex items-center gap-2 rounded-full px-3.5 py-1.5 text-[12.5px] font-semibold transition-all">
                <span :class="{
                    'bg-emerald-500 animate-pulse': status === 'open',
                    'bg-amber-400 animate-pulse': status === 'connecting',
                    'bg-red-500': status === 'close' || status === 'offline',
                    'bg-slate-400': status === 'not_configured' || status === 'unknown',
                }" class="h-2 w-2 rounded-full"></span>
                <span x-text="{
                    open: 'Conectado',
                    connecting: 'Conectando...',
                    close: 'Desconectado',
                    offline: 'Offline',
                    not_configured: 'Não configurado',
                }[status] ?? 'Verificando...'"></span>
                <span x-show="phone" x-text="'· ' + phone" class="font-normal text-[11.5px] opacity-70"></span>
            </div>
            <button @click="refreshStatus()" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition">
                <svg :class="{'animate-spin': refreshing}" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-[13px] text-emerald-700">
        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ── Stats ─────────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        @foreach([
            ['label' => 'Mensagens hoje',  'value' => $stats['hoje'],      'color' => 'blue',    'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['label' => 'Recebidas hoje',  'value' => $stats['recebidas'], 'color' => 'emerald', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            ['label' => 'Enviadas hoje',   'value' => $stats['enviadas'],  'color' => 'violet',  'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
            ['label' => 'Total histórico', 'value' => $stats['total'],     'color' => 'slate',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ] as $s)
        <div class="rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-center justify-between">
                <p class="text-[11.5px] font-semibold uppercase tracking-wider text-slate-500">{{ $s['label'] }}</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-{{ $s['color'] }}-50">
                    <svg class="h-4 w-4 text-{{ $s['color'] }}-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/></svg>
                </div>
            </div>
            <p class="mt-2 text-2xl font-bold text-slate-900">{{ $s['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ── Grid principal ────────────────────────────────────────────────────── --}}
    <div class="grid gap-6 lg:grid-cols-[1fr_340px]">

        {{-- Coluna esquerda --}}
        <div class="space-y-6">

            {{-- Configuração Evolution API --}}
            <div class="rounded-xl border border-slate-200 bg-white">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="text-[14px] font-bold text-slate-900">Conexão Evolution API</h2>
                        <p class="mt-0.5 text-[12px] text-slate-500">URL, credenciais e webhook da instância.</p>
                    </div>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50">
                        <svg class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                </div>

                <form action="{{ route('app.whatsapp.save') }}" method="POST" class="space-y-4 p-5">
                    @csrf

                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">URL da Evolution API</label>
                        <input type="url" name="url" value="{{ old('url', $config['url']) }}"
                            placeholder="http://2.24.205.178:32774"
                            class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"/>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">API Key</label>
                            <input type="text" name="key" value="{{ old('key', $config['key']) }}"
                                placeholder="Sua API Key"
                                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"/>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nome da Instância</label>
                            <input type="text" name="instance" value="{{ old('instance', $config['instance']) }}"
                                placeholder="carlos0ff.dev"
                                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"/>
                        </div>
                    </div>

                    {{-- Webhook URL --}}
                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-3.5">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-[11.5px] font-semibold text-slate-500 uppercase tracking-wider">URL do Webhook</p>
                                <p class="mt-0.5 truncate font-mono text-[12px] text-slate-700" id="webhook-url">{{ url('/webhook/whatsapp') }}</p>
                            </div>
                            <div class="flex shrink-0 gap-2">
                                <button type="button" @click="copyWebhook()"
                                    class="flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-[12px] font-semibold text-slate-600 hover:bg-slate-50 transition">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                                    <span x-text="copied ? 'Copiado!' : 'Copiar'"></span>
                                </button>
                                <button type="button" @click="registerWebhook()"
                                    :disabled="registering"
                                    class="flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-[12px] font-semibold text-white hover:bg-emerald-700 transition disabled:opacity-50">
                                    <svg class="h-3.5 w-3.5" :class="{'animate-spin': registering}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span x-text="registering ? 'Registrando...' : 'Registrar no Evolution'"></span>
                                </button>
                            </div>
                        </div>
                        <p x-show="webhookMsg" x-text="webhookMsg" :class="webhookOk ? 'text-emerald-600' : 'text-red-500'" class="mt-2 text-[12px] font-medium"></p>
                    </div>

                    <div class="flex items-center justify-end border-t border-slate-100 pt-4">
                        <button type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-2 text-[13px] font-semibold text-white transition hover:bg-blue-700 active:scale-95">
                            Salvar configurações
                        </button>
                    </div>
                </form>
            </div>

            {{-- n8n Integration --}}
            <div class="rounded-xl border border-slate-200 bg-white">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#ea4b71]/10">
                            <svg class="h-5 w-5 text-[#ea4b71]" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1.086 14.214l-3.3-3.3 1.414-1.414 1.886 1.887 4.472-4.472 1.414 1.414-5.886 5.885z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-[14px] font-bold text-slate-900">n8n Automação</h2>
                            <p class="mt-0.5 text-[12px] text-slate-500">Workflows para notificações e automações.</p>
                        </div>
                    </div>
                    <a href="http://{{ request()->getHost() }}:32772" target="_blank" rel="noopener"
                        class="flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-[12px] font-semibold text-slate-600 hover:bg-slate-50 transition">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Abrir n8n
                    </a>
                </div>

                <div class="p-5 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">URL do Webhook n8n</label>
                        <div class="flex gap-2">
                            <input type="url" id="n8n-url-input" name="n8n_url"
                                value="{{ old('n8n_url', $config['n8n_url']) }}"
                                placeholder="http://2.24.205.178:32772/webhook/futuredata"
                                class="flex-1 rounded-lg border border-slate-200 px-3.5 py-2.5 text-[13px] outline-none transition focus:border-[#ea4b71]/50 focus:ring-2 focus:ring-[#ea4b71]/10"/>
                            <button type="button" @click="testN8n()"
                                :disabled="testingN8n"
                                class="flex items-center gap-1.5 rounded-lg bg-[#ea4b71] px-3.5 py-2.5 text-[12.5px] font-semibold text-white hover:bg-[#d63d64] transition disabled:opacity-50">
                                <svg class="h-3.5 w-3.5" :class="{'animate-spin': testingN8n}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span x-text="testingN8n ? 'Testando...' : 'Testar'"></span>
                            </button>
                        </div>
                        <p x-show="n8nMsg" x-text="n8nMsg" :class="n8nOk ? 'text-emerald-600' : 'text-red-500'" class="mt-1.5 text-[12px] font-medium"></p>
                    </div>

                    {{-- Eventos --}}
                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-4">
                        <p class="mb-3 text-[12.5px] font-bold text-slate-700">Eventos disparados automaticamente</p>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                            @foreach([
                                ['event' => 'os.criada',               'label' => 'OS Criada',              'color' => 'blue'],
                                ['event' => 'os.status_alterado',      'label' => 'Status Alterado',        'color' => 'amber'],
                                ['event' => 'os.orcamento_respondido', 'label' => 'Orçamento Respondido',   'color' => 'emerald'],
                            ] as $ev)
                            <div class="flex items-center gap-2 rounded-lg bg-white border border-slate-100 px-3 py-2">
                                <span class="h-2 w-2 rounded-full bg-{{ $ev['color'] }}-400"></span>
                                <div>
                                    <p class="text-[12px] font-semibold text-slate-700">{{ $ev['label'] }}</p>
                                    <p class="text-[10.5px] font-mono text-slate-400">{{ $ev['event'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <p class="text-[11.5px] text-slate-400">
                            Guia completo em
                            <code class="rounded bg-slate-100 px-1.5 py-0.5 text-slate-600">docs/N8N_SETUP.md</code>
                        </p>
                        <button form="save-form" type="submit"
                            class="rounded-lg bg-slate-800 px-4 py-2 text-[12.5px] font-semibold text-white transition hover:bg-slate-900">
                            Salvar URL n8n
                        </button>
                    </div>
                </div>
            </div>

        </div>

        {{-- Coluna direita --}}
        <div class="space-y-4">

            {{-- QR Code --}}
            <div class="rounded-xl border border-slate-200 bg-white" x-data="qrcodePanel()">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h3 class="text-[14px] font-bold text-slate-900">Dispositivo WhatsApp</h3>
                        <p class="mt-0.5 text-[12px] text-slate-500">Escaneie para conectar.</p>
                    </div>
                    <button @click="loadQr()" :disabled="loading"
                        class="flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3.5 py-2 text-[12.5px] font-semibold text-white hover:bg-emerald-700 transition disabled:opacity-50">
                        <svg class="h-3.5 w-3.5" :class="{'animate-spin': loading}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h.01M18 14h3M14 18v3M18 18h3"/>
                        </svg>
                        <span x-text="loading ? 'Aguarde...' : 'Gerar QR Code'"></span>
                    </button>
                </div>

                <div class="flex min-h-[180px] items-center justify-center p-6">
                    <template x-if="qr">
                        <div class="text-center">
                            <img :src="'data:image/png;base64,' + qr" alt="QR Code" class="mx-auto h-48 w-48 rounded-xl border border-slate-200 p-2"/>
                            <p class="mt-3 text-[12px] text-slate-500">Abra o WhatsApp → Dispositivos vinculados</p>
                        </div>
                    </template>
                    <template x-if="!qr && !error">
                        <div class="text-center text-slate-400">
                            <svg class="mx-auto mb-3 h-12 w-12 opacity-30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h.01M18 14h3M14 18v3M18 18h3"/>
                            </svg>
                            <p class="text-[13px]">Clique em "Gerar QR Code"</p>
                            <p class="mt-1 text-[11.5px]">para conectar o número.</p>
                        </div>
                    </template>
                    <template x-if="error">
                        <div class="text-center">
                            <p class="text-[13px] font-medium text-red-500" x-text="error"></p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Bot toggle --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5" x-data="botToggle({{ $config['enabled'] ? 'true' : 'false' }})">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[13.5px] font-bold text-slate-900">Chatbot IA</p>
                        <p class="mt-0.5 text-[12px] text-slate-500">Respostas automáticas por Claude AI</p>
                    </div>
                    <button @click="toggle()"
                        :class="enabled ? 'bg-emerald-500' : 'bg-slate-300'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200">
                        <span :class="enabled ? 'translate-x-6' : 'translate-x-1'"
                            class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform duration-200"></span>
                    </button>
                </div>
                <div :class="enabled ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-slate-50 border-slate-200 text-slate-500'"
                    class="mt-3 flex items-center gap-2 rounded-lg border px-3 py-2 text-[12px]">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" x-show="enabled" d="M5 13l4 4L19 7"/>
                        <path stroke-linecap="round" stroke-linejoin="round" x-show="!enabled" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    <span x-text="enabled ? 'Bot ativo no WhatsApp e no portal.' : 'Bot desativado. Sem respostas automáticas.'"></span>
                </div>
            </div>

            {{-- Atividade recente --}}
            @if($recentes->count())
            <div class="rounded-xl border border-slate-200 bg-white">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h3 class="text-[13.5px] font-bold text-slate-900">Atividade de hoje</h3>
                </div>
                <ul class="divide-y divide-slate-100">
                    @foreach($recentes as $msg)
                    <li class="flex items-start gap-3 px-4 py-3">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-[9px] font-bold text-white mt-0.5
                            {{ $msg->tipo === 'cliente' ? 'bg-blue-500' : ($msg->user_id ? 'bg-violet-500' : 'bg-slate-400') }}">
                            {{ $msg->tipo === 'cliente' ? 'C' : ($msg->user_id ? 'T' : 'B') }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[12px] font-medium text-slate-700">
                                {{ $msg->ordem->cliente->nome ?? '—' }}
                                <span class="font-normal text-slate-400">· {{ $msg->ordem->numero ?? '' }}</span>
                            </p>
                            <p class="mt-0.5 truncate text-[11.5px] text-slate-500">{{ $msg->conteudo }}</p>
                        </div>
                        <span class="shrink-0 text-[11px] text-slate-400">{{ $msg->created_at->format('H:i') }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Links rápidos --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <h3 class="mb-3 text-[13px] font-bold text-slate-900">Links rápidos</h3>
                <div class="space-y-2">
                    @foreach([
                        ['label' => 'Evolution Manager',     'url' => 'http://2.24.205.178:32774/manager', 'color' => 'emerald'],
                        ['label' => 'n8n Workflows',         'url' => 'http://2.24.205.178:32772',         'color' => 'pink'],
                        ['label' => 'Webhook URL',           'url' => url('/webhook/whatsapp'),            'color' => 'blue'],
                    ] as $link)
                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener"
                        class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 px-3.5 py-2.5 text-[12.5px] font-medium text-slate-700 hover:bg-slate-100 transition">
                        {{ $link['label'] }}
                        <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- Form hidden para salvar n8n URL (reusa o mesmo endpoint) --}}
    <form id="save-form" action="{{ route('app.whatsapp.save') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="url"      value="{{ $config['url'] }}">
        <input type="hidden" name="key"      value="{{ $config['key'] }}">
        <input type="hidden" name="instance" value="{{ $config['instance'] }}">
        <input type="hidden" name="n8n_url"  id="save-n8n-url">
    </form>

</div>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function whatsappPage() {
    return {
        status: 'unknown',
        phone: '',
        refreshing: false,
        copied: false,
        registering: false,
        webhookMsg: '',
        webhookOk: false,
        testingN8n: false,
        n8nMsg: '',
        n8nOk: false,

        init() { this.refreshStatus(); setInterval(() => this.refreshStatus(), 20000); },

        async refreshStatus() {
            this.refreshing = true;
            try {
                const res  = await fetch('{{ route('app.whatsapp.status') }}');
                const data = await res.json();
                this.status = data.state ?? 'unknown';
                this.phone  = data.phone ?? '';
            } catch {}
            this.refreshing = false;
        },

        copyWebhook() {
            navigator.clipboard.writeText(document.getElementById('webhook-url').textContent.trim());
            this.copied = true;
            setTimeout(() => this.copied = false, 2000);
        },

        async registerWebhook() {
            this.registering = true;
            this.webhookMsg  = '';
            try {
                const res  = await fetch('{{ route('app.whatsapp.register-webhook') }}', {
                    method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                });
                const data = await res.json();
                if (data.ok) {
                    this.webhookOk  = true;
                    this.webhookMsg = '✓ Webhook registrado com sucesso na Evolution API!';
                } else {
                    this.webhookOk  = false;
                    this.webhookMsg = 'Erro: ' + (data.error ?? 'falha desconhecida');
                }
            } catch { this.webhookOk = false; this.webhookMsg = 'Falha de comunicação.'; }
            this.registering = false;
        },

        async testN8n() {
            const url = document.getElementById('n8n-url-input').value.trim();
            if (url) {
                document.getElementById('save-n8n-url').value = url;
            }
            this.testingN8n = true;
            this.n8nMsg     = '';
            try {
                const res  = await fetch('{{ route('app.whatsapp.test-n8n') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ url }),
                });
                const data = await res.json();
                if (data.ok) {
                    this.n8nOk  = true;
                    this.n8nMsg = '✓ n8n recebeu o evento de teste! (HTTP ' + data.status + ')';
                } else {
                    this.n8nOk  = false;
                    this.n8nMsg = 'Erro: ' + (data.error ?? 'falha');
                }
            } catch { this.n8nOk = false; this.n8nMsg = 'Falha de comunicação.'; }
            this.testingN8n = false;
        },
    };
}

function qrcodePanel() {
    return {
        qr: null, error: null, loading: false,
        async loadQr() {
            this.loading = true; this.error = null; this.qr = null;
            try {
                const res  = await fetch('{{ route('app.whatsapp.qrcode') }}');
                const data = await res.json();
                if (data.qr) this.qr = data.qr;
                else this.error = data.error ?? 'Erro ao gerar QR Code.';
            } catch { this.error = 'Falha de comunicação.'; }
            this.loading = false;
        }
    };
}

function botToggle(initial) {
    return {
        enabled: initial,
        async toggle() {
            this.enabled = !this.enabled;
            await fetch('{{ route('app.whatsapp.bot-toggle') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ enabled: this.enabled }),
            });
        }
    };
}
</script>
@endpush
