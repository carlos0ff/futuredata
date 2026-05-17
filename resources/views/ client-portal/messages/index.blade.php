<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssistPro | OS #12458</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f6f8fc] font-sans text-[#101828] antialiased">
<div class="flex min-h-screen">
    <aside class="hidden w-[252px] shrink-0 flex-col bg-[#00142b] text-white shadow-2xl lg:flex">
        <div class="flex h-[82px] items-center gap-3 px-6">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#1c66ff]/30 bg-[#061f45] text-[#2f80ff]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 2.5 14.4 5l3.4-.5 1.1 3.3 3.1 1.6-1.6 3.1 1.6 3.1-3.1 1.6-1.1 3.3-3.4-.5L12 21.5 9.6 19l-3.4.5-1.1-3.3L2 14.6l1.6-3.1L2 8.4l3.1-1.6 1.1-3.3 3.4.5L12 2.5Z"/>
                    <path d="m9.2 12.3 1.8 1.8 4-4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-[26px] font-extrabold leading-6 tracking-tight">AssistPro</h1>
                <p class="mt-1 text-[13px] font-medium text-slate-300">Assistência Técnica</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1 px-4 py-3 text-[15px] font-medium">
            <a href="#" class="flex h-12 items-center gap-3 rounded-xl px-3 text-slate-200 transition hover:bg-white/5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m3 11 9-8 9 8"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/></svg>
                Dashboard
            </a>

            <div class="rounded-xl bg-[#0b5fd8] text-white shadow-lg shadow-blue-950/20">
                <a href="#" class="flex h-12 items-center justify-between px-3">
                    <span class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        Ordens de Serviço
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                </a>
            </div>
            <div class="mb-4 ml-11 mt-2 space-y-3 text-[14px] text-slate-300">
                <a href="#" class="block hover:text-white">Lista de OS</a>
                <a href="#" class="block hover:text-white">Nova OS</a>
            </div>

            @php
                $menu = [
                    ['Clientes', '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
                    ['Equipamentos', '<rect x="3" y="4" width="18" height="14" rx="2"/><path d="M8 22h8"/><path d="M12 18v4"/>'],
                    ['Financeiro', '<circle cx="12" cy="12" r="9"/><path d="M15 9.5a3 3 0 0 0-3-1.5c-1.6 0-3 .8-3 2s1.4 2 3 2 3 .8 3 2-1.4 2-3 2a3 3 0 0 1-3-1.5"/><path d="M12 6v12"/>'],
                    ['Estoque', '<path d="M21 8a2 2 0 0 0-1-1.73L13 2.27a2 2 0 0 0-2 0L4 6.27A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>'],
                    ['Relatórios', '<path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>'],
                    ['Configurações', '<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>'],
                    ['Automação', '<path d="M12 8V4H8"/><rect x="4" y="8" width="16" height="12" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/>'],
                    ['Usuários', '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/>'],
                    ['Suporte', '<circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 1 1 5.8 1c0 2-3 2-3 4"/><path d="M12 17h.01"/>'],
                ];
            @endphp

            @foreach($menu as [$label, $icon])
                <a href="#" class="flex h-12 items-center gap-3 rounded-xl px-3 text-slate-200 transition hover:bg-white/5 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $icon !!}</svg>
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="px-4 pb-6">
            <div class="flex items-center gap-3 rounded-2xl px-2 py-3">
                <div class="h-11 w-11 overflow-hidden rounded-full bg-white">
                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-amber-100 to-rose-100 text-sm font-bold text-slate-800">CE</div>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[15px] font-semibold">Carlos Eduardo</p>
                    <p class="text-[12px] text-slate-300">Administrador</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
            </div>
            <a href="#" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-2 text-[14px] text-slate-300 hover:bg-white/5 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
                Sair
            </a>
        </div>
    </aside>

    <main class="min-w-0 flex-1 px-5 py-4 lg:px-9 lg:py-5">
        <header class="mb-5 flex items-center justify-between gap-6">
            <div>
                <div class="mb-5 flex items-center gap-3 text-[14px] font-medium text-slate-500">
                    <a href="#" class="hover:text-slate-800">Ordens de Serviço</a>
                    <span>›</span>
                    <span class="font-semibold text-slate-900">OS #12458</span>
                </div>
                <div class="flex items-center gap-4">
                    <h2 class="text-[32px] font-extrabold tracking-tight text-[#101828]">OS #12458</h2>
                    <span class="inline-flex items-center gap-2 rounded-full bg-[#e8f1ff] px-3 py-1.5 text-[14px] font-bold text-[#0b66d8]">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#0b66d8] text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M10 2 3 14h7l-1 8 8-12h-7l1-8Z"/></svg>
                        </span>
                        Em teste
                    </span>
                </div>
            </div>

            <div class="hidden items-center gap-4 xl:flex">
                <div class="flex h-12 w-[270px] items-center gap-3 rounded-lg border border-slate-200 bg-white px-4 shadow-sm">
                    <input class="w-full border-0 bg-transparent text-[14px] outline-none placeholder:text-slate-400" placeholder="Buscar...">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <button class="relative rounded-full p-2 text-slate-800 hover:bg-white">
                    <span class="absolute right-1 top-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">3</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10 21h4"/><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/></svg>
                </button>
                <button class="rounded-full p-2 text-slate-800 hover:bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 1 1 5.8 1c0 2-3 2-3 4"/><path d="M12 17h.01"/></svg>
                </button>
            </div>
        </header>

        <div class="mb-5 flex flex-wrap items-center justify-end gap-4">
            <button class="inline-flex h-12 items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 text-[15px] font-bold text-slate-800 shadow-sm hover:bg-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3h12v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
                Imprimir
            </button>
            <button class="inline-flex h-12 items-center gap-3 rounded-lg border border-slate-200 bg-white px-5 text-[15px] font-bold text-slate-800 shadow-sm hover:bg-slate-50">
                Ações
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <button class="inline-flex h-12 items-center gap-2 rounded-lg bg-[#0b66d8] px-5 text-[15px] font-bold text-white shadow-sm shadow-blue-900/20 hover:bg-[#095cc4]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                Editar OS
            </button>
        </div>

        <section class="mb-6 rounded-lg border border-slate-200 bg-white px-8 py-6 shadow-sm">
            <div class="grid grid-cols-6 items-start gap-2">
                @php
                    $steps = [
                        ['Recebimento', '12/05/2024 14:30', 'done'],
                        ['Em análise', '12/05/2024 16:20', 'done'],
                        ['Em teste', '13/05/2024 10:15', 'current'],
                        ['Finalização', '-', 'waiting'],
                        ['Pronto para entrega', '-', 'more'],
                        ['Entregue', '-', 'more'],
                    ];
                @endphp
                @foreach($steps as $index => [$title, $date, $state])
                    <div class="relative flex items-start gap-3">
                        @if($index < 5)
                            <div class="absolute left-[46px] top-4 h-px w-[calc(100%-40px)] border-t border-dashed border-slate-200"></div>
                        @endif
                        <div class="relative z-10 flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $state === 'done' ? 'bg-[#15b36a] text-white' : ($state === 'current' ? 'bg-[#0b66d8] text-white' : 'border border-slate-200 bg-white text-slate-400') }}">
                            @if($state === 'done')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="m5 12 4 4L19 6"/></svg>
                            @elseif($state === 'current')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10 2 3 14h7l-1 8 8-12h-7l1-8Z"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                            @endif
                        </div>
                        <div class="relative z-10 min-w-0 bg-white pr-2">
                            <p class="truncate text-[14px] font-extrabold text-slate-900">{{ $title }}</p>
                            <p class="mt-1 text-[13px] font-medium text-slate-500">{{ $date }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_384px]">
            <div class="space-y-5">
                <article class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="flex h-[58px] items-center gap-8 border-b border-slate-200 px-7 text-[15px] font-bold text-slate-500">
                        <button class="flex h-full items-center gap-2 border-b-2 border-transparent hover:text-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 16h8"/><path d="M7 12h12"/><path d="M7 8h5"/></svg>
                            Histórico
                        </button>
                        <button class="flex h-full items-center gap-2 border-b-2 border-[#0b66d8] text-[#0b66d8]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                            Orçamento
                        </button>
                        <button class="flex h-full items-center gap-2 border-b-2 border-transparent hover:text-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 1 1-2.83-2.83l8.49-8.48"/></svg>
                            Arquivos
                        </button>
                        <button class="flex h-full items-center gap-2 border-b-2 border-transparent hover:text-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/></svg>
                            Mensagens
                        </button>
                    </div>

                    <div class="px-7 py-6">
                        <div class="mb-4 flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-[17px] font-extrabold text-slate-900">Orçamento enviado em 13/05/2024 às 10:20</h3>
                                <span class="mt-3 inline-flex rounded-full bg-[#e8f8ef] px-3 py-1 text-[13px] font-bold text-[#0b9f56]">Aguardando aprovação do cliente</span>
                            </div>
                            <button class="inline-flex h-10 items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 text-[14px] font-bold text-slate-800 hover:bg-slate-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                                Reenviar orçamento
                            </button>
                        </div>

                        <div class="overflow-hidden rounded-lg border border-slate-200">
                            <table class="w-full text-left text-[14px]">
                                <thead class="bg-slate-50 text-[13px] font-bold text-slate-500">
                                    <tr>
                                        <th class="px-5 py-3">Item</th>
                                        <th class="px-5 py-3">Descrição</th>
                                        <th class="px-5 py-3 text-center">Quantidade</th>
                                        <th class="px-5 py-3 text-right">Valor unitário</th>
                                        <th class="px-5 py-3 text-right">Valor total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    <tr class="bg-white text-[#0b66d8]">
                                        <td class="px-5 py-3" colspan="5">
                                            <span class="inline-flex items-center gap-2 font-extrabold"><span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-50">◎</span>Peças e componentes</span>
                                        </td>
                                    </tr>
                                    <tr><td class="px-5 py-3">1</td><td class="px-5 py-3">Tela 15.6&quot; LED Slim</td><td class="px-5 py-3 text-center">1</td><td class="px-5 py-3 text-right">R$ 250,00</td><td class="px-5 py-3 text-right">R$ 250,00</td></tr>
                                    <tr><td class="px-5 py-3">2</td><td class="px-5 py-3">Conector de carga</td><td class="px-5 py-3 text-center">1</td><td class="px-5 py-3 text-right">R$ 80,00</td><td class="px-5 py-3 text-right">R$ 80,00</td></tr>
                                    <tr class="bg-white text-[#6f45d8]">
                                        <td class="px-5 py-3" colspan="5">
                                            <span class="inline-flex items-center gap-2 font-extrabold"><span class="flex h-5 w-5 items-center justify-center rounded-full bg-violet-50">⌁</span>Serviços</span>
                                        </td>
                                    </tr>
                                    <tr><td class="px-5 py-3">3</td><td class="px-5 py-3">Diagnóstico técnico</td><td class="px-5 py-3 text-center">1</td><td class="px-5 py-3 text-right">R$ 80,00</td><td class="px-5 py-3 text-right">R$ 80,00</td></tr>
                                    <tr><td class="px-5 py-3">4</td><td class="px-5 py-3">Mão de obra especializada</td><td class="px-5 py-3 text-center">1</td><td class="px-5 py-3 text-right">R$ 120,00</td><td class="px-5 py-3 text-right">R$ 120,00</td></tr>
                                    <tr class="bg-[#eef5ff] text-[#0b66d8]"><td class="px-5 py-3" colspan="5"><span class="font-bold">ⓘ Prazo estimado de conclusão: 2 a 3 dias úteis após aprovação.</span></td></tr>
                                </tbody>
                            </table>
                            <div class="flex justify-end bg-white px-5 py-4">
                                <div class="w-[260px] space-y-3 text-[14px]">
                                    <div class="flex justify-between"><span class="text-slate-600">Subtotal</span><strong>R$ 530,00</strong></div>
                                    <div class="flex justify-between"><span class="text-slate-600">Desconto</span><strong class="text-[#16a35f]">- R$ 30,00</strong></div>
                                    <div class="flex justify-between border-t border-slate-200 pt-3 text-[20px]"><strong>Total</strong><strong>R$ 500,00</strong></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 rounded-lg border border-slate-200 p-5">
                            <h4 class="text-[16px] font-extrabold text-slate-900">Aprovação do orçamento</h4>
                            <p class="mt-2 text-[14px] text-slate-600">Ao aprovar este orçamento, você autoriza o início do reparo do seu equipamento.</p>
                            <div class="mt-5 flex flex-wrap gap-3">
                                <button class="inline-flex h-11 items-center gap-2 rounded-lg bg-[#12a663] px-5 text-[14px] font-extrabold text-white hover:bg-[#0e9558]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg>
                                    Aprovar orçamento
                                </button>
                                <button class="inline-flex h-11 items-center gap-2 rounded-lg border border-slate-200 bg-white px-5 text-[14px] font-extrabold text-slate-800 hover:bg-slate-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    Recusar orçamento
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <aside class="space-y-4">
                <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-[16px] font-extrabold text-slate-900">Informações do equipamento</h3>
                    <div class="flex gap-4">
                        <div class="flex h-[76px] w-[86px] shrink-0 items-center justify-center rounded-md bg-slate-100">
                            <div class="h-14 w-16 rounded bg-gradient-to-br from-[#050b18] via-[#0d3c76] to-[#44a5ff] shadow-lg"></div>
                        </div>
                        <div class="text-[14px] leading-6">
                            <p class="font-extrabold text-slate-900">Notebook Dell Inspiron 15</p>
                            <p><span class="text-slate-600">Nº de Série:</span> 5CD1234</p>
                            <p><span class="text-slate-600">Modelo:</span> Inspiron 15 3000</p>
                            <p><span class="text-slate-600">Cor:</span> Preto</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-[#0b66d8]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                        </div>
                        <h3 class="text-[16px] font-extrabold text-slate-900">Previsão de entrega</h3>
                    </div>
                    <p class="text-[14px] text-slate-600">Data prevista</p>
                    <p class="mt-1 text-[22px] font-extrabold text-[#12a663]">17/05/2024</p>
                    <p class="text-[14px] font-medium text-slate-600">Sexta-feira</p>
                    <button class="mt-4 inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white text-[14px] font-bold text-slate-700 hover:bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M3 10h18"/></svg>
                        Alterar data prevista
                    </button>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mt-1 h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <div>
                                <h3 class="text-[16px] font-extrabold text-slate-900">Técnico responsável</h3>
                                <p class="mt-3 text-[14px] font-extrabold">Carlos Eduardo <span class="ml-1 rounded-full bg-[#dff7eb] px-2 py-1 text-[11px] font-bold text-[#0a9f55]">Técnico em informática</span></p>
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#12a663]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-[16px] font-extrabold text-slate-900">Resumo financeiro</h3>
                    <div class="space-y-3 text-[14px]">
                        <div class="flex justify-between"><span class="text-slate-600">Serviços</span><strong>R$ 200,00</strong></div>
                        <div class="flex justify-between"><span class="text-slate-600">Peças</span><strong>R$ 300,00</strong></div>
                        <div class="flex justify-between"><span class="text-slate-600">Desconto</span><strong class="text-[#12a663]">- R$ 30,00</strong></div>
                        <div class="flex justify-between border-t border-slate-200 pt-3 text-[18px]"><strong>Total</strong><strong>R$ 500,00</strong></div>
                    </div>
                </section>

                <section class="rounded-lg border border-[#a8e9c7] bg-[#effcf5] p-5 text-center shadow-sm">
                    <div class="mx-auto mb-2 flex h-11 w-11 items-center justify-center rounded-full bg-[#13b967] text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.92.33 1.81.62 2.66a2 2 0 0 1-.45 2.11L8 9.8a16 16 0 0 0 6.2 6.2l1.31-1.28a2 2 0 0 1 2.11-.45c.85.29 1.74.5 2.66.62A2 2 0 0 1 22 16.92Z"/></svg>
                    </div>
                    <p class="text-[15px] font-extrabold text-slate-900">Dúvidas ou precisa falar conosco?</p>
                    <p class="mt-1 text-[13px] text-slate-600">Fale diretamente pelo WhatsApp.</p>
                    <button class="mt-4 inline-flex h-10 w-full items-center justify-center gap-2 rounded-lg border border-[#13b967] bg-white text-[14px] font-extrabold text-[#079b50] hover:bg-[#f7fffa]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.92.33 1.81.62 2.66a2 2 0 0 1-.45 2.11L8 9.8a16 16 0 0 0 6.2 6.2l1.31-1.28a2 2 0 0 1 2.11-.45c.85.29 1.74.5 2.66.62A2 2 0 0 1 22 16.92Z"/></svg>
                        Conversar no WhatsApp
                    </button>
                </section>
            </aside>
        </section>
    </main>
</div>
</body>
</html>
