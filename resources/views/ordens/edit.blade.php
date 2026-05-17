<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar OS #12458 — AssistPro</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest" defer></script>
    
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased [font-family:'DM_Sans',sans-serif]">

<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 hidden min-h-screen w-[220px] min-w-[220px] flex-col bg-slate-950 lg:flex">
    <div class="flex items-center gap-2.5 border-b border-white/5 px-[18px] pb-[18px] pt-5">
        <img src="{{ asset('images/futuredata.png') }}" class="h-10 w-auto object-contain brightness-0 invert" alt="Future Data" />
    </div>

    <nav class="flex-1 overflow-y-auto px-2.5 py-3 [scrollbar-width:thin] [scrollbar-color:rgba(255,255,255,.1)_transparent]">
        <a href="#" class="mb-px flex items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 transition hover:bg-slate-800 hover:text-slate-100">
            <i data-lucide="layout-dashboard" class="h-[17px] w-[17px]"></i>Dashboard
        </a>
        <a href="#" class="mb-px flex items-center gap-2.5 rounded-lg bg-slate-800 px-2.5 py-[9px] text-[13.5px] font-medium text-slate-100 transition">
            <i data-lucide="clipboard-list" class="h-[17px] w-[17px]"></i>Ordens de Serviço
        </a>
        <div class="mb-1 pl-9">
            <a href="#" class="block rounded-md px-2.5 py-[7px] text-[13px] text-slate-400 transition hover:bg-slate-800 hover:text-slate-100">Lista de OS</a>
            <a href="#" class="block rounded-md px-2.5 py-[7px] text-[13px] font-semibold text-blue-500 transition hover:bg-slate-800">Editar OS</a>
        </div>
        <a href="#" class="mb-px flex items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 transition hover:bg-slate-800 hover:text-slate-100"><i data-lucide="users" class="h-[17px] w-[17px]"></i>Clientes</a>
        <a href="#" class="mb-px flex items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 transition hover:bg-slate-800 hover:text-slate-100"><i data-lucide="monitor" class="h-[17px] w-[17px]"></i>Equipamentos</a>
        <a href="#" class="mb-px flex items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 transition hover:bg-slate-800 hover:text-slate-100"><i data-lucide="wallet" class="h-[17px] w-[17px]"></i>Financeiro</a>
        <a href="#" class="mb-px flex items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 transition hover:bg-slate-800 hover:text-slate-100"><i data-lucide="package" class="h-[17px] w-[17px]"></i>Estoque</a>
        <a href="#" class="mb-px flex items-center gap-2.5 rounded-lg px-2.5 py-[9px] text-[13.5px] font-medium text-slate-400 transition hover:bg-slate-800 hover:text-slate-100"><i data-lucide="settings" class="h-[17px] w-[17px]"></i>Configurações</a>
    </nav>

    <div class="border-t border-white/5 px-2.5 py-3">
        <div class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 transition hover:bg-slate-800">
            <div class="flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-xs font-bold text-white">CJ</div>
            <div><h4 class="text-[13px] font-semibold text-slate-100">Carlos Junior</h4><p class="text-[11px] text-slate-400">Administrador</p></div>
        </div>
    </div>
</aside>

