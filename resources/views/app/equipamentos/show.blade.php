@extends('layouts.app')
@section('title', $equipamento->marca . ' ' . $equipamento->modelo)

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Equipamentos', 'href' => route('app.equipamentos.index')],
            ['label' => $equipamento->marca . ' ' . $equipamento->modelo],
        ]
    ])
@endsection

@section('content')

@php
$badgeClass = fn($s) => match($s) {
    'finalizado'         => 'bg-emerald-100 text-emerald-700',
    'execucao'           => 'bg-blue-100 text-blue-700',
    'em_teste'           => 'bg-cyan-100 text-cyan-700',
    'analise'            => 'bg-amber-100 text-amber-700',
    'aguardando_cliente' => 'bg-purple-100 text-purple-700',
    'cancelado'          => 'bg-red-100 text-red-600',
    default              => 'bg-slate-100 text-slate-600',
};
$statusLabel = [
    'entrada'            => 'Entrada',
    'analise'            => 'Em análise',
    'execucao'           => 'Em execução',
    'aguardando_cliente' => 'Aguardando',
    'em_teste'           => 'Em teste',
    'finalizado'         => 'Finalizado',
    'cancelado'          => 'Cancelado',
];
@endphp

{{-- Page header --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <div class="flex items-center gap-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 border border-blue-100">
            <svg class="h-6 w-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
            </svg>
        </div>
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-[22px] font-bold tracking-tight text-slate-900">
                    {{ $equipamento->marca }} {{ $equipamento->modelo }}
                </h1>
                <span class="inline-flex rounded-lg bg-slate-100 px-2.5 py-1 text-[11.5px] font-semibold text-slate-600">
                    {{ $equipamento->tipo }}
                </span>
                @if($equipamento->em_garantia)
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Em garantia
                    </span>
                @endif
            </div>
            @if($equipamento->numero_serie)
                <p class="mt-0.5 font-mono text-[12.5px] text-slate-500">S/N: {{ $equipamento->numero_serie }}</p>
            @endif
        </div>
    </div>
    <div class="flex shrink-0 items-center gap-2">
        <a href="{{ route('app.equipamentos.index') }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            ← Voltar
        </a>
        <a href="{{ route('app.equipamentos.edit', $equipamento) }}"
           class="rounded-xl bg-blue-600 px-4 py-2 text-[13px] font-semibold text-white hover:bg-blue-700 transition-colors">
            Editar
        </a>
    </div>
</div>

<div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

    {{-- Equipment Info --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[14px] font-bold text-slate-900">Informações do Equipamento</h2>
            </div>
            <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2">
                @php
                $fields = [
                    ['label' => 'Tipo',           'value' => $equipamento->tipo],
                    ['label' => 'Marca',          'value' => $equipamento->marca],
                    ['label' => 'Modelo',         'value' => $equipamento->modelo],
                    ['label' => 'Número de Série','value' => $equipamento->numero_serie, 'mono' => true],
                    ['label' => 'Patrimônio',     'value' => $equipamento->patrimonio],
                    ['label' => 'Em Garantia',    'value' => $equipamento->em_garantia ? 'Sim' : 'Não'],
                ];
                @endphp
                @foreach($fields as $f)
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ $f['label'] }}</p>
                        <p class="mt-0.5 text-[13.5px] {{ ($f['mono'] ?? false) ? 'font-mono' : 'font-medium' }} text-slate-800">
                            {{ $f['value'] ?: '—' }}
                        </p>
                    </div>
                @endforeach

                @if($equipamento->acessorios)
                    <div class="sm:col-span-2">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Acessórios</p>
                        <p class="mt-0.5 text-[13.5px] text-slate-700 whitespace-pre-line">{{ $equipamento->acessorios }}</p>
                    </div>
                @endif

                @if($equipamento->condicao_entrada)
                    <div class="sm:col-span-2">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Condição de Entrada</p>
                        <p class="mt-0.5 text-[13.5px] text-slate-700 whitespace-pre-line">{{ $equipamento->condicao_entrada }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Ordens de Serviço --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[14px] font-bold text-slate-900">Ordens de Serviço</h2>
                <p class="text-[12px] text-slate-500 mt-0.5">Histórico de OS vinculadas a este equipamento.</p>
            </div>
            @if($equipamento->ordens->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-slate-100">
                        <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                            <rect x="9" y="3" width="6" height="4" rx="1"/>
                        </svg>
                    </div>
                    <p class="text-[13px] font-semibold text-slate-700">Nenhum resultado encontrado</p>
                    <p class="mt-0.5 text-[12px] text-slate-400">Ainda não há OS para este equipamento.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50">
                                <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Número</th>
                                <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                                <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Data</th>
                                <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Técnico</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($equipamento->ordens as $os)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-[13px] font-bold text-slate-900">{{ $os->numero }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $badgeClass($os->status) }}">
                                        {{ $statusLabel[$os->status] ?? $os->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-[12.5px] text-slate-500 tabular-nums">
                                    {{ $os->created_at?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-[13px] text-slate-600">
                                    {{ $os->tecnico?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('app.os.show', $os) }}"
                                       class="text-[12px] font-semibold text-blue-600 hover:text-blue-700">
                                        Ver OS →
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Client Info --}}
    <div>
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-[14px] font-bold text-slate-900">Cliente</h2>
            </div>
            @if($equipamento->cliente)
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 text-[13px] font-bold text-white">
                            {{ strtoupper(substr($equipamento->cliente->nome, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-[13.5px] font-semibold text-slate-900">{{ $equipamento->cliente->nome }}</p>
                            <p class="text-[12px] text-slate-500">{{ $equipamento->cliente->email ?? '' }}</p>
                        </div>
                    </div>
                    <div class="space-y-2.5 border-t border-slate-100 pt-4">
                        @if($equipamento->cliente->telefone)
                        <div>
                            <p class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">Telefone</p>
                            <p class="mt-0.5 text-[13px] text-slate-700">{{ $equipamento->cliente->telefone }}</p>
                        </div>
                        @endif
                        @if($equipamento->cliente->cpf_cnpj)
                        <div>
                            <p class="text-[10.5px] font-semibold uppercase tracking-wider text-slate-400">CPF/CNPJ</p>
                            <p class="mt-0.5 font-mono text-[13px] text-slate-700">{{ $equipamento->cliente->cpf_cnpj }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="p-6 text-center text-[13px] text-slate-400">
                    Nenhum cliente vinculado.
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
