@extends('layouts.app')
@section('title', 'Registrar Entrada')

@section('breadcrumbs')
@include('layouts.partials.breadcrumbs', ['items' => [
    ['label' => 'Ordens de Serviço', 'href' => route('app.os.index')],
    ['label' => 'Registrar Entrada'],
]])
@endsection

@section('content')

<div
    x-data="{
        tipoCliente: '{{ old('tipo_cliente', 'existente') }}',
        busca: '',
        clienteId: '{{ old('cliente_id', '') }}',
        clienteNome: '',
        get clientesFiltrados() {
            if (this.busca.length < 1) return this.clientes;
            const b = this.busca.toLowerCase();
            return this.clientes.filter(c =>
                c.nome.toLowerCase().includes(b) ||
                (c.telefone && c.telefone.includes(b))
            );
        },
        clientes: {!! $clientesJson !!},
        selectCliente(id, nome) {
            this.clienteId = id;
            this.clienteNome = nome;
            this.busca = nome;
        },
        dropdownOpen: false,
    }"
    class="mx-auto max-w-3xl"
>

    {{-- Header --}}
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('app.os.index') }}"
           class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7M19 12H5"/></svg>
        </a>
        <div>
            <h1 class="text-[20px] font-bold text-slate-900">Registrar Entrada</h1>
            <p class="text-[12.5px] text-slate-500">Registre a entrada de um equipamento na assistência técnica.</p>
        </div>
    </div>

    <form action="{{ route('app.os.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- ═══ SEÇÃO 1: CLIENTE ═══ --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

            {{-- Header seção --}}
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-[11px] font-bold text-white">1</div>
                    <h2 class="text-[13.5px] font-bold text-slate-900">Cliente</h2>
                </div>
                {{-- Toggle Existente / Novo --}}
                <div class="flex rounded-xl border border-slate-200 bg-slate-50 p-0.5">
                    <button type="button"
                            @click="tipoCliente = 'existente'; busca = ''; clienteId = ''"
                            :class="tipoCliente === 'existente' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                            class="rounded-lg px-3.5 py-1.5 text-[12.5px] font-semibold transition-all">
                        Cadastrado
                    </button>
                    <button type="button"
                            @click="tipoCliente = 'novo'; clienteId = ''"
                            :class="tipoCliente === 'novo' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                            class="rounded-lg px-3.5 py-1.5 text-[12.5px] font-semibold transition-all">
                        + Novo cliente
                    </button>
                </div>
            </div>

            <input type="hidden" name="tipo_cliente" :value="tipoCliente">

            {{-- CLIENTE EXISTENTE --}}
            <div x-show="tipoCliente === 'existente'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="p-6">
                <label class="mb-2 block text-[12.5px] font-semibold text-slate-700">
                    Buscar cliente <span class="text-red-500">*</span>
                </label>

                <div class="relative" x-data @click.outside="dropdownOpen = false">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <input
                            type="text"
                            x-model="busca"
                            @focus="dropdownOpen = true"
                            @input="dropdownOpen = true; clienteId = ''"
                            placeholder="Digite o nome ou telefone…"
                            autocomplete="off"
                            class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        >
                        <button x-show="busca.length > 0" type="button" @click="busca = ''; clienteId = ''" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Dropdown de clientes --}}
                    <div
                        x-show="dropdownOpen && clientesFiltrados.length > 0"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="absolute z-30 mt-1 w-full overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl"
                        style="display:none"
                    >
                        <div class="max-h-56 overflow-y-auto [scrollbar-width:thin]">
                            <template x-for="c in clientesFiltrados.slice(0, 8)" :key="c.id">
                                <button
                                    type="button"
                                    @click="selectCliente(c.id, c.nome); dropdownOpen = false"
                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left transition hover:bg-slate-50"
                                    :class="clienteId == c.id ? 'bg-blue-50' : ''"
                                >
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 text-[11px] font-bold text-blue-700" x-text="c.iniciais"></div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-semibold text-slate-900 truncate" x-text="c.nome"></p>
                                        <p class="text-[11.5px] text-slate-400" x-text="c.telefone"></p>
                                    </div>
                                    <svg x-show="clienteId == c.id" class="ml-auto h-4 w-4 shrink-0 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </template>
                        </div>
                        <div x-show="clientesFiltrados.length === 0" class="px-4 py-3 text-[13px] text-slate-400 text-center">
                            Nenhum cliente encontrado
                        </div>
                    </div>

                    <input type="hidden" name="cliente_id" :value="clienteId">
                </div>

                {{-- Cliente selecionado --}}
                <div x-show="clienteId !== ''" class="mt-3 flex items-center gap-2.5 rounded-xl bg-blue-50 px-4 py-3" style="display:none">
                    <svg class="h-4 w-4 shrink-0 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <p class="text-[13px] font-semibold text-blue-800" x-text="clienteNome"></p>
                </div>

                @error('cliente_id')
                <p class="mt-1.5 text-[12px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- NOVO CLIENTE --}}
            <div x-show="tipoCliente === 'novo'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="p-6" style="display:none">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nome completo <span class="text-red-500">*</span></label>
                        <input type="text" name="novo_cliente_nome" value="{{ old('novo_cliente_nome') }}" placeholder="Ex.: João da Silva"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 @error('novo_cliente_nome') border-red-400 @enderror">
                        @error('novo_cliente_nome')<p class="mt-1 text-[12px] text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Telefone</label>
                        <input type="text" name="novo_cliente_telefone" value="{{ old('novo_cliente_telefone') }}" placeholder="(81) 99999-0000"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">E-mail</label>
                        <input type="email" name="novo_cliente_email" value="{{ old('novo_cliente_email') }}" placeholder="email@exemplo.com"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">CPF</label>
                        <input type="text" name="novo_cliente_cpf" value="{{ old('novo_cliente_cpf') }}" placeholder="000.000.000-00"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Cidade / Estado</label>
                        <div class="flex gap-2">
                            <input type="text" name="novo_cliente_cidade" value="{{ old('novo_cliente_cidade') }}" placeholder="Cidade"
                                   class="h-10 flex-1 rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            <input type="text" name="novo_cliente_estado" value="{{ old('novo_cliente_estado') }}" placeholder="UF" maxlength="2"
                                   class="h-10 w-16 rounded-xl border border-slate-200 bg-white px-3 text-[13px] text-center placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ SEÇÃO 2: EQUIPAMENTO ═══ --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-6 py-4 flex items-center gap-2.5">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-[11px] font-bold text-white">2</div>
                <h2 class="text-[13.5px] font-bold text-slate-900">Equipamento</h2>
            </div>
            <div class="grid gap-4 p-6 sm:grid-cols-2">
                @php
                    $tipos = ['Notebook','Celular','PC','Impressora','Monitor','Tablet','TV','Videogame','Outro'];
                @endphp
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Tipo <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="equipamento_tipo" required
                                class="h-10 w-full appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Selecione o tipo…</option>
                            @foreach($tipos as $t)
                            <option value="{{ $t }}" @selected(old('equipamento_tipo') === $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Marca</label>
                    <input type="text" name="equipamento_marca" value="{{ old('equipamento_marca') }}" placeholder="Ex.: Dell, Samsung, Apple…"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Modelo</label>
                    <input type="text" name="equipamento_modelo" value="{{ old('equipamento_modelo') }}" placeholder="Ex.: Galaxy S23, MacBook Air…"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nº de Série</label>
                    <input type="text" name="equipamento_serie" value="{{ old('equipamento_serie') }}" placeholder="Número de série"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Acessórios recebidos</label>
                    <input type="text" name="equipamento_acessorios" value="{{ old('equipamento_acessorios') }}" placeholder="Ex.: Carregador, capa, caixa…"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Condição de entrada</label>
                    <input type="text" name="equipamento_condicao" value="{{ old('equipamento_condicao') }}" placeholder="Ex.: Sem avarias externas…"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div class="sm:col-span-2 flex items-center gap-2.5">
                    <input type="checkbox" name="equipamento_garantia" id="garantia" value="1"
                           class="h-4 w-4 rounded border-slate-300 accent-blue-600"
                           @checked(old('equipamento_garantia'))>
                    <label for="garantia" class="cursor-pointer text-[13px] font-medium text-slate-700">
                        Equipamento dentro do prazo de garantia
                    </label>
                </div>
            </div>
        </div>

        {{-- ═══ SEÇÃO 3: PROBLEMA ═══ --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-6 py-4 flex items-center gap-2.5">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-[11px] font-bold text-white">3</div>
                <h2 class="text-[13.5px] font-bold text-slate-900">Problema e Serviço</h2>
            </div>
            <div class="space-y-4 p-6">
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">
                        Problema relatado pelo cliente <span class="text-red-500">*</span>
                    </label>
                    <textarea name="problema_relatado" rows="3" required
                              placeholder="Descreva o problema exatamente como relatado pelo cliente…"
                              class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 @error('problema_relatado') border-red-400 @enderror">{{ old('problema_relatado') }}</textarea>
                    @error('problema_relatado')<p class="mt-1 text-[12px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Observações internas</label>
                    <textarea name="observacoes" rows="2"
                              placeholder="Notas internas, checklist de entrada, itens verificados…"
                              class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">{{ old('observacoes') }}</textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Status inicial</label>
                        <div class="relative">
                            <select name="status" class="h-10 w-full appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                @foreach($status as $key => $s)
                                <option value="{{ $key }}" @selected(old('status', 'entrada') === $key)>{{ $s['label'] }}</option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Orçamento (R$)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[12.5px] font-medium text-slate-400">R$</span>
                            <input type="number" name="valor_servico" step="0.01" min="0" value="{{ old('valor_servico', '0.00') }}"
                                   class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Previsão de entrega</label>
                        <input type="date" name="previsao_entrega" value="{{ old('previsao_entrega') }}"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    </div>
                </div>

                {{-- Hidden extras --}}
                <input type="hidden" name="valor_pecas" value="0">
                <input type="hidden" name="desconto" value="0">
            </div>
        </div>

        {{-- Ações --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end pb-6">
            <a href="{{ route('app.os.index') }}"
               class="flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-[13.5px] font-semibold text-slate-700 transition hover:bg-slate-50">
                Cancelar
            </a>
            <button type="submit"
                    class="flex h-10 items-center justify-center gap-2 rounded-xl bg-blue-600 px-8 text-[13.5px] font-bold text-white shadow-sm transition hover:bg-blue-700 active:scale-[0.99]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Registrar Entrada
            </button>
        </div>
    </form>
</div>

@endsection
