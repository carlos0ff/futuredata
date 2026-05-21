@extends('layouts.app')
@section('title', isset($ordem) ? 'Editar OS ' . $ordem->numero : 'Nova OS')

@section('breadcrumbs')
@include('layouts.partials.breadcrumbs', ['items' => [
    ['label' => 'Ordens de Serviço', 'href' => route('app.os.index')],
    ['label' => isset($ordem) ? 'Editar ' . $ordem->numero : 'Nova OS'],
]])
@endsection

@section('content')

<div class="mx-auto max-w-4xl">

    {{-- Header --}}
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ isset($ordem) ? route('app.os.show', $ordem) : route('app.os.index') }}"
           class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7M19 12H5"/></svg>
        </a>
        <div>
            <h1 class="text-[20px] font-bold text-slate-900">{{ isset($ordem) ? 'Editar OS ' . $ordem->numero : 'Nova Ordem de Serviço' }}</h1>
            <p class="text-[12.5px] text-slate-500">{{ isset($ordem) ? 'Atualize os dados da ordem de serviço.' : 'Preencha os dados para criar uma nova OS.' }}</p>
        </div>
    </div>

    <form action="{{ isset($ordem) ? route('app.os.update', $ordem) : route('app.os.store') }}" method="POST" class="space-y-5">
        @csrf
        @if(isset($ordem)) @method('PUT') @endif

        {{-- Seção: Cliente --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[13.5px] font-bold text-slate-900">Cliente</h2>
            </div>
            <div class="grid gap-5 p-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Cliente <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="cliente_id" required
                                class="h-10 w-full appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 @error('cliente_id') border-red-400 @enderror">
                            <option value="">Selecione um cliente…</option>
                            @foreach($clientes as $c)
                            <option value="{{ $c->id }}" @selected(old('cliente_id', $ordem->cliente_id ?? '') == $c->id)>{{ $c->nome }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                    </div>
                    @error('cliente_id')<p class="mt-1 text-[12px] text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Seção: Equipamento --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[13.5px] font-bold text-slate-900">Equipamento</h2>
            </div>
            <div class="grid gap-5 p-6 sm:grid-cols-2">
                @php
                    $tipos = ['Notebook', 'Celular', 'PC', 'Impressora', 'Monitor', 'Tablet', 'TV', 'Outro'];
                @endphp
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Tipo <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="equipamento_tipo" required
                                class="h-10 w-full appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Tipo…</option>
                            @foreach($tipos as $t)
                            <option value="{{ $t }}" @selected(old('equipamento_tipo', $ordem->equipamento?->tipo ?? '') === $t)>{{ $t }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Marca</label>
                    <input type="text" name="equipamento_marca" value="{{ old('equipamento_marca', $ordem->equipamento?->marca ?? '') }}" placeholder="Ex.: Dell, Samsung, Apple…"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Modelo</label>
                    <input type="text" name="equipamento_modelo" value="{{ old('equipamento_modelo', $ordem->equipamento?->modelo ?? '') }}" placeholder="Ex.: Inspiron 15, iPhone 14…"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Nº de Série</label>
                    <input type="text" name="equipamento_serie" value="{{ old('equipamento_serie', $ordem->equipamento?->numero_serie ?? '') }}" placeholder="Número de série"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Acessórios recebidos</label>
                    <input type="text" name="equipamento_acessorios" value="{{ old('equipamento_acessorios', $ordem->equipamento?->acessorios ?? '') }}" placeholder="Ex.: Carregador, mochila…"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Condição de entrada</label>
                    <input type="text" name="equipamento_condicao" value="{{ old('equipamento_condicao', $ordem->equipamento?->condicao_entrada ?? '') }}" placeholder="Ex.: Sem avarias externas…"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div class="sm:col-span-2 flex items-center gap-2.5">
                    <input type="checkbox" name="equipamento_garantia" id="garantia" value="1" class="h-4 w-4 rounded border-slate-300 text-blue-600 accent-blue-600"
                           @checked(old('equipamento_garantia', $ordem->equipamento?->em_garantia ?? false))>
                    <label for="garantia" class="text-[13px] font-medium text-slate-700 cursor-pointer">Equipamento em garantia</label>
                </div>
            </div>
        </div>

        {{-- Seção: Serviço --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[13.5px] font-bold text-slate-900">Serviço</h2>
            </div>
            <div class="space-y-5 p-6">
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Problema relatado <span class="text-red-500">*</span></label>
                    <textarea name="problema_relatado" rows="3" required placeholder="Descreva o problema conforme relatado pelo cliente…"
                              class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 @error('problema_relatado') border-red-400 @enderror">{{ old('problema_relatado', $ordem->problema_relatado ?? '') }}</textarea>
                    @error('problema_relatado')<p class="mt-1 text-[12px] text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Diagnóstico técnico</label>
                    <textarea name="diagnostico" rows="3" placeholder="Diagnóstico identificado pelo técnico…"
                              class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">{{ old('diagnostico', $ordem->diagnostico ?? '') }}</textarea>
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Solução aplicada</label>
                    <textarea name="solucao" rows="2" placeholder="Descreva o que foi feito para resolver o problema…"
                              class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">{{ old('solucao', $ordem->solucao ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Seção: Valores e Status --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[13.5px] font-bold text-slate-900">Valores e Status</h2>
            </div>
            <div class="grid gap-5 p-6 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Valor dos serviços</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[13px] font-medium text-slate-400">R$</span>
                        <input type="number" name="valor_servico" step="0.01" min="0" value="{{ old('valor_servico', $ordem->valor_servico ?? '0.00') }}"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Valor das peças</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[13px] font-medium text-slate-400">R$</span>
                        <input type="number" name="valor_pecas" step="0.01" min="0" value="{{ old('valor_pecas', $ordem->valor_pecas ?? '0.00') }}"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Desconto</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[13px] font-medium text-slate-400">R$</span>
                        <input type="number" name="desconto" step="0.01" min="0" value="{{ old('desconto', $ordem->desconto ?? '0.00') }}"
                               class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Previsão de entrega</label>
                    <input type="date" name="previsao_entrega" value="{{ old('previsao_entrega', isset($ordem->previsao_entrega) ? $ordem->previsao_entrega->format('Y-m-d') : '') }}"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-[13px] outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Status <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="status" required
                                class="h-10 w-full appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            @foreach($status as $key => $s)
                            <option value="{{ $key }}" @selected(old('status', $ordem->status ?? 'entrada') === $key)>{{ $s['label'] }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Técnico responsável</label>
                    <div class="relative">
                        <select name="tecnico_id"
                                class="h-10 w-full appearance-none rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-[13px] text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Nenhum</option>
                            @foreach($tecnicos as $t)
                            <option value="{{ $t->id }}" @selected(old('tecnico_id', $ordem->tecnico_id ?? '') == $t->id)>{{ $t->name }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-[12.5px] font-semibold text-slate-700">Observações internas</label>
                    <textarea name="observacoes" rows="2" placeholder="Notas internas, não visíveis ao cliente…"
                              class="w-full resize-none rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-[13px] placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">{{ old('observacoes', $ordem->observacoes ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Ações --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ isset($ordem) ? route('app.os.show', $ordem) : route('app.os.index') }}"
               class="flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-6 text-[13.5px] font-semibold text-slate-700 transition hover:bg-slate-50">
                Cancelar
            </a>
            <button type="submit"
                    class="flex h-10 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 text-[13.5px] font-bold text-white shadow-sm transition hover:bg-blue-700">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ isset($ordem) ? 'Salvar alterações' : 'Criar OS' }}
            </button>
        </div>
    </form>
</div>

@endsection
