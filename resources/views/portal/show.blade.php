@extends('layouts.portal')
@section('title', 'OS ' . $ordem->codigo_publico)
@section('transparent-nav', '1')

@section('content')
@php
    $primeiroNome  = explode(' ', $cliente->nome ?? 'Cliente')[0];
    $equipamento   = $ordem->equipamento;
    $tecnico       = $ordem->tecnico;
    $arquivos      = $ordem->arquivos()->latest()->get();

    $statusConfig = \App\Models\Ordem::STATUS[$ordem->status] ?? ['label' => $ordem->status, 'color' => 'default'];

    $heroBadgeClass = match($statusConfig['color']) {
        'success' => 'bg-green-500/20 text-green-400 ring-1 ring-green-500/30',
        'warning' => 'bg-amber-500/20 text-amber-400 ring-1 ring-amber-500/30',
        'info'    => 'bg-blue-500/20 text-blue-400 ring-1 ring-blue-500/30',
        'danger'  => 'bg-red-500/20 text-red-400 ring-1 ring-red-500/30',
        'purple'  => 'bg-purple-500/20 text-purple-400 ring-1 ring-purple-500/30',
        'primary' => 'bg-blue-500/20 text-blue-400 ring-1 ring-blue-500/30',
        default   => 'bg-slate-500/20 text-slate-400 ring-1 ring-slate-500/30',
    };

    // Mapeia historico por status para pegar a data de cada etapa
    $historicoMap = $ordem->historico->keyBy('status_novo');

    // Progresso geral (etapas concluídas / total)
    $totalSteps  = count($steps);
    $pctProgresso = $isCancelled ? 0 : (int) round((min($currentStep + ($isFinished ? 1 : 0), $totalSteps) / $totalSteps) * 100);
@endphp

