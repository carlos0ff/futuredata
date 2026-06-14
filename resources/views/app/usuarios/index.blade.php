@extends('layouts.app')
@section('title', 'Usuários')
@section('breadcrumbs')
<span class="font-semibold text-slate-900">Usuários</span>
@endsection

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Usuários</h1>
        <p class="mt-0.5 text-[13px] text-slate-500">Gerencie os utilizadores do sistema</p>
    </div>
    <a href="{{ route('app.usuarios.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-[13px] font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
        Novo Usuário
    </a>
</div>


<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    @if($usuarios->isEmpty())
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100">
            <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 0 0-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 0 1 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0"/></svg>
        </div>
        <p class="text-[14px] font-semibold text-slate-700">Nenhum usuário encontrado</p>
        <p class="mt-1 text-[12.5px] text-slate-400">Adicione o primeiro utilizador do sistema.</p>
    </div>
    @else
    <table class="w-full text-[13px]">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
                <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Utilizador</th>
                <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500 hidden sm:table-cell">E-mail</th>
                <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Função</th>
                <th class="px-5 py-3 text-left text-[10.5px] font-semibold uppercase tracking-wider text-slate-500 hidden md:table-cell">Cadastro</th>
                <th class="px-5 py-3 text-right text-[10.5px] font-semibold uppercase tracking-wider text-slate-500">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($usuarios as $u)
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-[11px] font-bold text-white">
                            {{ $u->iniciais }}
                        </div>
                        <span class="font-medium text-slate-900">{{ $u->name }}</span>
                    </div>
                </td>
                <td class="px-5 py-3.5 text-slate-500 hidden sm:table-cell">{{ $u->email }}</td>
                <td class="px-5 py-3.5">
                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold
                        {{ $u->role === 'gerente' ? 'bg-blue-100 text-blue-700' : ($u->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-purple-100 text-purple-700') }}">
                        {{ $u->roleLabel }}
                    </span>
                </td>
                <td class="px-5 py-3.5 text-slate-400 hidden md:table-cell">{{ $u->created_at->format('d/m/Y') }}</td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center justify-end gap-1.5">
                        <a href="{{ route('app.usuarios.edit', $u) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:border-blue-300 hover:text-blue-600 transition-colors">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        @if($u->id !== auth()->id())
                        <form action="{{ route('app.usuarios.destroy', $u) }}" method="POST" onsubmit="return confirm('Remover {{ addslashes($u->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 hover:border-red-300 hover:text-red-500 transition-colors">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($usuarios->hasPages())
    <div class="border-t border-slate-100 px-5 py-3">{{ $usuarios->links() }}</div>
    @endif
    @endif
</div>
@endsection
