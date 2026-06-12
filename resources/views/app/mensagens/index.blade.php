@extends('layouts.app')
@section('title', 'Mensagens')
@section('fullpage', '1')

@push('styles')
<style>
.chat-contacts::-webkit-scrollbar,
.chat-messages::-webkit-scrollbar,
.chat-panel::-webkit-scrollbar  { width: 4px; }
.chat-contacts::-webkit-scrollbar-track,
.chat-messages::-webkit-scrollbar-track,
.chat-panel::-webkit-scrollbar-track  { background: transparent; }
.chat-contacts::-webkit-scrollbar-thumb,
.chat-messages::-webkit-scrollbar-thumb,
.chat-panel::-webkit-scrollbar-thumb  { background: #e2e8f0; border-radius: 2px; }

.typing-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: #94a3b8;
    animation: typing-bounce 1.2s ease-in-out infinite;
}
.typing-dot:nth-child(2) { animation-delay: 0.2s; }
.typing-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes typing-bounce {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.55; }
    30%            { transform: translateY(-5px); opacity: 1; }
}
</style>
@endpush

@push('scripts')
<script>
function mensagensApp() {
    return {
        active: 0,
        contacts: {!! \Illuminate\Support\Js::from($contacts) !!},
        newMsg: '',
        search: '',
        filter: 'all',
        isTyping: false,
        typingTimeout: null,
        showQuickReplies: false,
        sending: false,
        pollingInterval: null,

        quickReplies: [
            { icon: '📋', label: 'Recebemos o equip.',  text: 'Olá! Recebemos seu equipamento. Ele já está na fila de análise e em breve retornamos com o diagnóstico completo.' },
            { icon: '🔍', label: 'Diagnóstico pronto',  text: 'Seu diagnóstico está pronto! Aguardamos sua aprovação do orçamento para iniciarmos o reparo.' },
            { icon: '✅', label: 'Orçamento aprovado',  text: 'Ótimo! Orçamento aprovado. Já iniciamos o reparo. Prazo estimado: ___ dias úteis.' },
            { icon: '📦', label: 'Pronto p/ retirada',  text: 'Seu equipamento está pronto para retirada! Atendemos seg–sex das 9h às 19h e sáb das 9h às 14h.' },
            { icon: '🛡️', label: 'Garantia 90 dias',   text: 'Todos os nossos serviços têm garantia de 90 dias. Qualquer dúvida, estamos à disposição!' },
            { icon: '💳', label: 'Formas de pagamento', text: 'Aceitamos PIX, débito e crédito (parcelamos em até 6x sem juros).' },
        ],

        get filtered() {
            let list = this.contacts;
            if (this.filter === 'unread')    list = list.filter(c => c.unread > 0);
            if (this.filter === 'responded') list = list.filter(c => c.unread === 0);
            if (!this.search.trim()) return list;
            const q = this.search.toLowerCase();
            return list.filter(c =>
                c.name.toLowerCase().includes(q) || c.device.toLowerCase().includes(q) || c.os.toLowerCase().includes(q)
            );
        },

        async loadMessages(idx, silent = false) {
            const contact = this.contacts[idx];
            if (!contact?.ordemId) return;
            try {
                const res  = await fetch(`/app/mensagens/${contact.ordemId}/chat`);
                const data = await res.json();
                const wasAtBottom = (() => {
                    const el = this.$refs.msgs;
                    if (!el) return true;
                    return el.scrollHeight - el.scrollTop - el.clientHeight < 60;
                })();
                this.contacts[idx].messages = data;
                if (!silent || wasAtBottom) {
                    this.$nextTick(() => {
                        const el = this.$refs.msgs;
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                }
            } catch (e) {}
        },

        startPolling(idx) {
            this.stopPolling();
            this.pollingInterval = setInterval(() => this.loadMessages(idx, true), 5000);
        },

        stopPolling() {
            if (this.pollingInterval) { clearInterval(this.pollingInterval); this.pollingInterval = null; }
        },

        triggerTyping() {
            this.isTyping = true;
            clearTimeout(this.typingTimeout);
            this.typingTimeout = setTimeout(() => { this.isTyping = false; }, 2500);
        },

        async selectContact(realIdx) {
            this.active = realIdx;
            this.contacts[realIdx].unread = 0;
            this.showQuickReplies = false;
            await this.loadMessages(realIdx);
            this.startPolling(realIdx);
            if (this.contacts[realIdx].status === 'online') this.triggerTyping();
        },

        async sendMessage() {
            if (!this.newMsg.trim() || this.sending) return;
            const text = this.newMsg.trim();
            const now  = new Date();
            const t    = String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');

            this.contacts[this.active].messages.push({ from: 'me', text, time: t });
            this.contacts[this.active].lastMsg  = text;
            this.contacts[this.active].lastTime = 'agora';
            this.newMsg = '';
            this.showQuickReplies = false;

            this.$nextTick(() => {
                const ta = this.$refs.msgInput;
                if (ta) ta.style.height = 'auto';
                const el = this.$refs.msgs;
                if (el) el.scrollTop = el.scrollHeight;
            });

            const ordemId = this.contacts[this.active].ordemId;
            if (ordemId) {
                this.sending = true;
                try {
                    const res  = await fetch('/app/mensagens', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        },
                        body: JSON.stringify({ ordem_id: ordemId, conteudo: text }),
                    });
                    const json = await res.json();
                    // marca a última mensagem como enviada via WhatsApp
                    const msgs = this.contacts[this.active].messages;
                    if (msgs.length && json.whatsapp) {
                        msgs[msgs.length - 1].whatsapp = true;
                    }
                } catch (e) {}
                finally { this.sending = false; }
            }
        },

        useQuickReply(text) {
            this.newMsg = text;
            this.showQuickReplies = false;
            this.$nextTick(() => {
                const ta = this.$refs.msgInput;
                if (ta) {
                    ta.style.height = 'auto';
                    ta.style.height = Math.min(ta.scrollHeight, 120) + 'px';
                    ta.focus();
                }
            });
        },

        whatsappUrl() {
            const c = this.contacts[this.active];
            if (!c?.phone) return '#';
            const num  = c.phone.replace(/\D/g, '');
            const full = num.startsWith('55') ? num : '55' + num;
            const msg  = encodeURIComponent('Olá ' + c.name + '! Referente à ' + c.os + '.');
            return 'https://wa.me/' + full + '?text=' + msg;
        },

        init() {
            if (this.contacts.length > 0) {
                this.loadMessages(0);
                this.startPolling(0);
            }
        },
    };
}
</script>
@endpush