{{-- ───── Hero ───── --}}
<div class="relative overflow-hidden bg-[#0d1117]">
    {{-- Imagem de fundo + overlays --}}
    <div class="absolute inset-0 bg-cover bg-center"
         style="background-image:url('https://thumbs.dreamstime.com/b/processo-de-reparo-do-dispositivo-da-tabuleta-do-pc-perto-da-chave-de-fenda-e-mordido-no-fundo-de-madeira-preto-desmontado-o-vidro-82189381.jpg?w=992')"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-[#0d1117] via-[#0d1117]/90 to-[#0d1117]/55"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#0d1117] via-transparent to-transparent"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 pt-28 pb-14 sm:pt-32 sm:pb-16">
        <div class="flex flex-col md:flex-row md:items-start gap-10">

            {{-- Saudação --}}
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/15 px-3.5 py-1.5 text-[11px] font-semibold text-slate-300 uppercase tracking-widest mb-5">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Portal do Cliente
                </div>
                <h1 class="text-3xl sm:text-[40px] font-bold text-white leading-tight tracking-tight">
                    Olá, {{ $primeiroNome }}! <span>👋</span>
                </h1>
                <p class="mt-3 max-w-md text-slate-300 text-[15px] leading-relaxed">
                    @if($isFinished)
                        Seu equipamento está pronto para retirada! 🎉
                        Aguardamos você em nosso horário de atendimento.
                    @elseif($isCancelled)
                        Esta ordem de serviço foi cancelada.
                        Em caso de dúvidas, fale com a nossa equipe.
                    @else
                        Acompanhe em tempo real cada etapa do reparo do seu equipamento,
                        do diagnóstico à entrega.
                    @endif
                </p>

                {{-- Trust chips --}}
                <div class="mt-6 flex flex-wrap items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/5 backdrop-blur-sm border border-white/10 px-3 py-1.5 text-[12px] font-medium text-slate-300">
                        <svg class="h-3.5 w-3.5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        Status em tempo real
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/5 backdrop-blur-sm border border-white/10 px-3 py-1.5 text-[12px] font-medium text-slate-300">
                        <svg class="h-3.5 w-3.5 text-[#22c55e]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        </svg>
                        Avisos por WhatsApp
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/5 backdrop-blur-sm border border-white/10 px-3 py-1.5 text-[12px] font-medium text-slate-300">
                        <svg class="h-3.5 w-3.5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0 1 12 2.944a11.955 11.955 0 0 1-8.618 3.04A12.02 12.02 0 0 0 3 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Garantia de serviço
                    </span>
                </div>

                @if(session()->has('portal_cliente_id'))
                <a href="{{ route('portal.index') }}"
                   class="inline-flex items-center gap-1.5 mt-7 text-[13px] font-medium text-slate-400 hover:text-white transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    Ver todas as minhas OS
                </a>
                @endif
            </div>

            {{-- Card da OS (vidro, compacto) --}}
            <div class="relative w-full md:w-auto md:min-w-[480px] lg:min-w-[540px] shrink-0 md:mt-9">
                {{-- Glow decorativo atrás do card --}}
                <div class="absolute -inset-1.5 rounded-[22px] bg-gradient-to-br {{ $isFinished ? 'from-emerald-500/40 via-emerald-400/10' : 'from-blue-500/40 via-cyan-400/10' }} to-transparent blur-xl"></div>

                <div class="relative overflow-hidden rounded-2xl bg-white/[0.07] backdrop-blur-xl border border-white/15 shadow-2xl shadow-black/50 px-6 py-5 sm:px-8 sm:py-7">
                    <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-3">
                        <div class="flex items-center gap-4 min-w-0">
                            {{-- Ícone circular --}}
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full {{ $isFinished ? 'bg-emerald-500/15' : ($isCancelled ? 'bg-red-500/15' : 'bg-blue-500/15') }}">
                                <svg class="h-6 w-6 {{ $isFinished ? 'text-emerald-400' : ($isCancelled ? 'text-red-400' : 'text-blue-400') }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    @if($isFinished)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                    @elseif($isCancelled)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.344-3.071a9 9 0 1 1-18.688 0 9 9 0 0 1 18.688 0zM12 15.75h.008v.008H12v-.008z"/>
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                    @endif
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[28px] font-bold text-white leading-none font-mono tracking-tight">{{ $ordem->codigo_publico }}</p>
                                <p class="mt-1.5 truncate text-[13px] text-slate-400">
                                    Ordem de serviço · entrada {{ $ordem->created_at->format('d/m/Y') }}
                                    @if($ordem->previsao_entrega && !$isFinished && !$isCancelled)
                                        · previsão <span class="font-semibold text-blue-300">{{ $ordem->previsao_entrega->format('d/m/Y') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1.5 text-[11.5px] font-bold {{ $heroBadgeClass }}">
                            <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-current {{ !$isFinished && !$isCancelled ? 'animate-pulse' : '' }}"></span>
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ───── Flash: resposta ao orçamento ───── --}}
@if(session('orcamento_resposta'))
@php $resp = session('orcamento_resposta'); @endphp
<div class="border-b {{ $resp['status'] === 'aprovado' ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50' }}">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 py-3 flex items-center gap-3">
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $resp['status'] === 'aprovado' ? 'bg-emerald-500' : 'bg-red-500' }}">
            <svg class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                @if($resp['status'] === 'aprovado')
                <polyline points="20 6 9 17 4 12"/>
                @else
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                @endif
            </svg>
        </div>
        <p class="text-[13.5px] font-semibold {{ $resp['status'] === 'aprovado' ? 'text-emerald-800' : 'text-red-800' }}">
            {{ $resp['msg'] }}
        </p>
    </div>
</div>
@endif

