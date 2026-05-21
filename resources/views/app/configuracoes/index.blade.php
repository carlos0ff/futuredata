@extends('layouts.app')
@section('title', 'Configurações')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [['label' => 'Configurações']]
    ])
@endsection

@section('content')

{{-- Page header --}}
<div class="mb-6">
    <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Configurações</h1>
    <p class="mt-0.5 text-[13px] text-slate-500">Gerencie as configurações do sistema.</p>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
        <svg class="h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/>
        </svg>
        <p class="text-[13px] font-medium text-emerald-700">{{ session('success') }}</p>
    </div>
@endif

<div x-data="{ tab: 'empresa' }" class="space-y-5">

    {{-- Tabs --}}
    <div class="flex gap-1 rounded-xl border border-slate-200 bg-slate-100/50 p-1">
        @foreach([
            ['key' => 'empresa',  'label' => 'Empresa'],
            ['key' => 'sistema',  'label' => 'Sistema'],
            ['key' => 'email',    'label' => 'E-mail'],
        ] as $t)
        <button type="button"
                @click="tab = '{{ $t['key'] }}'"
                :class="tab === '{{ $t['key'] }}' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                class="flex-1 rounded-lg px-4 py-2 text-[13px] font-semibold transition-all">
            {{ $t['label'] }}
        </button>
        @endforeach
    </div>

    {{-- Empresa tab --}}
    <div x-show="tab === 'empresa'" x-cloak>
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[14px] font-bold text-slate-900">Dados da Empresa</h2>
                <p class="text-[12px] text-slate-500 mt-0.5">Informações que aparecem nos documentos e relatórios.</p>
            </div>
            <form method="POST" action="{{ route('app.configuracoes.empresa') }}" class="p-6" novalidate>
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nome da Empresa</label>
                        <input type="text" name="empresa_nome"
                               value="{{ old('empresa_nome', $config['empresa_nome'] ?? '') }}"
                               placeholder="Future Data Assistência Técnica"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('empresa_nome') border-red-400 bg-red-50 @enderror" />
                        @error('empresa_nome')
                            <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">CNPJ</label>
                        <input type="text" name="empresa_cnpj"
                               value="{{ old('empresa_cnpj', $config['empresa_cnpj'] ?? '') }}"
                               placeholder="00.000.000/0000-00"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 font-mono text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('empresa_cnpj') border-red-400 bg-red-50 @enderror" />
                        @error('empresa_cnpj')
                            <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Telefone</label>
                        <input type="text" name="empresa_telefone"
                               value="{{ old('empresa_telefone', $config['empresa_telefone'] ?? '') }}"
                               placeholder="(00) 00000-0000"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('empresa_telefone') border-red-400 bg-red-50 @enderror" />
                        @error('empresa_telefone')
                            <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">E-mail</label>
                        <input type="email" name="empresa_email"
                               value="{{ old('empresa_email', $config['empresa_email'] ?? '') }}"
                               placeholder="contato@empresa.com.br"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('empresa_email') border-red-400 bg-red-50 @enderror" />
                        @error('empresa_email')
                            <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Endereço</label>
                        <input type="text" name="empresa_endereco"
                               value="{{ old('empresa_endereco', $config['empresa_endereco'] ?? '') }}"
                               placeholder="Rua, número, bairro, cidade - UF"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('empresa_endereco') border-red-400 bg-red-50 @enderror" />
                        @error('empresa_endereco')
                            <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="rounded-xl bg-blue-600 px-5 py-2 text-[13px] font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                        Salvar Dados da Empresa
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Sistema tab --}}
    <div x-show="tab === 'sistema'" x-cloak>
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[14px] font-bold text-slate-900">Configurações do Sistema</h2>
                <p class="text-[12px] text-slate-500 mt-0.5">Preferências gerais de funcionamento.</p>
            </div>
            <div class="divide-y divide-slate-50">
                @php
                $sysSettings = [
                    ['label' => 'Numeração automática de OS',   'desc' => 'Gerar número sequencial automaticamente ao criar OS', 'key' => 'auto_numero_os'],
                    ['label' => 'Exibir prazo de entrega',      'desc' => 'Mostrar campo de previsão de entrega nas OS',          'key' => 'exibir_prazo'],
                    ['label' => 'Portal do cliente ativo',      'desc' => 'Permitir que clientes consultem OS pelo portal',       'key' => 'portal_ativo'],
                ];
                @endphp
                @foreach($sysSettings as $setting)
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <p class="text-[13.5px] font-semibold text-slate-800">{{ $setting['label'] }}</p>
                        <p class="text-[12px] text-slate-500">{{ $setting['desc'] }}</p>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" class="peer sr-only"
                               @checked($config[$setting['key']] ?? true) />
                        <div class="peer h-5 w-9 rounded-full bg-slate-200 after:absolute after:left-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:shadow after:transition-all peer-checked:bg-blue-600 peer-checked:after:translate-x-full"></div>
                    </label>
                </div>
                @endforeach
            </div>
            <div class="border-t border-slate-100 px-6 py-4 flex justify-end">
                <button type="button"
                        class="rounded-xl bg-blue-600 px-5 py-2 text-[13px] font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors opacity-50 cursor-not-allowed"
                        disabled>
                    Em breve
                </button>
            </div>
        </div>
    </div>

    {{-- Email tab --}}
    <div x-show="tab === 'email'" x-cloak>
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                    <svg class="h-8 w-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                    </svg>
                </div>
                <h3 class="text-[16px] font-bold text-slate-900">Configurações de E-mail</h3>
                <p class="mt-2 max-w-sm text-[13px] text-slate-500">
                    As configurações de envio de e-mail estão em desenvolvimento e serão disponibilizadas em breve.
                </p>
                <span class="mt-4 inline-flex rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-[12px] font-semibold text-amber-700">
                    Em desenvolvimento
                </span>
            </div>
        </div>
    </div>

</div>

@endsection