<div class="flex min-h-screen flex-1 flex-col lg:ml-[220px]">
    <header class="flex h-14 items-center gap-3 border-b border-slate-200 bg-white px-6">
        <button id="mobile-menu-button" type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 transition hover:bg-slate-200 lg:hidden" aria-label="Abrir menu lateral"><i data-lucide="menu" class="h-[18px] w-[18px]"></i></button>
        <div class="flex flex-1 items-center gap-1.5 text-[13px] text-slate-500">
            <a href="#" class="hover:text-slate-900">Ordens de Serviço</a><span class="text-slate-400">›</span><a href="#" class="hover:text-slate-900">OS #12458</a><span class="text-slate-400">›</span><span class="font-semibold text-slate-900">Editar</span>
        </div>
        <button type="button" class="relative flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-200 hover:text-slate-900"><i data-lucide="bell" class="h-[17px] w-[17px]"></i><span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full border-2 border-white bg-red-500 text-[9px] font-bold leading-none text-white">3</span></button>
    </header>

    <main class="flex-1 px-6 py-5 pb-8">
        <div class="mb-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="flex items-center gap-3.5">
                    <h1 class="text-[26px] font-bold tracking-[-0.5px]">Editar OS #12458</h1>
                    <span id="status-badge" class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600"><i data-lucide="badge-check" class="h-3.5 w-3.5"></i>Em teste</span>
                </div>
                <p class="mt-1 text-[13px] text-slate-500">Atualize os dados da ordem, acompanhe histórico, envie mensagens e fale com o cliente.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="#" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-[13.5px] font-semibold text-slate-700 transition hover:bg-slate-50"><i data-lucide="arrow-left" class="h-[15px] w-[15px]"></i>Voltar</a>
                <button id="save-os-button" type="button" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-[13.5px] font-semibold text-white transition hover:bg-blue-700"><i data-lucide="save" class="h-[15px] w-[15px]"></i>Salvar alterações</button>
            </div>
        </div>

        <form id="os-edit-form" method="POST" action="#" enctype="multipart/form-data" class="grid items-start gap-5 xl:grid-cols-[1fr_340px]">
            @csrf
            @method('PUT')

            <section class="space-y-5">
                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h2 class="text-base font-bold">Dados principais</h2>
                        <span class="rounded-md border border-amber-200 bg-amber-50 px-2.5 py-1 text-[11.5px] font-semibold text-amber-700">Edição administrativa</span>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Cliente</span><input name="cliente_nome" value="Maria Oliveira" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></label>
                        <label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Telefone do cliente</span><input id="client-phone" name="cliente_telefone" value="5511999999999" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></label>
                        <label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Equipamento</span><input name="equipamento" value="Notebook Dell Inspiron 15" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></label>
                        <label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Nº de série</span><input name="numero_serie" value="5CD1234" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></label>
                        <label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Status</span><select id="os-status" name="status" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"><option>Recebimento</option><option>Em análise</option><option selected>Em teste</option><option>Finalização</option><option>Pronto para entrega</option><option>Entregue</option></select></label>
                        <label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Técnico responsável</span><input name="tecnico" value="Carlos Eduardo" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"></label>
                    </div>
                    <label class="mt-4 block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Descrição do problema</span><textarea name="descricao" rows="4" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">Notebook não liga corretamente e apresenta falhas no conector de carga. Cliente autorizou diagnóstico técnico.</textarea></label>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <div class="flex flex-wrap gap-0 border-b border-slate-200 px-1">
                        <button type="button" data-tab="historico" class="tab-button mb-[-1px] flex items-center gap-2 border-b-2 border-blue-600 px-4 py-3.5 text-[13.5px] font-semibold text-blue-600"><i data-lucide="history" class="h-[15px] w-[15px]"></i>Histórico</button>
                        <button type="button" data-tab="arquivos" class="tab-button mb-[-1px] flex items-center gap-2 border-b-2 border-transparent px-4 py-3.5 text-[13.5px] font-medium text-slate-500 hover:text-slate-900"><i data-lucide="paperclip" class="h-[15px] w-[15px]"></i>Arquivos</button>
                        <button type="button" data-tab="mensagens" class="tab-button mb-[-1px] flex items-center gap-2 border-b-2 border-transparent px-4 py-3.5 text-[13.5px] font-medium text-slate-500 hover:text-slate-900"><i data-lucide="messages-square" class="h-[15px] w-[15px]"></i>Mensagens</button>
                        <button type="button" data-tab="orcamento" class="tab-button mb-[-1px] flex items-center gap-2 border-b-2 border-transparent px-4 py-3.5 text-[13.5px] font-medium text-slate-500 hover:text-slate-900"><i data-lucide="receipt" class="h-[15px] w-[15px]"></i>Orçamento</button>
                    </div>

                    <div data-tab-panel="historico" class="px-5 py-5 pb-6">
                        <div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 p-4">
                            <label class="mb-2 block text-xs font-semibold text-blue-700">Adicionar novo evento ao histórico</label>
                            <div class="grid gap-3 md:grid-cols-[1fr_auto]">
                                <input id="history-input" type="text" placeholder="Ex.: Cliente autorizou troca do conector de carga" class="rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
                                <button id="add-history-button" type="button" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"><i data-lucide="plus" class="h-4 w-4"></i>Adicionar</button>
                            </div>
                        </div>
                        <ol id="history-list" class="space-y-3">
                            <li class="flex gap-3 rounded-lg border border-slate-200 bg-white p-3"><span class="mt-1 h-2.5 w-2.5 rounded-full bg-green-500"></span><div><p class="text-sm font-semibold">OS recebida e triagem iniciada</p><p class="text-xs text-slate-500">12/05/2024 às 14:30 por Carlos Junior</p></div></li>
                            <li class="flex gap-3 rounded-lg border border-slate-200 bg-white p-3"><span class="mt-1 h-2.5 w-2.5 rounded-full bg-blue-500"></span><div><p class="text-sm font-semibold">Diagnóstico concluído e orçamento enviado</p><p class="text-xs text-slate-500">13/05/2024 às 10:20 por Carlos Eduardo</p></div></li>
                        </ol>
                    </div>

                    <div data-tab-panel="arquivos" class="hidden px-5 py-5 pb-6">
                        <div class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                            <i data-lucide="upload-cloud" class="mx-auto mb-2 h-8 w-8 text-slate-400"></i>
                            <p class="text-sm font-semibold">Anexar arquivos da OS</p>
                            <p class="mb-3 text-xs text-slate-500">Fotos, laudos, comprovantes, PDFs ou imagens do equipamento.</p>
                            <input id="file-input" name="arquivos[]" type="file" multiple class="hidden">
                            <button id="choose-files-button" type="button" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Selecionar arquivos</button>
                        </div>
                        <ul id="file-list" class="mt-4 space-y-2">
                            <li class="flex items-center justify-between rounded-lg border border-slate-200 p-3 text-sm"><span class="flex items-center gap-2"><i data-lucide="file-text" class="h-4 w-4 text-blue-600"></i>laudo-diagnostico.pdf</span><span class="text-xs text-slate-400">Enviado</span></li>
                        </ul>
                    </div>

                    <div data-tab-panel="mensagens" class="hidden px-5 py-5 pb-6">
                        <div id="message-list" class="mb-4 max-h-[300px] space-y-3 overflow-y-auto rounded-lg border border-slate-200 bg-slate-50 p-3">
                            <div class="max-w-[85%] rounded-lg bg-white p-3 shadow-sm"><p class="text-sm">Olá, o orçamento já foi enviado para aprovação.</p><p class="mt-1 text-[11px] text-slate-400">Atendente · 13/05/2024 10:20</p></div>
                            <div class="ml-auto max-w-[85%] rounded-lg bg-blue-600 p-3 text-white shadow-sm"><p class="text-sm">Obrigado, vou verificar ainda hoje.</p><p class="mt-1 text-[11px] text-blue-100">Cliente · 13/05/2024 10:35</p></div>
                        </div>
                        <div class="grid gap-3 md:grid-cols-[1fr_auto]">
                            <input id="message-input" type="text" placeholder="Digite uma mensagem interna ou para o cliente" class="rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
                            <button id="send-message-button" type="button" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"><i data-lucide="send" class="h-4 w-4"></i>Enviar</button>
                        </div>
                    </div>

                    <div data-tab-panel="orcamento" class="hidden px-5 py-5 pb-6">
                        <div class="grid gap-4 md:grid-cols-3">
                            <label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Serviços</span><input id="budget-services" name="valor_servicos" value="200,00" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"></label>
                            <label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Peças</span><input id="budget-parts" name="valor_pecas" value="300,00" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"></label>
                            <label class="block"><span class="mb-1.5 block text-xs font-semibold text-slate-600">Desconto</span><input id="budget-discount" name="desconto" value="30,00" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm"></label>
                        </div>
                        <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-4"><div class="flex items-center justify-between"><span class="font-bold">Total estimado</span><span id="budget-total" class="text-xl font-extrabold">R$ 470,00</span></div></div>
                    </div>
                </div>
            </section>

            <aside class="space-y-3.5">
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <div class="mb-3 flex items-center gap-3"><div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50"><i data-lucide="calendar-days" class="h-[18px] w-[18px] text-blue-600"></i></div><div class="text-sm font-bold">Previsão de entrega</div></div>
                    <div class="text-[11.5px] font-medium text-slate-400">Data prevista</div>
                    <div id="delivery-date-view" class="text-[22px] font-extrabold tracking-[-0.5px] text-green-600 [font-family:'DM_Mono',monospace]">17/05/2024</div>
                    <div id="delivery-weekday-view" class="mb-3 text-xs text-slate-500">Sexta-feira</div>
                    <input id="delivery-date-input" name="previsao_entrega" type="date" value="2024-05-17" class="mb-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <button id="update-delivery-button" type="button" class="flex w-full items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 p-[9px] text-[12.5px] font-medium text-slate-600 transition hover:bg-slate-200 hover:text-slate-900"><i data-lucide="refresh-cw" class="h-3.5 w-3.5"></i>Alterar previsão</button>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <div class="mb-3.5 text-sm font-bold">Cliente</div>
                    <div class="flex items-start gap-3"><div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-pink-500 to-rose-600 text-xs font-bold text-white">MO</div><div><h4 id="client-name-view" class="text-[13.5px] font-bold">Maria Oliveira</h4><p class="text-xs text-slate-500">maria.oliveira@email.com</p><p id="client-phone-view" class="text-xs text-slate-500">+55 (11) 99999-9999</p></div></div>
                </div>

                <div class="rounded-md border border-emerald-900 bg-gradient-to-br from-emerald-800 to-emerald-700 px-[18px] py-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[10px] bg-white/15"><i data-lucide="message-circle" class="h-5 w-5 text-white"></i></div>
                        <div><h4 class="mb-0.5 text-[13px] font-bold text-white">Falar com o cliente</h4><p class="text-xs text-white/70">Abra uma conversa direta no WhatsApp.</p></div>
                    </div>
                    <textarea id="whatsapp-message" rows="3" class="mt-3 w-full rounded-lg border border-white/20 bg-white/10 px-3 py-2 text-xs text-white outline-none placeholder:text-white/60" placeholder="Mensagem para o cliente">Olá, Maria! Estamos atualizando a sua OS #12458. A previsão de entrega é 17/05/2024.</textarea>
                    <button id="talk-client-button" type="button" class="mt-3 flex w-full items-center justify-center gap-[7px] rounded-lg border border-white/20 bg-white/15 p-2.5 text-[13px] font-semibold text-white transition hover:bg-white/25"><i data-lucide="send" class="h-[15px] w-[15px]"></i>Conversar no WhatsApp</button>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <div class="mb-3.5 text-sm font-bold">Resumo financeiro</div>
                    <div class="flex items-center justify-between border-b border-slate-100 py-[7px] text-[13px]"><span class="text-slate-500">Serviços</span><span>R$ 200,00</span></div>
                    <div class="flex items-center justify-between border-b border-slate-100 py-[7px] text-[13px]"><span class="text-slate-500">Peças</span><span>R$ 300,00</span></div>
                    <div class="flex items-center justify-between py-[7px] text-[13px]"><span class="text-slate-500">Desconto</span><span class="text-red-600">- R$ 30,00</span></div>
                    <div class="mt-2 flex items-center justify-between border-t-2 border-slate-200 pt-2.5"><span class="text-[15px] font-bold">Total</span><span class="text-base font-extrabold">R$ 470,00</span></div>
                </div>
            </aside>
        </form>
    </main>
