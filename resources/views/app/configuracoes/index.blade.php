@extends('layouts.app')
@section('title', 'Configurações')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [['label' => 'Configurações']]
    ])
@endsection

@section('content')


<div x-data="{ tab: 'empresa' }">

    <h1 class="text-[26px] font-bold tracking-tight text-slate-900 mb-6">Configurações</h1>

    <div class="flex gap-8 items-start">

        {{-- ── Nav lateral ── --}}
        <nav class="w-48 shrink-0 space-y-0.5">
            @foreach([
                ['key' => 'empresa',       'label' => 'Empresa'],
                ['key' => 'sistema',       'label' => 'Sistema'],
                ['key' => 'email',         'label' => 'E-mail'],
                ['key' => 'notificacoes',  'label' => 'Notificações'],
                ['key' => 'integracoes',   'label' => 'Integrações'],
            ] as $item)
            <button type="button"
                    @click="tab = '{{ $item['key'] }}'"
                    :class="tab === '{{ $item['key'] }}' ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                    class="w-full rounded-lg px-3 py-2 text-left text-[13.5px] transition-all">
                {{ $item['label'] }}
            </button>
            @endforeach
        </nav>

        {{-- ── Conteúdo ── --}}
        <div class="flex-1 min-w-0">

            {{-- Empresa --}}
            <div x-show="tab === 'empresa'" x-cloak>
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-[15px] font-bold text-slate-900">Dados da Empresa</h2>
                        <p class="text-[12.5px] text-slate-500 mt-0.5">Informações que aparecem nos documentos e relatórios.</p>
                    </div>
                    <form method="POST" action="{{ route('app.configuracoes.empresa') }}" novalidate>
                        @csrf @method('PUT')

                        {{-- Nome --}}
                        <div class="flex items-start justify-between gap-8 px-6 py-4 border-b border-slate-100">
                            <div class="w-48 shrink-0 pt-1">
                                <p class="text-[13px] font-semibold text-slate-800">Nome da Empresa</p>
                                <p class="text-[12px] text-slate-500 mt-0.5">Razão social ou nome fantasia.</p>
                            </div>
                            <div class="flex-1">
                                <input type="text" name="empresa_nome"
                                       value="{{ old('empresa_nome', $config['empresa_nome'] ?? '') }}"
                                       placeholder="Future Data Assistência Técnica"
                                       class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('empresa_nome') border-red-400 @enderror" />
                                @error('empresa_nome')<p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- CNPJ + Telefone --}}
                        <div class="flex items-start justify-between gap-8 px-6 py-4 border-b border-slate-100">
                            <div class="w-48 shrink-0 pt-1">
                                <p class="text-[13px] font-semibold text-slate-800">CNPJ &amp; Telefone</p>
                                <p class="text-[12px] text-slate-500 mt-0.5">Dados fiscais e de contato.</p>
                            </div>
                            <div class="flex-1 grid grid-cols-2 gap-3">
                                <div>
                                    <label class="mb-1 block text-[12px] font-medium text-slate-600">CNPJ</label>
                                    <input type="text" name="empresa_cnpj"
                                           value="{{ old('empresa_cnpj', $config['empresa_cnpj'] ?? '') }}"
                                           placeholder="00.000.000/0000-00"
                                           class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 font-mono text-[13px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('empresa_cnpj') border-red-400 @enderror" />
                                    @error('empresa_cnpj')<p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-[12px] font-medium text-slate-600">Telefone</label>
                                    <input type="text" name="empresa_telefone"
                                           value="{{ old('empresa_telefone', $config['empresa_telefone'] ?? '') }}"
                                           placeholder="(00) 00000-0000"
                                           class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[13px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('empresa_telefone') border-red-400 @enderror" />
                                    @error('empresa_telefone')<p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- E-mail --}}
                        <div class="flex items-start justify-between gap-8 px-6 py-4 border-b border-slate-100">
                            <div class="w-48 shrink-0 pt-1">
                                <p class="text-[13px] font-semibold text-slate-800">E-mail</p>
                                <p class="text-[12px] text-slate-500 mt-0.5">E-mail de contato da empresa.</p>
                            </div>
                            <div class="flex-1">
                                <input type="email" name="empresa_email"
                                       value="{{ old('empresa_email', $config['empresa_email'] ?? '') }}"
                                       placeholder="contato@empresa.com.br"
                                       class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('empresa_email') border-red-400 @enderror" />
                                @error('empresa_email')<p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Endereço --}}
                        <div class="flex items-start justify-between gap-8 px-6 py-4 border-b border-slate-100">
                            <div class="w-48 shrink-0 pt-1">
                                <p class="text-[13px] font-semibold text-slate-800">Endereço</p>
                                <p class="text-[12px] text-slate-500 mt-0.5">Endereço físico da empresa.</p>
                            </div>
                            <div class="flex-1">
                                <input type="text" name="empresa_endereco"
                                       value="{{ old('empresa_endereco', $config['empresa_endereco'] ?? '') }}"
                                       placeholder="Rua, número, bairro, cidade — UF"
                                       class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 @error('empresa_endereco') border-red-400 @enderror" />
                                @error('empresa_endereco')<p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50">
                            <button type="submit"
                                    class="rounded-lg bg-slate-900 px-5 py-2 text-[13px] font-semibold text-white transition hover:bg-slate-700 active:scale-[.98]">
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sistema --}}
            <div x-show="tab === 'sistema'" x-cloak>
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-[15px] font-bold text-slate-900">Configurações do Sistema</h2>
                        <p class="text-[12.5px] text-slate-500 mt-0.5">Preferências gerais de funcionamento da plataforma.</p>
                    </div>
                    @php
                    $sysSettings = [
                        ['label' => 'Numeração automática de OS',   'desc' => 'Gerar número sequencial automaticamente ao criar OS', 'key' => 'auto_numero_os'],
                        ['label' => 'Exibir prazo de entrega',      'desc' => 'Mostrar campo de previsão de entrega nas ordens de serviço', 'key' => 'exibir_prazo'],
                        ['label' => 'Portal do cliente ativo',      'desc' => 'Permitir que clientes consultem suas OS pelo portal',  'key' => 'portal_ativo'],
                    ];
                    @endphp
                    @foreach($sysSettings as $i => $setting)
                    <div class="flex items-center justify-between gap-8 px-6 py-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                        <div>
                            <p class="text-[13.5px] font-semibold text-slate-800">{{ $setting['label'] }}</p>
                            <p class="text-[12px] text-slate-500 mt-0.5">{{ $setting['desc'] }}</p>
                        </div>
                        <label class="relative inline-flex cursor-pointer items-center shrink-0">
                            <input type="checkbox" class="peer sr-only" @checked($config[$setting['key']] ?? true) />
                            <div class="peer h-5 w-9 rounded-full bg-slate-200 after:absolute after:left-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:shadow after:transition-all peer-checked:bg-blue-600 peer-checked:after:translate-x-full"></div>
                        </label>
                    </div>
                    @endforeach
                    <div class="flex justify-end px-6 py-4 bg-slate-50 border-t border-slate-100">
                        <button type="button" disabled
                                class="rounded-lg bg-slate-900 px-5 py-2 text-[13px] font-semibold text-white opacity-40 cursor-not-allowed">
                            Em breve
                        </button>
                    </div>
                </div>
            </div>

            {{-- E-mail --}}
            <div x-show="tab === 'email'" x-cloak>
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-[15px] font-bold text-slate-900">Configurações de E-mail</h2>
                        <p class="text-[12.5px] text-slate-500 mt-0.5">Envio de e-mails automáticos para clientes.</p>
                    </div>
                    <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">
                            <svg class="h-7 w-7 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <p class="text-[14px] font-bold text-slate-800">Em desenvolvimento</p>
                        <p class="mt-1.5 max-w-xs text-[13px] text-slate-500">As configurações de envio de e-mail estarão disponíveis em breve.</p>
                        <span class="mt-4 inline-flex rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-[11.5px] font-semibold text-amber-700">
                            Em breve
                        </span>
                    </div>
                </div>
            </div>

            {{-- Notificações --}}
            <div x-show="tab === 'notificacoes'" x-cloak>
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-[15px] font-bold text-slate-900">Notificações</h2>
                        <p class="text-[12.5px] text-slate-500 mt-0.5">Controle quando e como você recebe alertas.</p>
                    </div>
                    <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">
                            <svg class="h-7 w-7 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                        </div>
                        <p class="text-[14px] font-bold text-slate-800">Em desenvolvimento</p>
                        <p class="mt-1.5 max-w-xs text-[13px] text-slate-500">As preferências de notificação estarão disponíveis em breve.</p>
                        <span class="mt-4 inline-flex rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-[11.5px] font-semibold text-amber-700">
                            Em breve
                        </span>
                    </div>
                </div>
            </div>

            {{-- Integrações --}}
            <div x-show="tab === 'integracoes'" x-cloak>
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-[15px] font-bold text-slate-900">Integrações</h2>
                        <p class="text-[12.5px] text-slate-500 mt-0.5">Conecte a plataforma com serviços externos.</p>
                    </div>
                    <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100">
                            <svg class="h-7 w-7 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 0 0 2.25-2.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v2.25A2.25 2.25 0 0 0 6 10.5Zm0 9.75h2.25A2.25 2.25 0 0 0 10.5 18v-2.25a2.25 2.25 0 0 0-2.25-2.25H6a2.25 2.25 0 0 0-2.25 2.25V18A2.25 2.25 0 0 0 6 20.25Zm9.75-9.75H18a2.25 2.25 0 0 0 2.25-2.25V6A2.25 2.25 0 0 0 18 3.75h-2.25A2.25 2.25 0 0 0 13.5 6v2.25a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                        </div>
                        <p class="text-[14px] font-bold text-slate-800">Em desenvolvimento</p>
                        <p class="mt-1.5 max-w-xs text-[13px] text-slate-500">Integrações com WhatsApp, e-mail e outros serviços estarão disponíveis em breve.</p>
                        <span class="mt-4 inline-flex rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-[11.5px] font-semibold text-amber-700">
                            Em breve
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
