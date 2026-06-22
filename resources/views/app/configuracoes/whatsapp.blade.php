@extends('layouts.app')
@section('title', 'WhatsApp & Chatbot')

@section('content')
<div class="mx-auto max-w-4xl space-y-6 px-4 py-8 sm:px-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100">
            <svg class="h-6 w-6 text-emerald-600" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900">WhatsApp & Chatbot</h1>
            <p class="text-[13px] text-slate-500">Configuração da Evolution API e automação do atendimento.</p>
        </div>

        {{-- Status badge (atualizado via JS) --}}
        <div class="ml-auto">
            <span id="status-badge" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[12px] font-semibold bg-slate-100 text-slate-500">
                <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                Verificando...
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-[13px] text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_320px]">

        {{-- Coluna principal --}}
        <div class="space-y-6">

            {{-- Configuração da API --}}
            <div class="rounded-xl border border-slate-200 bg-white">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-[14px] font-bold text-slate-900">Conexão Evolution API</h2>
                    <p class="mt-0.5 text-[12px] text-slate-500">Configure a URL e credenciais da sua instância.</p>
                </div>

                <form action="{{ route('app.whatsapp.save') }}" method="POST" class="space-y-4 p-5">
                    @csrf

                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">URL da Evolution API</label>
                        <input
                            type="url"
                            name="url"
                            value="{{ old('url', $config['url']) }}"
                            placeholder="https://evolution.seudominio.com.br"
                            class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-[13px] text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        />
                        @error('url')<p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">API Key</label>
                            <input
                                type="text"
                                name="key"
                                value="{{ old('key', $config['key']) }}"
                                placeholder="sua-api-key"
                                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-[13px] text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            />
                            @error('key')<p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nome da Instância</label>
                            <input
                                type="text"
                                name="instance"
                                value="{{ old('instance', $config['instance']) }}"
                                placeholder="futuredata"
                                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-[13px] text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            />
                            @error('instance')<p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">
                            Webhook Secret
                            <span class="ml-1 font-normal text-slate-400">(opcional)</span>
                        </label>
                        <input
                            type="text"
                            name="secret"
                            placeholder="token-secreto-para-validar-webhook"
                            class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-[13px] text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        />
                        <p class="mt-1 text-[11.5px] text-slate-400">
                            Configure o mesmo valor no painel da Evolution API em Webhooks → apikey.
                        </p>
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                        <p class="text-[11.5px] text-slate-400">
                            URL do webhook:
                            <code class="rounded bg-slate-100 px-1.5 py-0.5 text-slate-600">{{ url('/webhook/whatsapp') }}</code>
                        </p>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-[13px] font-semibold text-white transition hover:bg-blue-700">
                            Salvar configurações
                        </button>
                    </div>
                </form>
            </div>

            {{-- QR Code --}}
            <div class="rounded-xl border border-slate-200 bg-white" x-data="qrcodePanel()">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="text-[14px] font-bold text-slate-900">Conexão do dispositivo</h2>
                        <p class="mt-0.5 text-[12px] text-slate-500">Escaneie o QR code com o WhatsApp para conectar.</p>
                    </div>
                    <button @click="loadQr()" class="rounded-lg border border-slate-200 px-3.5 py-2 text-[12.5px] font-semibold text-slate-600 transition hover:bg-slate-50" :disabled="loading">
                        <span x-show="!loading">Gerar QR Code</span>
                        <span x-show="loading">Aguarde...</span>
                    </button>
                </div>

                <div class="flex items-center justify-center p-8">
                    <template x-if="qr">
                        <img :src="'data:image/png;base64,' + qr" alt="QR Code WhatsApp" class="h-52 w-52 rounded-lg border border-slate-200 p-2" />
                    </template>
                    <template x-if="!qr && !error">
                        <div class="text-center text-slate-400">
                            <svg class="mx-auto mb-3 h-12 w-12 opacity-40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h.01M18 14h3M14 18v3M18 18h3"/>
                            </svg>
                            <p class="text-[13px]">Clique em "Gerar QR Code" para conectar.</p>
                        </div>
                    </template>
                    <template x-if="error">
                        <p class="text-[13px] text-red-500" x-text="error"></p>
                    </template>
                </div>
            </div>

        </div>

        {{-- Coluna lateral --}}
        <div class="space-y-4">

            {{-- Toggle do bot --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5" x-data="botToggle({{ $config['enabled'] ? 'true' : 'false' }})">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[13.5px] font-bold text-slate-900">Bot automático</p>
                        <p class="mt-0.5 text-[12px] text-slate-500">Respostas automáticas ativas</p>
                    </div>
                    <button
                        @click="toggle()"
                        :class="enabled ? 'bg-emerald-500' : 'bg-slate-300'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                    >
                        <span
                            :class="enabled ? 'translate-x-6' : 'translate-x-1'"
                            class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform"
                        ></span>
                    </button>
                </div>
                <p class="mt-3 text-[11.5px] text-slate-400" x-text="enabled ? 'O bot responde automaticamente no WhatsApp e no portal.' : 'Bot desativado. Mensagens recebidas mas sem resposta automática.'"></p>
            </div>

            {{-- Fluxo do bot --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <h3 class="mb-3 text-[13.5px] font-bold text-slate-900">Fluxo do chatbot</h3>
                <ul class="space-y-2.5">
                    @foreach([
                        ['icon' => '👋', 'title' => 'Boas-vindas', 'desc' => 'Identifica o cliente pelo número'],
                        ['icon' => '🔍', 'title' => 'Identificação', 'desc' => 'Pede CPF ou código OS se desconhecido'],
                        ['icon' => '📋', 'title' => 'Menu principal', 'desc' => 'Ver OS, falar com equipe, orçamento'],
                        ['icon' => '💰', 'title' => 'Orçamentos', 'desc' => 'Link direto para aprovação no portal'],
                        ['icon' => '👨‍🔧', 'title' => 'Equipe humana', 'desc' => 'Sinaliza para atendimento manual'],
                    ] as $step)
                    <li class="flex items-start gap-3">
                        <span class="text-base leading-tight">{{ $step['icon'] }}</span>
                        <div>
                            <p class="text-[12.5px] font-semibold text-slate-800">{{ $step['title'] }}</p>
                            <p class="text-[11.5px] text-slate-500">{{ $step['desc'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Info canais --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <h3 class="mb-3 text-[13.5px] font-bold text-slate-900">Canais suportados</h3>
                <div class="space-y-2">
                    <div class="flex items-center gap-2.5 rounded-lg bg-emerald-50 px-3 py-2.5">
                        <span class="text-emerald-600">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-[12.5px] font-semibold text-emerald-800">WhatsApp</p>
                            <p class="text-[11px] text-emerald-600">Evolution API — mensagens bidirecionais</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 rounded-lg bg-blue-50 px-3 py-2.5">
                        <span class="text-blue-600">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-[12.5px] font-semibold text-blue-800">Portal do Cliente</p>
                            <p class="text-[11px] text-blue-600">Bot responde no chat do portal</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function qrcodePanel() {
    return {
        qr: null,
        error: null,
        loading: false,
        async loadQr() {
            this.loading = true;
            this.error = null;
            this.qr = null;
            try {
                const res = await fetch('{{ route('app.whatsapp.qrcode') }}');
                const data = await res.json();
                if (data.qr) this.qr = data.qr;
                else this.error = data.error ?? 'Erro ao gerar QR Code.';
            } catch (e) {
                this.error = 'Falha de comunicação com o servidor.';
            } finally {
                this.loading = false;
            }
        }
    }
}

function botToggle(initial) {
    return {
        enabled: initial,
        async toggle() {
            this.enabled = !this.enabled;
            await fetch('{{ route('app.whatsapp.bot-toggle') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ enabled: this.enabled }),
            });
        }
    }
}

// Checa status de conexão ao carregar
async function checkStatus() {
    const badge = document.getElementById('status-badge');
    try {
        const res  = await fetch('{{ route('app.whatsapp.status') }}');
        const data = await res.json();
        const map  = {
            open:           ['bg-emerald-100 text-emerald-700', 'bg-emerald-500', 'Conectado'],
            connecting:     ['bg-amber-100 text-amber-700',   'bg-amber-500',   'Conectando'],
            close:          ['bg-red-100 text-red-700',       'bg-red-500',     'Desconectado'],
            not_configured: ['bg-slate-100 text-slate-500',   'bg-slate-400',   'Não configurado'],
            offline:        ['bg-red-100 text-red-700',       'bg-red-500',     'Offline'],
        };
        const [cls, dotCls, label] = map[data.state] ?? map.offline;
        badge.className = `inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[12px] font-semibold ${cls}`;
        badge.innerHTML = `<span class="h-2 w-2 rounded-full ${dotCls}"></span>${label}`;
    } catch {}
}
checkStatus();
</script>
@endpush