</div>

<div id="toast" class="fixed bottom-5 right-5 hidden rounded-lg bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-xl"></div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) window.lucide.createIcons();

        const showToast = (message) => {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 2500);
        };

        document.getElementById('mobile-menu-button')?.addEventListener('click', () => {
            document.getElementById('sidebar')?.classList.toggle('hidden');
            document.getElementById('sidebar')?.classList.toggle('flex');
        });

        document.querySelectorAll('.tab-button').forEach((button) => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-button').forEach((item) => {
                    item.classList.remove('border-blue-600', 'text-blue-600', 'font-semibold');
                    item.classList.add('border-transparent', 'text-slate-500', 'font-medium');
                });
                button.classList.remove('border-transparent', 'text-slate-500', 'font-medium');
                button.classList.add('border-blue-600', 'text-blue-600', 'font-semibold');
                document.querySelectorAll('[data-tab-panel]').forEach((panel) => panel.classList.toggle('hidden', panel.dataset.tabPanel !== button.dataset.tab));
            });
        });

        document.getElementById('update-delivery-button')?.addEventListener('click', () => {
            const input = document.getElementById('delivery-date-input');
            if (!input.value) return showToast('Selecione uma data de entrega.');
            const date = new Date(input.value + 'T00:00:00');
            const formatted = date.toLocaleDateString('pt-BR');
            const weekday = date.toLocaleDateString('pt-BR', { weekday: 'long' });
            document.getElementById('delivery-date-view').textContent = formatted;
            document.getElementById('delivery-weekday-view').textContent = weekday.charAt(0).toUpperCase() + weekday.slice(1);
            document.getElementById('whatsapp-message').value = `Olá, Maria! Estamos atualizando a sua OS #12458. A nova previsão de entrega é ${formatted}.`;
            showToast('Previsão de entrega alterada.');
        });

        document.getElementById('add-history-button')?.addEventListener('click', () => {
            const input = document.getElementById('history-input');
            const value = input.value.trim();
            if (!value) return showToast('Digite um evento para o histórico.');
            const now = new Date().toLocaleString('pt-BR', { dateStyle: 'short', timeStyle: 'short' });
            const item = document.createElement('li');
            item.className = 'flex gap-3 rounded-lg border border-slate-200 bg-white p-3';
            item.innerHTML = `<span class="mt-1 h-2.5 w-2.5 rounded-full bg-amber-500"></span><div><p class="text-sm font-semibold">${value}</p><p class="text-xs text-slate-500">${now} por Carlos Junior</p></div>`;
            document.getElementById('history-list').prepend(item);
            input.value = '';
            showToast('Evento adicionado ao histórico.');
        });

        document.getElementById('choose-files-button')?.addEventListener('click', () => document.getElementById('file-input')?.click());
        document.getElementById('file-input')?.addEventListener('change', (event) => {
            const list = document.getElementById('file-list');
            Array.from(event.target.files).forEach((file) => {
                const item = document.createElement('li');
                item.className = 'flex items-center justify-between rounded-lg border border-slate-200 p-3 text-sm';
                item.innerHTML = `<span class="flex items-center gap-2"><i data-lucide="file" class="h-4 w-4 text-blue-600"></i>${file.name}</span><span class="text-xs text-slate-400">Pronto para envio</span>`;
                list.appendChild(item);
            });
            if (window.lucide) window.lucide.createIcons();
            showToast('Arquivo(s) adicionados.');
        });

        document.getElementById('send-message-button')?.addEventListener('click', () => {
            const input = document.getElementById('message-input');
            const message = input.value.trim();
            if (!message) return showToast('Digite uma mensagem.');
            const item = document.createElement('div');
            item.className = 'max-w-[85%] rounded-lg bg-white p-3 shadow-sm';
            item.innerHTML = `<p class="text-sm">${message}</p><p class="mt-1 text-[11px] text-slate-400">Atendente · ${new Date().toLocaleString('pt-BR', { dateStyle: 'short', timeStyle: 'short' })}</p>`;
            document.getElementById('message-list').appendChild(item);
            input.value = '';
            showToast('Mensagem adicionada.');
        });

        const parseMoney = (value) => Number(String(value).replace('.', '').replace(',', '.')) || 0;
        const updateBudget = () => {
            const total = parseMoney(document.getElementById('budget-services').value) + parseMoney(document.getElementById('budget-parts').value) - parseMoney(document.getElementById('budget-discount').value);
            document.getElementById('budget-total').textContent = total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        };
        ['budget-services', 'budget-parts', 'budget-discount'].forEach((id) => document.getElementById(id)?.addEventListener('input', updateBudget));
        updateBudget();

        document.getElementById('talk-client-button')?.addEventListener('click', () => {
            const phone = document.getElementById('client-phone').value.replace(/\D/g, '');
            const message = encodeURIComponent(document.getElementById('whatsapp-message').value.trim());
            if (!phone) return showToast('Informe o telefone do cliente.');
            window.open(`https://wa.me/${phone}?text=${message}`, '_blank', 'noopener,noreferrer');
        });

        document.getElementById('save-os-button')?.addEventListener('click', () => {
            showToast('Alterações prontas para salvar. Ligue este botão ao submit no Laravel.');
            // document.getElementById('os-edit-form').submit();
        });
    });
</script>

</body>
</html>
