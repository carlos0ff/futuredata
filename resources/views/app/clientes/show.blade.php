@extends('layouts.app')
@section('title', $cliente->nome)
@section('breadcrumbs')
<a href="{{ route('app.clientes.index') }}" class="text-slate-400 hover:text-slate-600">Clientes</a>
<span class="mx-1.5 text-slate-300">/</span>
<span class="font-semibold text-slate-900">{{ $cliente->nome }}</span>
@endsection
@section('content')
@if(session('success'))
<div class="mb-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-[13.5px] font-medium text-emerald-700">
    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
    <div class="flex items-center gap-4">
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 text-[18px] font-bold text-white shadow-md">{{ $cliente->iniciais }}</div>
        <div>
            <h1 class="text-[22px] font-bold tracking-tight text-slate-900">{{ $cliente->nome }}</h1>
            <p class="text-[13px] text-slate-500">{{ $cliente->email ?? $cliente->telefone ?? 'Sem contacto' }}</p>
        </div>
    </div>
    <a href="{{ route('app.clientes.edit', $cliente) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[13px] font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Editar
    </a>
</div>
<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-1 space-y-4">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-3.5"><h2 class="text-[13.5px] font-bold text-slate-900">Informações</h2></div>
            <dl class="divide-y divide-slate-50 p-1">
                @foreach([['Telefone', $cliente->telefone],['E-mail', $cliente->email],['CPF/CNPJ', $cliente->cpf_cnpj],['Endereço', $cliente->endereco],['Cidade', $cliente->cidade ? $cliente->cidade . ($cliente->estado ? ' · '.$cliente->estado : '') : null],['CEP', $cliente->cep]] as [$label, $val])
                @if($val)
                <div class="flex items-baseline justify-between gap-3 px-4 py-2.5">
                    <span class="shrink-0 text-[11.5px] font-medium text-slate-400">{{ $label }}</span>
                    <span class="text-right text-[12.5px] text-slate-800">{{ $val }}</span>
                </div>
                @endif
                @endforeach
            </dl>
        </div>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-3.5"><h2 class="text-[13.5px] font-bold text-slate-900">Equipamentos ({{ $cliente->equipamentos->count() }})</h2></div>
            @if($cliente->equipamentos->isEmpty())
            <p class="px-5 py-4 text-[12.5px] text-slate-400">Nenhum equipamento.</p>
            @else
            <div class="divide-y divide-slate-50 p-1">
                @foreach($cliente->equipamentos->take(5) as $eq)
                <div class="px-4 py-2.5">
                    <p class="text-[12.5px] font-medium text-slate-800">{{ $eq->tipo }} {{ $eq->marca }} {{ $eq->modelo }}</p>
                    @if($eq->numero_serie)<p class="text-[11px] text-slate-400">S/N: {{ $eq->numero_serie }}</p>@endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    <div class="lg:col-span-2">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5">
                <h2 class="text-[13.5px] font-bold text-slate-900">Ordens de Serviço ({{ $cliente->ordens->count() }})</h2>
                <a href="{{ route('app.os.create') }}" class="text-[12px] font-semibold text-blue-600 hover:text-blue-700">+ Nova OS</a>
            </div>
            @php
            $badgeClass = fn($s) => match($s) {
                'finalizado' => 'bg-emerald-100 text-emerald-700',
                'execucao'   => 'bg-blue-100 text-blue-700',
                'analise'    => 'bg-amber-100 text-amber-700',
                'cancelado'  => 'bg-red-100 text-red-600',
                default      => 'bg-slate-100 text-slate-600',
            };
            @endphp
            @if($cliente->ordens->isEmpty())
            <p class="px-5 py-8 text-center text-[13px] text-slate-400">Nenhuma ordem de serviço.</p>
            @else
            <div class="divide-y divide-slate-50">
                @foreach($cliente->ordens as $os)
                <div class="flex items-center justify-between gap-4 px-5 py-3.5 hover:bg-slate-50/50 transition-colors">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-[12.5px] font-bold text-slate-900">{{ $os->numero }}</span>
                            <span class="inline-flex rounded-full px-2 py-0.5 text-[10.5px] font-semibold {{ $badgeClass($os->status) }}">{{ $os->status_label }}</span>
                        </div>
                        @if($os->equipamento)<p class="text-[12px] text-slate-500 truncate">{{ $os->equipamento->tipo }} {{ $os->equipamento->marca }}</p>@endif
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="text-[11.5px] text-slate-400">{{ $os->created_at->format('d/m/Y') }}</p>
                        <a href="{{ route('app.os.show', $os) }}" class="text-[11.5px] font-semibold text-blue-600 hover:text-blue-700">Ver →</a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
