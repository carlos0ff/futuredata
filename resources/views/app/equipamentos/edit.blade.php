@extends('layouts.app')
@section('title', 'Editar Equipamento')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Equipamentos', 'href' => route('app.equipamentos.index')],
            ['label' => $equipamento->marca . ' ' . $equipamento->modelo, 'href' => route('app.equipamentos.show', $equipamento)],
            ['label' => 'Editar'],
        ]
    ])
@endsection

@section('content')

<div class="mb-6">
    <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Editar Equipamento</h1>
    <p class="mt-0.5 text-[13px] text-slate-500">Atualize os dados do equipamento.</p>
</div>

<form method="POST" action="{{ route('app.equipamentos.update', $equipamento) }}" class="space-y-5" novalidate>
    @csrf
    @method('PUT')

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h2 class="text-[14px] font-bold text-slate-900">Informações do Equipamento</h2>
        </div>
        <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2">

            {{-- Cliente --}}
            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">
                    Cliente <span class="text-red-500">*</span>
                </label>
                <select name="cliente_id"
                        class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('cliente_id') border-red-400 bg-red-50 @enderror">
                    <option value="">Selecione o cliente...</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" @selected(old('cliente_id', $equipamento->cliente_id) == $cliente->id)>
                            {{ $cliente->nome }}
                        </option>
                    @endforeach
                </select>
                @error('cliente_id')
                    <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tipo --}}
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">
                    Tipo <span class="text-red-500">*</span>
                </label>
                <select name="tipo"
                        class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('tipo') border-red-400 bg-red-50 @enderror">
                    <option value="">Selecione o tipo...</option>
                    @foreach(['Notebook', 'Desktop', 'Impressora', 'Celular', 'Tablet', 'Monitor', 'Outro'] as $tipo)
                        <option value="{{ $tipo }}" @selected(old('tipo', $equipamento->tipo) === $tipo)>{{ $tipo }}</option>
                    @endforeach
                </select>
                @error('tipo')
                    <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Marca --}}
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Marca</label>
                <input type="text" name="marca" value="{{ old('marca', $equipamento->marca) }}"
                       placeholder="Ex: Dell, Samsung, Apple..."
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('marca') border-red-400 bg-red-50 @enderror" />
                @error('marca')
                    <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Modelo --}}
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Modelo</label>
                <input type="text" name="modelo" value="{{ old('modelo', $equipamento->modelo) }}"
                       placeholder="Ex: Inspiron 15, Galaxy A52..."
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('modelo') border-red-400 bg-red-50 @enderror" />
                @error('modelo')
                    <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Número de Série --}}
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Número de Série</label>
                <input type="text" name="numero_serie" value="{{ old('numero_serie', $equipamento->numero_serie) }}"
                       placeholder="S/N do equipamento"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 font-mono text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('numero_serie') border-red-400 bg-red-50 @enderror" />
                @error('numero_serie')
                    <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Patrimônio --}}
            <div>
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nº de Patrimônio</label>
                <input type="text" name="patrimonio" value="{{ old('patrimonio', $equipamento->patrimonio) }}"
                       placeholder="Código de patrimônio"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('patrimonio') border-red-400 bg-red-50 @enderror" />
                @error('patrimonio')
                    <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Acessórios --}}
            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Acessórios</label>
                <textarea name="acessorios" rows="2"
                          placeholder="Lista de acessórios entregues com o equipamento..."
                          class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('acessorios') border-red-400 bg-red-50 @enderror">{{ old('acessorios', $equipamento->acessorios) }}</textarea>
                @error('acessorios')
                    <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Condição de entrada --}}
            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Condição de Entrada</label>
                <textarea name="condicao_entrada" rows="2"
                          placeholder="Descreva o estado físico e visual do equipamento na entrada..."
                          class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[13.5px] text-slate-800 placeholder-slate-400 outline-none transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-100 @error('condicao_entrada') border-red-400 bg-red-50 @enderror">{{ old('condicao_entrada', $equipamento->condicao_entrada) }}</textarea>
                @error('condicao_entrada')
                    <p class="mt-1 text-[11.5px] text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Em Garantia --}}
            <div class="sm:col-span-2">
                <label class="flex cursor-pointer items-center gap-3">
                    <input type="checkbox" name="em_garantia" value="1"
                           @checked(old('em_garantia', $equipamento->em_garantia))
                           class="h-4 w-4 rounded border-slate-300 text-blue-600 transition focus:ring-blue-500" />
                    <span class="text-[13px] font-medium text-slate-700">Equipamento está em garantia</span>
                </label>
            </div>

        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between pt-1">
        <div class="flex items-center gap-2" x-data="{ confirmDelete: false }">
            <button type="button"
                    @click="confirmDelete = true"
                    class="rounded-xl border border-red-200 bg-white px-4 py-2 text-[13px] font-semibold text-red-600 hover:bg-red-50 transition-colors">
                Excluir Equipamento
            </button>

            {{-- Delete Confirm --}}
            <div x-show="confirmDelete"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
                 style="display:none">
                <div class="w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-6 shadow-xl">
                    <h3 class="text-[15px] font-bold text-slate-900">Excluir equipamento?</h3>
                    <p class="mt-2 text-[13px] text-slate-500">
                        Esta ação não pode ser desfeita. O equipamento
                        <strong class="text-slate-700">{{ $equipamento->marca }} {{ $equipamento->modelo }}</strong>
                        será removido permanentemente.
                    </p>
                    <div class="mt-5 flex justify-end gap-2">
                        <button type="button" @click="confirmDelete = false"
                                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-700 hover:bg-slate-50">
                            Cancelar
                        </button>
                        <form method="POST" action="{{ route('app.equipamentos.destroy', $equipamento) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="rounded-xl bg-red-600 px-4 py-2 text-[13px] font-semibold text-white hover:bg-red-700">
                                Sim, excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('app.equipamentos.show', $equipamento) }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-2 text-[13px] font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                Salvar Alterações
            </button>
        </div>
    </div>
</form>

@endsection