{{-- ───── Conteúdo principal ───── --}}
<div class="mx-auto max-w-7xl px-4 sm:px-6 py-8">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_380px]">

        {{-- ═══════════════════════════════ COLUNA ESQUERDA ═══════════════════════════════ --}}
        <div class="space-y-5">

            {{-- ── Timeline ── --}}
            <div id="portal-os-progresso" data-live-refresh="15" class="rounded-2xl bg-white border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-[15px] font-bold text-slate-800">Acompanhe o andamento</h2>
                    @unless($isCancelled)
                    <span class="text-[12px] font-bold {{ $isFinished ? 'text-emerald-600' : 'text-blue-600' }}">{{ $pctProgresso }}% concluído</span>
                    @endunless
                </div>
                @if($isCancelled)
                <div class="mx-6 mt-5 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-500">
                        <svg class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-red-800">Ordem de serviço cancelada</p>
                        <p class="text-[12px] text-red-600">O andamento abaixo reflete as etapas até o cancelamento.</p>
                    </div>
                </div>
                @endif
                <div class="px-6 py-5 space-y-0">
                    @foreach($steps as $i => $step)
                    @php
                        $done    = $i < $currentStep;
                        $active  = $i === $currentStep;
                        $pending = $i > $currentStep;

                        $hist    = $historicoMap->get($step['key']);
                        $data    = $hist?->created_at;
                        $isLast  = $i === count($steps) - 1;
                    @endphp

                    <div class="flex gap-4 {{ !$isLast ? 'pb-5' : '' }}">
                        {{-- Linha vertical + ícone --}}
                        <div class="flex flex-col items-center">
                            @if($done)
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-500 shadow-sm shadow-green-200">
                                <svg class="h-[18px] w-[18px] text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>
                            @elseif($active)
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-500 shadow-sm shadow-amber-200 ring-4 ring-amber-100">
                                <svg class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                </svg>
                            </div>
                            @else
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 border border-slate-200">
                                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                    <path d="M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v0a2 2 0 0 1-2 2H11a2 2 0 0 1-2-2z"/>
                                </svg>
                            </div>
                            @endif

                            @if(!$isLast)
                            <div class="mt-1 w-0.5 flex-1 {{ $done ? 'bg-green-300' : 'bg-slate-200' }}"></div>
                            @endif
                        </div>

                        {{-- Texto --}}
                        <div class="flex-1 min-w-0 pb-1">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-[13.5px] font-semibold {{ $done ? 'text-slate-700' : ($active ? 'text-slate-900' : 'text-slate-400') }}">
                                        {{ $step['label'] }}
                                    </p>
                                    <p class="text-[12.5px] {{ $done ? 'text-slate-500' : ($active ? 'text-slate-600' : 'text-slate-400') }} mt-0.5 leading-relaxed">
                                        {{ $step['desc'] }}
                                    </p>
                                </div>
                                @if($data)
                                <div class="text-right shrink-0">
                                    <p class="text-[11.5px] text-slate-500">{{ $data->format('d/m/Y') }}</p>
                                    <p class="text-[11.5px] text-slate-400">{{ $data->format('H:i') }}</p>
                                </div>
                                @elseif($active)
                                <span class="shrink-0 text-[11px] font-semibold text-amber-600 bg-amber-50 border border-amber-200 rounded-full px-2.5 py-0.5">
                                    Em andamento
                                </span>
                                @else
                                <span class="shrink-0 text-[11.5px] text-slate-300">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($ordem->historico->isNotEmpty())
                <div class="px-6 pb-5">
                    <button
                        onclick="this.closest('.rounded-2xl').querySelector('[data-hist]').classList.toggle('hidden')"
                        class="flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-[12.5px] font-medium text-slate-600 hover:bg-slate-50 transition-colors w-full justify-center">
                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                            <path d="M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 0 2-2h2a2 2 0 0 0 2 2"/>
                        </svg>
                        Ver histórico completo
                    </button>
                    <div data-hist class="hidden mt-3 space-y-2">
                        @foreach($ordem->historico as $h)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-2.5 text-[12px]">
                            <div class="flex items-center gap-2 text-slate-600">
                                <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="5 12 3 12 12 3 21 12 19 12"/>
                                    <polyline points="19 12 19 21 13 21 13 15 11 15 11 21 5 21 5 12"/>
                                </svg>
                                <span class="font-medium">{{ \App\Models\Ordem::STATUS[$h->status_anterior]['label'] ?? $h->status_anterior }}</span>
                                <svg class="h-3 w-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                                <span class="font-semibold text-slate-800">{{ \App\Models\Ordem::STATUS[$h->status_novo]['label'] ?? $h->status_novo }}</span>
                            </div>
                            <span class="text-slate-400">{{ $h->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- ── Orçamento ── --}}
            @if($ordem->valor_servico > 0 || $ordem->valor_pecas > 0)
            @php
                $orcPendente = $ordem->status_orcamento === 'pendente';
                $orcAprovado = $ordem->status_orcamento === 'aprovado';
                $orcRecusado = $ordem->status_orcamento === 'recusado';
                $totalOrc    = ($ordem->valor_servico ?? 0) + ($ordem->valor_pecas ?? 0) - ($ordem->desconto ?? 0);
            @endphp

            {{-- Banner de ação quando pendente --}}
            @if($orcPendente)
            <div x-data="{ confirmar: null }" class="rounded-2xl border-2 border-amber-300 bg-amber-50 overflow-hidden shadow-sm">

                {{-- Cabeçalho de alerta --}}
                <div class="flex items-center gap-3 bg-amber-400/20 px-6 py-4 border-b border-amber-200">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-500 shadow-sm">
                        <svg class="h-[18px] w-[18px] text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[14px] font-bold text-amber-900">Orçamento aguardando sua aprovação</p>
                        <p class="text-[12px] text-amber-700">Revise os valores abaixo e responda para que possamos prosseguir.</p>
                    </div>
                </div>

                {{-- Valores --}}
                <div class="px-6 py-4">
                    <div class="space-y-2 mb-4">
                        @if($ordem->valor_servico > 0)
                        <div class="flex items-center justify-between text-[13.5px]">
                            <span class="text-slate-600">Mão de obra</span>
                            <span class="font-medium text-slate-800">R$ {{ number_format($ordem->valor_servico, 2, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($ordem->valor_pecas > 0)
                        <div class="flex items-center justify-between text-[13.5px]">
                            <span class="text-slate-600">Peças e materiais</span>
                            <span class="font-medium text-slate-800">R$ {{ number_format($ordem->valor_pecas, 2, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($ordem->desconto > 0)
                        <div class="flex items-center justify-between text-[13.5px]">
                            <span class="text-emerald-700">Desconto</span>
                            <span class="font-medium text-emerald-700">- R$ {{ number_format($ordem->desconto, 2, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between border-t border-amber-200 pt-3 mt-3">
                            <span class="text-[15px] font-bold text-slate-800">Total</span>
                            <span class="text-[22px] font-black text-slate-900">R$ {{ number_format($totalOrc, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($ordem->diagnostico)
                    <div class="mb-4 rounded-xl bg-white border border-amber-200 px-4 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1">Diagnóstico técnico</p>
                        <p class="text-[13px] text-slate-700 leading-relaxed">{{ $ordem->diagnostico }}</p>
                    </div>
                    @endif

                    {{-- Botões de ação --}}
                    @if($isStaffPreview ?? false)
                    <div class="flex items-start gap-2.5 rounded-xl border border-slate-200 bg-white px-4 py-3 text-[12.5px] text-slate-500">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                        <span><strong class="font-semibold text-slate-700">Visualização da equipe.</strong> A aprovação ou recusa do orçamento só pode ser feita pelo cliente, no portal dele.</span>
                    </div>
                    @else
                    <div class="flex flex-col sm:flex-row gap-2.5">
                        <button type="button"
                            @click="confirmar = 'aprovado'"
                            class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-3 text-[13.5px] font-bold text-white transition-colors shadow-sm shadow-emerald-200">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            Aprovar orçamento
                        </button>
                        <button type="button"
                            @click="confirmar = 'recusado'"
                            class="flex flex-1 items-center justify-center gap-2 rounded-xl border-2 border-slate-300 bg-white hover:bg-slate-50 px-5 py-3 text-[13.5px] font-semibold text-slate-600 transition-colors">
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Recusar orçamento
                        </button>
                    </div>
                    @endif
                </div>

                {{-- Modal de confirmação --}}
                <div x-show="confirmar !== null"
                     x-cloak
                     class="fixed inset-0 z-50 flex items-center justify-center p-4"
                     style="background:rgba(0,0,0,0.5)">
                    <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl" @click.stop>

                        <template x-if="confirmar === 'aprovado'">
                            <div>
                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 mx-auto">
                                    <svg class="h-6 w-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <p class="text-center text-[16px] font-bold text-slate-800">Confirmar aprovação?</p>
                                <p class="mt-1.5 text-center text-[13px] text-slate-500 leading-relaxed">
                                    Ao aprovar, nossa equipe iniciará o reparo do equipamento. O valor total é <strong>R$ {{ number_format($totalOrc, 2, ',', '.') }}</strong>.
                                </p>
                                <div class="mt-5 flex gap-2.5">
                                    <button @click="confirmar = null" class="flex-1 rounded-xl border border-slate-200 bg-white py-2.5 text-[13px] font-semibold text-slate-600 hover:bg-slate-50 transition">
                                        Cancelar
                                    </button>
                                    <form action="{{ route('portal.os.orcamento', $ordem) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="resposta" value="aprovado">
                                        <button type="submit" class="w-full rounded-xl bg-emerald-600 py-2.5 text-[13px] font-bold text-white hover:bg-emerald-700 transition">
                                            Sim, aprovar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </template>

                        <template x-if="confirmar === 'recusado'">
                            <div>
                                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mx-auto">
                                    <svg class="h-6 w-6 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </div>
                                <p class="text-center text-[16px] font-bold text-slate-800">Recusar orçamento?</p>
                                <p class="mt-1.5 text-center text-[13px] text-slate-500 leading-relaxed">
                                    Ao recusar, nossa equipe entrará em contato para discutir os próximos passos sobre o seu equipamento.
                                </p>
                                <div class="mt-5 flex gap-2.5">
                                    <button @click="confirmar = null" class="flex-1 rounded-xl border border-slate-200 bg-white py-2.5 text-[13px] font-semibold text-slate-600 hover:bg-slate-50 transition">
                                        Voltar
                                    </button>
                                    <form action="{{ route('portal.os.orcamento', $ordem) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="resposta" value="recusado">
                                        <button type="submit" class="w-full rounded-xl bg-red-600 py-2.5 text-[13px] font-bold text-white hover:bg-red-700 transition">
                                            Sim, recusar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </template>

                    </div>
                </div>
            </div>
            @else
            {{-- Orçamento já respondido — só exibe o resumo --}}
            <div class="rounded-2xl bg-white border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100">
                            <svg class="h-4 w-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                            </svg>
                        </div>
                        <h2 class="text-[15px] font-bold text-slate-800">Orçamento</h2>
                    </div>
                    @php
                        $orcBadge = match($ordem->status_orcamento) {
                            'aprovado' => ['label' => 'Aprovado', 'class' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200'],
                            'recusado' => ['label' => 'Recusado', 'class' => 'bg-red-50 text-red-700 ring-1 ring-red-200'],
                            default    => ['label' => 'Pendente', 'class' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'],
                        };
                    @endphp
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-[11.5px] font-semibold {{ $orcBadge['class'] }}">
                        {{ $orcBadge['label'] }}
                    </span>
                </div>
                <div class="px-6 py-4 space-y-2">
                    @if($ordem->valor_servico > 0)
                    <div class="flex justify-between text-[13.5px]">
                        <span class="text-slate-600">Mão de obra</span>
                        <span class="font-medium text-slate-800">R$ {{ number_format($ordem->valor_servico, 2, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($ordem->valor_pecas > 0)
                    <div class="flex justify-between text-[13.5px]">
                        <span class="text-slate-600">Peças</span>
                        <span class="font-medium text-slate-800">R$ {{ number_format($ordem->valor_pecas, 2, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($ordem->desconto > 0)
                    <div class="flex justify-between text-[13.5px]">
                        <span class="text-emerald-700">Desconto</span>
                        <span class="font-medium text-emerald-700">- R$ {{ number_format($ordem->desconto, 2, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between border-t border-slate-100 pt-3">
                        <span class="text-[14px] font-bold text-slate-800">Total</span>
                        <span class="text-[18px] font-bold text-slate-900">R$ {{ number_format($totalOrc, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endif
            @endif

            {{-- ── Fotos e documentos ── --}}
            @if($arquivos->isNotEmpty())
            <div class="rounded-2xl bg-white border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100">
                            <svg class="h-4 w-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                        <h2 class="text-[15px] font-bold text-slate-800">Fotos e documentos</h2>
                    </div>
                    <span class="text-[12px] text-slate-400">{{ $arquivos->count() }} arquivo(s)</span>
                </div>
                <div class="p-5 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($arquivos as $arquivo)
                    @php $tipo = \App\Models\OrdemArquivo::TIPOS[$arquivo->tipo] ?? ['label' => 'Arquivo', 'icon' => 'document']; @endphp
                    <a href="{{ route('portal.os.arquivo', [$ordem, $arquivo]) }}" target="_blank" rel="noopener"
                       class="group relative block overflow-hidden rounded-xl border border-slate-200 bg-slate-50 hover:border-blue-300 hover:shadow-md transition-all">
                        @if($arquivo->isImagem())
                        <div class="aspect-square overflow-hidden">
                            <img src="{{ route('portal.os.arquivo', [$ordem, $arquivo]) }}"
                                 alt="{{ $arquivo->nome_original }}" loading="lazy"
                                 class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </div>
                        <div class="pointer-events-none absolute inset-0 flex items-center justify-center bg-slate-900/0 transition group-hover:bg-slate-900/20">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/90 opacity-0 shadow-md transition group-hover:opacity-100">
                                <svg class="h-4 w-4 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3M11 8v6M8 11h6"/>
                                </svg>
                            </span>
                        </div>
                        @else
                        <div class="flex aspect-square items-center justify-center">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100">
                                <span class="text-[11px] font-bold text-red-600 uppercase">{{ pathinfo($arquivo->nome_original, PATHINFO_EXTENSION) ?: 'PDF' }}</span>
                            </div>
                        </div>
                        @endif
                        <div class="px-2.5 pb-2.5 pt-2">
                            <p class="text-[11.5px] font-semibold text-slate-700 truncate">{{ $tipo['label'] }}</p>
                            <p class="text-[10.5px] text-slate-400">{{ $arquivo->created_at->format('d/m/Y · H:i') }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- ═══════════════════════════════ COLUNA DIREITA ═══════════════════════════════ --}}
        <div class="space-y-4">

            {{-- ── Equipamento ── --}}
            @if($equipamento)
            <div class="rounded-2xl bg-white border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-[13.5px] font-bold text-slate-800">Informações do equipamento</h3>
                </div>
                <div class="p-5 flex gap-4">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-slate-100 border border-slate-200">
                        <svg class="h-8 w-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="2" y="3" width="20" height="14" rx="2"/>
                            <path d="M8 21h8M12 17v4"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[14px] font-bold text-slate-800">
                            {{ $equipamento->marca }} {{ $equipamento->modelo }}
                        </p>
                        @if($equipamento->numero_serie)
                        <p class="text-[12px] text-slate-500">Nº de Série: {{ $equipamento->numero_serie }}</p>
                        @endif
                        @if($equipamento->tipo)
                        <p class="text-[12px] text-slate-500">Tipo: {{ $equipamento->tipo }}</p>
                        @endif
                        @if($equipamento->condicao_entrada)
                        <p class="text-[12px] text-slate-500">Condição: {{ $equipamento->condicao_entrada }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- ── Defeito relatado ── --}}
            @if($ordem->problema_relatado)
            <div class="rounded-2xl bg-white border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100">
                        <svg class="h-3.5 w-3.5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-[13.5px] font-bold text-slate-800">Defeito relatado</h3>
                </div>
                <div class="px-5 py-4">
                    <p class="text-[13px] text-slate-600 leading-relaxed">{{ $ordem->problema_relatado }}</p>
                </div>
            </div>
            @endif

            {{-- ── Previsão de entrega ── --}}
            @if($ordem->previsao_entrega)
            <div class="rounded-2xl bg-white border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100">
                        <svg class="h-3.5 w-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <h3 class="text-[13.5px] font-bold text-slate-800">Previsão de entrega</h3>
                </div>
                <div class="px-5 py-4">
                    <p class="text-[24px] font-bold text-blue-600">
                        {{ $ordem->previsao_entrega->format('d/m/Y') }}
                    </p>
                    <p class="text-[12.5px] text-slate-400 mt-0.5">
                        {{ ucfirst(\Carbon\Carbon::parse($ordem->previsao_entrega)->locale('pt_BR')->dayName) }}
                    </p>
                    <p class="text-[11px] text-slate-400 mt-2">*Previsão sujeita a alterações</p>
                </div>
            </div>
            @endif

            {{-- ── Técnico responsável ── --}}
            @if($tecnico)
            <div class="rounded-2xl bg-white border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-[13.5px] font-bold text-slate-800">Técnico responsável</h3>
                </div>
                <div class="px-5 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-[13px] font-bold text-white">
                            {{ strtoupper(substr($tecnico->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-[13.5px] font-semibold text-slate-800">{{ $tecnico->name }}</p>
                            <p class="text-[12px] text-slate-400">Técnico em informática</p>
                        </div>
                    </div>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 border border-blue-100">
                        <svg class="h-4 w-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0 1 12 2.944a11.955 11.955 0 0 1-8.618 3.04A12.02 12.02 0 0 0 3 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── Precisa de ajuda? ── --}}
            @if($waUrl)
            <div class="rounded-2xl bg-white border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-[13.5px] font-bold text-slate-800">Precisa de ajuda?</h3>
                    <p class="text-[12px] text-slate-500 mt-0.5">Fale com nossa equipe diretamente pelo WhatsApp.</p>
                </div>
                <div class="px-5 py-4">
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                       class="flex items-center justify-center gap-2.5 w-full rounded-xl bg-[#22c55e] hover:bg-[#16a34a] px-5 py-3 text-[13.5px] font-semibold text-white transition-colors shadow-sm shadow-green-200">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        </svg>
                        Conversar no WhatsApp
                    </a>
                </div>
            </div>
            @endif


        </div>{{-- /col-right --}}
    </div>{{-- /grid --}}
</div>

@endsection