@section('content')

<div class="flex w-full flex-1 overflow-hidden" x-data="mensagensApp()">

    {{-- ══════════════════════════════════════════════════════
         COLUNA 1 — LISTA DE CONTATOS (260px)
    ══════════════════════════════════════════════════════ --}}
    <div class="flex w-[260px] shrink-0 flex-col overflow-hidden border-r border-slate-200/80 bg-white">

        {{-- Header --}}
        <div class="border-b border-slate-100 px-4 pt-4 pb-3">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-[15px] font-bold text-slate-900">Conversas</h2>
                <span x-show="contacts.reduce((s,c) => s+(c.unread||0),0) > 0"
                      x-text="contacts.reduce((s,c) => s+(c.unread||0),0)"
                      class="flex h-5 min-w-[20px] items-center justify-center rounded-full bg-blue-500 px-1.5 text-[10px] font-black text-white"></span>
            </div>

            {{-- Busca --}}
            <div class="relative mb-3">
                <svg class="absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" x-model="search" placeholder="Buscar..."
                       class="w-full rounded-lg border border-slate-200 bg-slate-50 py-1.5 pl-8 pr-3 text-[12.5px] outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100">
            </div>

            {{-- Filtros --}}
            <div class="flex gap-1">
                <button @click="filter='all'"
                        :class="filter==='all' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                        class="flex-1 rounded-lg py-1 text-[11.5px] font-semibold transition">Todos</button>
                <button @click="filter='unread'"
                        :class="filter==='unread' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                        class="flex-1 rounded-lg py-1 text-[11.5px] font-semibold transition">Pendentes</button>
                <button @click="filter='responded'"
                        :class="filter==='responded' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                        class="flex-1 rounded-lg py-1 text-[11.5px] font-semibold transition">Respondidos</button>
            </div>
        </div>

        {{-- Lista --}}
        <div class="chat-contacts flex-1 divide-y divide-slate-50 overflow-y-auto">
            <template x-for="c in filtered" :key="c.id">
                <button
                    @click="selectContact(contacts.indexOf(c))"
                    :class="active === contacts.indexOf(c)
                        ? 'bg-blue-50 border-l-[3px] border-l-blue-500'
                        : 'border-l-[3px] border-l-transparent hover:bg-slate-50'"
                    class="flex w-full items-start gap-3 px-3.5 py-3 text-left transition-all">

                    {{-- Avatar --}}
                    <div class="relative mt-0.5 shrink-0">
                        <div :class="c.color"
                             class="flex h-9 w-9 items-center justify-center rounded-full text-[11.5px] font-bold text-white shadow-sm">
                            <span x-text="c.initials"></span>
                        </div>
                        <span :class="c.status === 'online' ? 'bg-emerald-400' : 'bg-slate-300'"
                              class="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 rounded-full ring-2 ring-white"></span>
                    </div>

                    {{-- Info --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-1">
                            <span class="truncate text-[13px] font-semibold leading-tight text-slate-800" x-text="c.name"></span>
                            <span class="mt-px shrink-0 text-[10px] text-slate-400" x-text="c.lastTime"></span>
                        </div>
                        {{-- Status label --}}
                        <div class="mt-0.5 mb-1">
                            <span :class="c.statusBadge"
                                  class="inline-block rounded px-1.5 py-px text-[10px] font-semibold leading-tight"
                                  x-text="c.statusLabel"></span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="truncate text-[11.5px] text-slate-400" x-text="c.lastMsg"></span>
                            <span x-show="c.unread > 0"
                                  class="flex h-[17px] min-w-[17px] shrink-0 items-center justify-center rounded-full bg-blue-500 px-1 text-[9.5px] font-bold text-white"
                                  x-text="c.unread"></span>
                        </div>
                    </div>
                </button>
            </template>

            <div x-show="filtered.length === 0" class="px-4 py-10 text-center">
                <p class="text-[12.5px] text-slate-400">Nenhuma conversa encontrada.</p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         EMPTY STATE
    ══════════════════════════════════════════════════════ --}}
    <div x-show="contacts.length === 0"
         class="flex flex-1 flex-col items-center justify-center gap-3 bg-slate-50/50">
        <svg class="h-12 w-12 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
        </svg>
        <p class="text-[13px] font-medium text-slate-400">Nenhuma conversa ainda.</p>
        <a href="{{ route('app.os.index') }}"
           class="rounded-xl bg-blue-500 px-4 py-2 text-[12.5px] font-semibold text-white transition hover:bg-blue-600">
            Ver Ordens de Serviço
        </a>
    </div>

    {{-- ══════════════════════════════════════════════════════
         COLUNA 2 — CHAT (flex-1)
    ══════════════════════════════════════════════════════ --}}
    <div x-show="contacts.length > 0" class="flex min-w-0 flex-1 flex-col overflow-hidden bg-[#f5f7fa]">

        {{-- Chat header --}}
        <div class="flex items-center gap-3 border-b border-slate-200/80 bg-white px-5 py-3">
            <div class="relative shrink-0">
                <div :class="contacts[active]?.color"
                     class="flex h-9 w-9 items-center justify-center rounded-full text-[12px] font-bold text-white">
                    <span x-text="contacts[active]?.initials"></span>
                </div>
                <span :class="contacts[active]?.status === 'online' ? 'bg-emerald-400' : 'bg-slate-300'"
                      class="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 rounded-full ring-2 ring-white"></span>
            </div>

            <div class="min-w-0 flex-1">
                <p class="text-[14px] font-bold leading-tight text-slate-900" x-text="contacts[active]?.name"></p>
                <p class="text-[11.5px] leading-tight text-slate-400" x-text="contacts[active]?.os"></p>
            </div>

            {{-- WhatsApp --}}
            <a x-show="contacts[active]?.phone"
               :href="whatsappUrl()"
               target="_blank"
               title="Abrir no WhatsApp"
               class="flex h-8 w-8 items-center justify-center rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-600 transition hover:bg-emerald-100">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                </svg>
            </a>

            <button title="Mais opções"
                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 transition hover:bg-slate-50 hover:text-slate-700">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>
                </svg>
            </button>
        </div>

        {{-- Mensagens --}}
        <div x-ref="msgs" class="chat-messages flex-1 overflow-y-auto px-4 py-3">
            <div class="mb-4 flex items-center gap-3">
                <div class="h-px flex-1 bg-slate-200/80"></div>
                <span class="rounded-full bg-slate-200/60 px-3 py-1 text-[10.5px] font-semibold text-slate-500"
                      x-text="contacts[active]?.dateLabel"></span>
                <div class="h-px flex-1 bg-slate-200/80"></div>
            </div>

            <div class="space-y-1.5">
                <template x-for="(msg, i) in (contacts[active]?.messages ?? [])" :key="i">
                    <div :class="msg.from === 'me' ? 'justify-end' : 'justify-start'"
                         class="flex items-end gap-1.5">

                        <template x-if="msg.from !== 'me'">
                            <div :class="contacts[active].color"
                                 class="mb-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[9.5px] font-bold text-white">
                                <span x-text="contacts[active].initials"></span>
                            </div>
                        </template>

                        <div :class="msg.from === 'me'
                                 ? 'bg-blue-500 text-white rounded-2xl rounded-br-sm ml-10 shadow-sm shadow-blue-200/50'
                                 : 'bg-white text-slate-800 rounded-2xl rounded-bl-sm mr-10 shadow-sm ring-1 ring-black/[0.06]'"
                             class="max-w-[70%] px-3 py-2">
                            <p class="text-[13px] leading-relaxed" x-text="msg.text"></p>
                            <div class="mt-0.5 flex items-center justify-end gap-1">
                                {{-- ícone WhatsApp quando enviado por lá --}}
                                <template x-if="msg.whatsapp">
                                    <svg class="h-3 w-3 text-emerald-300" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                                    </svg>
                                </template>
                                <span class="text-[10px]"
                                      :class="msg.from === 'me' ? 'text-blue-200' : 'text-slate-400'"
                                      x-text="msg.time"></span>
                            </div>
                        </div>

                        <template x-if="msg.from === 'me'">
                            <div class="mb-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-700 text-[9.5px] font-bold text-white">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Typing indicator --}}
                <div x-show="isTyping" class="flex items-end gap-2 justify-start">
                    <div :class="contacts[active]?.color"
                         class="mb-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[9.5px] font-bold text-white">
                        <span x-text="contacts[active]?.initials"></span>
                    </div>
                    <div class="rounded-2xl rounded-bl-sm bg-white px-3.5 py-3 shadow-sm ring-1 ring-black/[0.06]">
                        <div class="flex items-center gap-1.5">
                            <span class="typing-dot"></span>
                            <span class="typing-dot"></span>
                            <span class="typing-dot"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Respostas rápidas --}}
        <div x-show="showQuickReplies"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 translate-y-1"
             class="border-t border-slate-100 bg-white px-4 py-3">
            <p class="mb-2 text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Respostas rápidas</p>
            <div class="flex flex-wrap gap-1.5">
                <template x-for="qr in quickReplies" :key="qr.label">
                    <button @click="useQuickReply(qr.text)"
                            class="flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[11.5px] font-medium text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700">
                        <span x-text="qr.icon" class="text-[12px]"></span>
                        <span x-text="qr.label"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Input --}}
        <div class="border-t border-slate-200/80 bg-white px-4 py-3">
            <div class="flex items-end gap-2">
                <button @click="showQuickReplies = !showQuickReplies"
                        title="Respostas rápidas"
                        :class="showQuickReplies ? 'border-blue-400 bg-blue-50 text-blue-600' : 'border-slate-200 text-slate-400 hover:bg-slate-50'"
                        class="mb-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border transition">
                    <svg class="h-[15px] w-[15px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </button>

                <textarea
                    x-ref="msgInput"
                    x-model="newMsg"
                    @keydown.enter.prevent="!$event.shiftKey && sendMessage()"
                    placeholder="Digite uma mensagem… (Enter para enviar)"
                    rows="1"
                    class="flex-1 resize-none rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-[13px] text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100"
                    style="max-height:120px;overflow-y:auto"
                    @input="$el.style.height='auto'; $el.style.height=Math.min($el.scrollHeight,120)+'px'"
                ></textarea>

                <button title="Emoji"
                        class="mb-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition hover:bg-slate-50">
                    <svg class="h-[15px] w-[15px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path stroke-linecap="round" d="M8 13s1.5 2 4 2 4-2 4-2"/>
                        <line x1="9" y1="9" x2="9.01" y2="9" stroke-linecap="round" stroke-width="2.5"/>
                        <line x1="15" y1="9" x2="15.01" y2="9" stroke-linecap="round" stroke-width="2.5"/>
                    </svg>
                </button>

                <button @click="sendMessage()"
                        :disabled="!newMsg.trim() || sending"
                        :class="newMsg.trim() && !sending
                            ? 'bg-blue-500 text-white shadow-md shadow-blue-200/60 hover:bg-blue-600'
                            : 'bg-slate-100 text-slate-400 cursor-not-allowed'"
                        class="mb-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg transition">
                    <template x-if="!sending">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2" fill="currentColor" stroke="none"/>
                        </svg>
                    </template>
                    <template x-if="sending">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                        </svg>
                    </template>
                </button>
            </div>
        </div>
    </div>

    <div x-show="contacts.length > 0"
         class="chat-panel flex w-[280px] shrink-0 flex-col overflow-y-auto border-l border-slate-200/80 bg-white">

        {{-- Perfil do cliente --}}
        <div class="border-b border-slate-100 px-5 py-5">
            <p class="mb-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-400">Cliente</p>
            <div class="flex items-center gap-3">
                <div :class="contacts[active]?.color"
                     class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-[13px] font-bold text-white shadow-sm">
                    <span x-text="contacts[active]?.initials"></span>
                </div>
                <div class="min-w-0">
                    <p class="truncate text-[14px] font-bold text-slate-900" x-text="contacts[active]?.name"></p>
                    <span :class="contacts[active]?.statusBadge"
                          class="inline-block rounded px-1.5 py-px text-[10px] font-semibold leading-tight"
                          x-text="contacts[active]?.statusLabel"></span>
                </div>
            </div>

            {{-- Contatos --}}
            <div class="mt-3 space-y-1.5">
                <div x-show="contacts[active]?.phone" class="flex items-center gap-2 text-[12px] text-slate-500">
                    <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                    </svg>
                    <span x-text="contacts[active]?.phone" class="truncate"></span>
                </div>
                <div x-show="contacts[active]?.email" class="flex items-center gap-2 text-[12px] text-slate-500">
                    <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                    <span x-text="contacts[active]?.email" class="truncate"></span>
                </div>
            </div>
        </div>

        {{-- Ordem de Serviço --}}
        <div class="border-b border-slate-100 px-5 py-4">
            <p class="mb-3 text-[10.5px] font-bold uppercase tracking-wider text-slate-400">Ordem de Serviço</p>
            <div class="space-y-2.5">

                <div class="flex items-center justify-between">
                    <span class="text-[11.5px] text-slate-400">Número</span>
                    <span class="font-mono text-[11.5px] font-semibold text-slate-700" x-text="contacts[active]?.os"></span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-[11.5px] text-slate-400">Equipamento</span>
                    <span class="max-w-[140px] truncate text-right text-[11.5px] font-medium text-slate-700" x-text="contacts[active]?.device"></span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-[11.5px] text-slate-400">Entrada</span>
                    <span class="text-[11.5px] font-medium text-slate-700" x-text="contacts[active]?.dataEntrada"></span>
                </div>

                <div x-show="contacts[active]?.tecnico" class="flex items-center justify-between">
                    <span class="text-[11.5px] text-slate-400">Técnico</span>
                    <span class="max-w-[140px] truncate text-right text-[11.5px] font-medium text-slate-700" x-text="contacts[active]?.tecnico"></span>
                </div>

                <div x-show="contacts[active]?.valor" class="flex items-center justify-between">
                    <span class="text-[11.5px] text-slate-400">Valor</span>
                    <span class="text-[11.5px] font-bold text-emerald-600" x-text="contacts[active]?.valor"></span>
                </div>
            </div>
        </div>

        {{-- Problema relatado --}}
        <div x-show="contacts[active]?.problema" class="border-b border-slate-100 px-5 py-4">
            <p class="mb-2 text-[10.5px] font-bold uppercase tracking-wider text-slate-400">Problema relatado</p>
            <p class="text-[12px] leading-relaxed text-slate-600" x-text="contacts[active]?.problema"></p>
        </div>

        {{-- Ações --}}
        <div class="px-5 py-4 space-y-2">
            <a :href="'/app/ordens-servico/' + contacts[active]?.ordemId"
               class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white py-2 text-[12.5px] font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                </svg>
                Ver OS Completa
            </a>

            <a x-show="contacts[active]?.phone"
               :href="whatsappUrl()"
               target="_blank"
               class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 py-2 text-[12.5px] font-semibold text-white transition hover:bg-emerald-600">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                </svg>
                Abrir no WhatsApp
            </a>
        </div>
    </div>

</div>

@endsection
