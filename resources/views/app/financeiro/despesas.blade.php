@extends('layouts.app')
@section('title', 'Despesas')

@section('breadcrumbs')
    @include('layouts.partials.breadcrumbs', [
        'items' => [
            ['label' => 'Financeiro', 'href' => route('app.financeiro.index')],
            ['label' => 'Despesas'],
        ]
    ])
@endsection

@section('content')

{{-- Page header --}}
<div class="mb-6">
    <h1 class="text-[22px] font-bold tracking-tight text-slate-900">Despesas</h1>
    <p class="mt-0.5 text-[13px] text-slate-500">Controle e registro de despesas operacionais.</p>
</div>

{{-- Em desenvolvimento --}}
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col items-center justify-center px-6 py-20 text-center">
        <div class="mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-slate-100 to-slate-200">
            <svg class="h-10 w-10 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
            </svg>
        </div>
        <h2 class="text-[18px] font-bold text-slate-900">Em Desenvolvimento</h2>
        <p class="mt-2 max-w-md text-[14px] text-slate-500">
            O módulo de controle de despesas está sendo desenvolvido e estará disponível em breve.
        </p>

        <div class="mt-8 w-full max-w-sm rounded-xl border border-blue-100 bg-blue-50 p-5 text-left">
            <div class="flex items-start gap-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4M12 8h.01"/>
                </svg>
                <div>
                    <p class="text-[13px] font-semibold text-blue-800">O que estará disponível</p>
                    <ul class="mt-2 space-y-1.5 text-[12.5px] text-blue-700">
                        <li class="flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                            Registro de despesas operacionais
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                            Categorização por tipo de gasto
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                            Relatórios de fluxo de caixa
                        </li>
                        <li class="flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                            Balanço receitas x despesas
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <a href="{{ route('app.financeiro.index') }}"
           class="mt-8 inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-[13px] font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            ← Voltar ao Financeiro
        </a>
    </div>
</div>

@endsection
