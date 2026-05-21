@extends('layouts.app')
@section('title', 'Clientes')
@section('breadcrumbs')<span class="font-semibold text-slate-900">Clientes</span>@endsection
@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Clientes</h1>
        <p class="mt-0.5 text-[13px] text-slate-500">{{ $clientes->total() }} cliente(s) cadastrado(s)</p>
    </div>
    <a href="{{ route('app.clientes.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-[13px] font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
        Novo Cliente
    </a>
</div>
@if(session('success'))
<div class="mb-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-[13.5px] font-medium text-emerald-700">
    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif
<form method="GET" class="mb-4">
    <div class="flex gap-2">
        <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Pesquisar por nome, e-mail, telefone..."
            class="flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-[13.5px] focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
        <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-[13px] font-semibold text-white hover:bg-slate-800 transition-colors">Pesquisar</button>
        @if(request('busca'))<a href="{{ route('app.clientes.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Limpar</a>@endif
    </div>
</form>
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    @if($clientes->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">
            <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/></svg>
        </div>
        <p class="text-[14px] font-semibold text-slate-700">Nenhum cliente encontrado</p>
    </div>
    @else
    <table class="w-full text-[13px]">
        <thead><tr class="border-b border-slate-100 bg-slate-50">
            <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Cliente</th>
            <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500 hidden sm:table-cell">Contacto</th>
            <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500 hidden md:table-cell">OS</th>
            <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Ações</th>
        </tr></thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($clientes as $c)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[11px] font-bold text-slate-600">{{ $c->iniciais }}</div>
                        <div>
                            <p class="font-medium text-slate-900">{{ $c->nome }}</p>
                            @if($c->cpf_cnpj)<p class="text-[11.5px] text-slate-400">{{ $c->cpf_cnpj }}</p>@endif
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3.5 text-slate-500 hidden sm:table-cell">
                    @if($c->telefone)<p>{{ $c->telefone }}</p>@endif
                    @if($c->email)<p class="text-[11.5px]">{{ $c->email }}</p>@endif
                </td>
                <td class="px-5 py-3.5 hidden md:table-cell">
                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-[11.5px] font-semibold text-slate-700">{{ $c->ordens_count }}</span>
                </td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center justify-end gap-1.5">
                        <a href="{{ route('app.clientes.show', $c) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:border-blue-300 hover:text-blue-600 transition-colors">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="{{ route('app.clientes.edit', $c) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:border-blue-300 hover:text-blue-600 transition-colors">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($clientes->hasPages())<div class="border-t border-slate-100 px-5 py-3">{{ $clientes->links() }}</div>@endif
    @endif
</div>
@endsection
