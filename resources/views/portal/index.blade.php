@extends('layouts.app')
@section('content')

{{-- Portal do Cliente --}}
@if($cliente)

    {{-- Conteúdo principal do portal --}}
    <div class="container mx-auto px-4 py-6">

        {{-- Estatísticas --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center">
                <p class="text-2xl font-bold text-slate-700">{{ $stats['total'] }}</p>
                <p class="text-sm text-slate-400">Total de Ordens</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $stats['abertas'] }}</p>
                <p class="text-sm text-slate-400">Em Andamento</p>
            </div>
            <div class="bg-white rounded-lg border border-slate-200 p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ $stats['finalizadas'] }}</p>
                <p class="text-sm text-slate-400">Finalizadas</p>
            </div>
        </div>

        {{-- Lista de ordens --}}
        @if($ordens->isEmpty())
            <div class="text-center py-12 text-slate-400">
                <p>Nenhuma ordem de serviço encontrada.</p>
            </div>
        @else
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-slate-600 font-medium">Código</th>
                            <th class="px-4 py-3 text-left text-slate-600 font-medium">Equipamento</th>
                            <th class="px-4 py-3 text-left text-slate-600 font-medium">Status</th>
                            <th class="px-4 py-3 text-left text-slate-600 font-medium">Data</th>
                            <th class="px-4 py-3 text-left text-slate-600 font-medium">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($ordens as $ordem)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-mono text-slate-700">{{ $ordem->codigo_publico }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $ordem->equipamento->nome ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    {{ $ordem->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-400">{{ $ordem->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('portal.show', $ordem->codigo_publico) }}"
                                   class="text-blue-600 hover:underline text-xs">Ver detalhes</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if($ordens->hasPages())
            <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4">
                <p class="text-[12px] text-slate-400">
                    {{ $ordens->firstItem() }}–{{ $ordens->lastItem() }} de {{ $ordens->total() }}
                </p>

                <div class="flex gap-1">

                    @if(!$ordens->onFirstPage())
                        <a href="{{ $ordens->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m15 18-6-6 6-6"/>
                            </svg>
                        </a>
                    @endif

                    @if($ordens->hasMorePages())
                        <a href="{{ $ordens->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </a>
                    @endif

                </div>
            </div>
            @endif 
        @endif 

    </div>
@else
    <div class="text-center py-16 text-slate-400">
        <p class="text-lg font-medium">Nenhum cliente associado a este utilizador.</p>
        <p class="text-sm mt-2">Contacte o suporte para associar a sua conta.</p>
    </div>
@endif 

@endsection