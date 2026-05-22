<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OS {{ $ordem->numero }} — Entrada de Equipamento</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { font-size: 8.5px; }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #111;
            background: #dde1e8;
            line-height: 1.35;
        }

        /* ─── Toolbar ────────────────────────────────────────────────────────── */
        .toolbar {
            position: sticky; top: 0; z-index: 100;
            background: #0f172a; color: #fff;
            padding: 9px 24px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,.4);
        }
        .tl { display: flex; align-items: center; gap: 10px; }
        .tt { font-size: 12px; font-weight: 600; }
        .ts { font-size: 10px; color: #64748b; margin-top: 1px; }
        .ta { display: flex; gap: 8px; }
        .btn-back {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 13px; border: 1px solid #334155; border-radius: 5px;
            font-size: 11px; color: #94a3b8; text-decoration: none;
        }
        .btn-back:hover { background: #1e293b; }
        .btn-print {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 16px; background: #2563eb; border: none; border-radius: 5px;
            font-size: 11px; font-weight: 600; color: #fff; cursor: pointer;
        }
        .btn-print:hover { background: #1d4ed8; }

        /* ─── Page wrapper ───────────────────────────────────────────────────── */
        .page-wrap { display: flex; justify-content: center; padding: 20px 12px 40px; }

        /* ─── A4 sheet ───────────────────────────────────────────────────────── */
        .sheet {
            width: 210mm; height: 297mm;
            background: #fff;
            box-shadow: 0 2px 20px rgba(0,0,0,.18);
            display: flex; flex-direction: column;
            overflow: hidden;
        }

        /* ─── Via (meia folha) ───────────────────────────────────────────────── */
        .via {
            flex: 1;
            padding: 5mm 8.5mm 4mm;
            display: flex; flex-direction: column;
        }

        /* ─── Linha de corte ─────────────────────────────────────────────────── */
        .cut {
            flex-shrink: 0; height: 7mm;
            display: flex; align-items: center; gap: 8px;
            padding: 0 8.5mm;
            font-size: 6.5px; letter-spacing: .06em; color: #c0c8d5;
        }
        .cut::before, .cut::after { content: ''; flex: 1; border-top: 1px dashed #c0c8d5; }

        /* ─── Cabeçalho ──────────────────────────────────────────────────────── */
        .hd {
            display: flex; align-items: flex-start; justify-content: space-between;
            padding-bottom: 3.5px;
            border-bottom: 1.5px solid #111;
            margin-bottom: 3px;
        }
        .hd-l { display: flex; align-items: center; gap: 7px; }
        .hd-l img { width: 21mm; height: auto; display: block; flex-shrink: 0; }
        .hd-company { font-size: 7.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #1e293b; }
        .hd-info { font-size: 6.5px; color: #64748b; margin-top: 1.5px; }
        .hd-r { text-align: right; flex-shrink: 0; }
        .badge {
            display: inline-block; padding: 1.5px 6px; border-radius: 2px;
            font-size: 6.5px; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; color: #fff;
        }
        .badge.cliente { background: #1e3a8a; }
        .badge.empresa { background: #7f1d1d; }
        .hd-os { font-size: 12px; font-weight: 900; color: #0f172a; letter-spacing: -.02em; margin-top: 2px; line-height: 1; }
        .hd-date { font-size: 6.5px; color: #64748b; margin-top: 2px; }
        .hd-date b { color: #374151; font-weight: 700; }

        /* ─── Label de seção ─────────────────────────────────────────────────── */
        .sec-label {
            font-size: 6px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .08em; color: #94a3b8; margin-bottom: 1.5px;
            display: block;
        }

        /* ─── Grid de dados (tabela) ─────────────────────────────────────────── */
        /* Usamos table para garantir bordas perfeitas independente do conteúdo */
        .dg {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            border-radius: 3px; /* apenas visual no overflow da div pai */
        }
        .dg td {
            border: 1px solid #e2e8f0;
            padding: 2px 5px;
            vertical-align: top;
        }
        .dl { /* data label */
            display: block;
            font-size: 6px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .06em; color: #94a3b8; margin-bottom: 1px;
        }
        .dv { /* data value */
            font-size: 8.5px; color: #111827;
        }

        /* ─── Caixa de texto (queixa) ────────────────────────────────────────── */
        .text-box {
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            padding: 3px 5px;
            min-height: 16mm;
            font-size: 8.5px; color: #111827;
            white-space: pre-wrap; word-break: break-word;
        }

        /* ─── Portal ─────────────────────────────────────────────────────────── */
        .portal {
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            display: flex; overflow: hidden;
            background: #f8fafc;
        }
        .portal-cell { padding: 2px 6px; flex: 1; }
        .portal-cell + .portal-cell { border-left: 1px solid #e2e8f0; }
        .pl { font-size: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #94a3b8; display: block; }
        .pv { font-size: 8.5px; font-weight: 700; color: #1d4ed8; font-family: 'Courier New', monospace; letter-spacing: .07em; }
        .pu { font-size: 7px; color: #64748b; font-family: 'Courier New', monospace; }

        /* ─── Checkboxes de andamento (via empresa) ──────────────────────────── */
        .progress {
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            padding: 3px 6px 4px;
        }
        .progress-head {
            display: flex; justify-content: space-between; align-items: baseline;
            margin-bottom: 4px;
        }
        .progress-note { font-size: 6px; color: #c0c8d5; font-style: italic; }
        .progress-steps { display: flex; gap: 0; }
        .progress-step {
            flex: 1; display: flex; align-items: center; gap: 3px;
        }
        .progress-step + .progress-step {
            padding-left: 6px;
            border-left: 1px solid #e2e8f0;
        }
        .cb {
            width: 9px; height: 9px; flex-shrink: 0;
            border: 1px solid #94a3b8;
            border-radius: 1.5px;
            background: #fff;
        }
        .cb-text { font-size: 7px; font-weight: 600; color: #374151; white-space: nowrap; }

        /* ─── Condições ──────────────────────────────────────────────────────── */
        .conditions {
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            padding: 3px 5px;
        }
        .conditions p { font-size: 6.8px; line-height: 1.35; color: #374151; }
        .conditions p + p { margin-top: 1.5px; }

        /* ─── Termo ──────────────────────────────────────────────────────────── */
        .termo {
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            padding: 3px 5px;
        }
        .termo p { font-size: 7px; line-height: 1.4; color: #374151; text-align: justify; }

        /* ─── Espaçador ──────────────────────────────────────────────────────── */
        .spacer { flex: 1; min-height: 2mm; }

        /* ─── Assinaturas ────────────────────────────────────────────────────── */
        .sigs { display: flex; gap: 10mm; padding-top: 3px; }
        .sig { flex: 1; }
        .sig-label { font-size: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #94a3b8; display: block; margin-bottom: 9px; }
        .sig-line { border-top: 1px solid #374151; margin-bottom: 2px; }
        .sig-name { font-size: 8px; font-weight: 600; color: #0f172a; }
        .sig-cpf { font-size: 6.5px; color: #64748b; }
        .sig-date { font-size: 6.5px; color: #9ca3af; margin-top: 2px; }

        /* ─── Separação entre seções ─────────────────────────────────────────── */
        .via > * + * { margin-top: 2.5px; }
        /* exceto o spacer e sigs que ficam no fim */

        /* ─── Print ──────────────────────────────────────────────────────────── */
        @media print {
            @page { size: A4 portrait; margin: 0; }
            html { font-size: 8.5px; }
            body { background: #fff; }
            .toolbar { display: none !important; }
            .page-wrap { padding: 0; }
            .sheet { box-shadow: none; }

            .dg, .dg td      { border-color: #bbb !important; }
            .text-box        { border-color: #bbb !important; }
            .portal          { border-color: #bbb !important; }
            .portal-cell     { border-color: #bbb !important; }
            .progress        { border-color: #bbb !important; }
            .progress-step   { border-color: #bbb !important; }
            .cb              { border-color: #555 !important; }
            .conditions      { border-color: #bbb !important; }
            .termo           { border-color: #bbb !important; }
            .hd              { border-bottom-color: #000 !important; }
        }
    </style>
</head>
<body>

@php
    $empresa = [
        'nome'     => 'CARLOS A DE S BARBALHO TECNOLOGIA',
        'endereco' => 'Rua dos Martírios, 13A, Centro – Goiana-Pe',
        'telefone' => '(81) 99359-6484 (Apenas Whatsapp)',
    ];

    $equip = collect([
        $ordem->equipamento?->tipo,
        $ordem->equipamento?->marca,
        $ordem->equipamento?->modelo,
    ])->filter()->join(' ') ?: '–';

    $endereco = collect([
        $ordem->cliente?->endereco,
        ($ordem->cliente?->cidade && $ordem->cliente?->estado)
            ? "{$ordem->cliente->cidade} – {$ordem->cliente->estado}"
            : ($ordem->cliente?->cidade ?? $ordem->cliente?->estado ?? null),
        $ordem->cliente?->cep ? 'CEP ' . $ordem->cliente->cep : null,
    ])->filter()->join(', ');

    $conditions = [
        "A empresa {$empresa['nome']} não se responsabilizará por perdas de dados gravados em HD e/ou softwares instalados. Fica sob responsabilidade do cliente qualquer cópia de segurança (backup).",
        "Os serviços executados têm garantia de 15 dias a partir da entrega. Problema causado por mau uso invalida a garantia.",
        "Equipamentos em garantia de venda só estarão livres de taxas mediante apresentação de nota fiscal.",
        "Após 60 dias do orçamento apresentado, o cliente sujeita-se a taxa de armazenagem de R\$5,00/dia caso não retire o equipamento.",
        "Decorrido 90 dias após o orçamento, a {$empresa['nome']} fica autorizada a alienar o equipamento para ressarcimento dos custos.",
    ];

    $steps = ['Entrada', 'Análise', 'Execução', 'Aguardando', 'Em teste', 'Finalizado'];

    $vias = [
        ['key' => 'cliente', 'label' => 'Via do Cliente'],
        ['key' => 'empresa', 'label' => 'Via da Empresa'],
    ];
@endphp

{{-- Toolbar --}}
<div class="toolbar">
    <div class="tl">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" style="flex-shrink:0"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
        <div>
            <div class="tt">Entrada de Equipamento — OS {{ $ordem->numero }}</div>
            <div class="ts">{{ $ordem->cliente?->nome }} · {{ $ordem->created_at->format('d/m/Y') }} · Duas vias</div>
        </div>
    </div>
    <div class="ta">
        <a href="{{ route('app.os.show', $ordem) }}" class="btn-back">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m12 19-7-7 7-7M19 12H5"/></svg>
            Voltar
        </a>
        <button onclick="window.print()" class="btn-print">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
            Imprimir / Salvar PDF
        </button>
    </div>
</div>

<div class="page-wrap">
<div class="sheet">

@foreach($vias as $i => $via)
<div class="via">

    {{-- Cabeçalho --}}
    <div class="hd">
        <div class="hd-l">
            <img src="{{ asset('images/futuredata.png') }}" alt="FutureData">
            <div>
                <div class="hd-company">{{ $empresa['nome'] }}</div>
                <div class="hd-info">{{ $empresa['endereco'] }}</div>
                <div class="hd-info">{{ $empresa['telefone'] }}</div>
            </div>
        </div>
        <div class="hd-r">
            <span class="badge {{ $via['key'] }}">{{ $via['label'] }}</span>
            <div class="hd-os">OS {{ $ordem->numero }}</div>
            <div class="hd-date">Abertura: <b>{{ $ordem->created_at->format('d/m/Y') }}</b>
                @if($ordem->previsao_entrega)
                &nbsp;·&nbsp; Previsão: <b>{{ $ordem->previsao_entrega->format('d/m/Y') }}</b>
                @endif
            </div>
        </div>
    </div>

    {{-- Cliente --}}
    <div style="overflow:hidden; border-radius:3px;">
        <table class="dg">
            <tr>
                <td style="width:52%">
                    <span class="dl">Cliente</span>
                    <span class="dv">{{ $ordem->cliente?->nome ?? '—' }}</span>
                </td>
                <td>
                    <span class="dl">Telefone</span>
                    <span class="dv">{{ $ordem->cliente?->telefone ?? '—' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="dl">CPF / CNPJ</span>
                    <span class="dv">{{ $ordem->cliente?->cpf_cnpj ?? '—' }}</span>
                </td>
                <td>
                    <span class="dl">Endereço</span>
                    <span class="dv">{{ $endereco ?: '—' }}</span>
                </td>
            </tr>
        </table>
    </div>

    {{-- Equipamento --}}
    <div style="overflow:hidden; border-radius:3px;">
        <table class="dg">
            <tr>
                <td style="width:46%">
                    <span class="dl">Equipamento (tipo / marca / modelo)</span>
                    <span class="dv">{{ $equip }}</span>
                </td>
                <td style="width:28%">
                    <span class="dl">Nº de Série</span>
                    <span class="dv">{{ $ordem->equipamento?->numero_serie ?: '—' }}</span>
                </td>
                <td style="width:15%">
                    <span class="dl">Patrimônio</span>
                    <span class="dv">{{ $ordem->equipamento?->patrimonio ?: '—' }}</span>
                </td>
                <td style="width:11%">
                    <span class="dl">Garantia</span>
                    <span class="dv">{{ $ordem->equipamento?->em_garantia ? 'Sim' : 'Não' }}</span>
                </td>
            </tr>
            @if($ordem->equipamento?->acessorios)
            <tr>
                <td colspan="4">
                    <span class="dl">Acessórios / Itens inclusos</span>
                    <span class="dv">{{ $ordem->equipamento->acessorios }}</span>
                </td>
            </tr>
            @endif
        </table>
    </div>

    {{-- Queixa --}}
    <div>
        <span class="sec-label">Defeito / Queixa relatada pelo cliente</span>
        <div class="text-box">{{ $ordem->problema_relatado }}</div>
    </div>

    {{-- Portal --}}
    @if($ordem->token)
    <div class="portal">
        <div class="portal-cell">
            <span class="pl">Código de acompanhamento</span>
            <span class="pv">{{ $ordem->token }}</span>
        </div>
        <div class="portal-cell" style="flex:2">
            <span class="pl">Portal do cliente</span>
            <span class="pu">{{ config('app.url') }}/r/{{ $ordem->token }}</span>
        </div>
    </div>
    @endif

    {{-- Checkboxes (só via da empresa) --}}
    @if($via['key'] === 'empresa')
    <div class="progress">
        <div class="progress-head">
            <span class="sec-label" style="margin:0;">Andamento do serviço</span>
            <span class="progress-note">marque conforme evolução</span>
        </div>
        <div class="progress-steps">
            @foreach($steps as $step)
            <div class="progress-step">
                <div class="cb"></div>
                <span class="cb-text">{{ $step }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Condições --}}
    <div>
        <span class="sec-label">Condições de Assistência Técnica</span>
        <div class="conditions">
            @foreach($conditions as $n => $cond)
            <p><b>{{ $n + 1 }}.</b> {{ $cond }}</p>
            @endforeach
        </div>
    </div>

    {{-- Termo --}}
    <div>
        <span class="sec-label">Termo de Compromisso</span>
        <div class="termo">
            <p>
                Eu, <b>{{ $ordem->cliente?->nome ?? '_______________________________' }}</b>,
                portador(a) do CPF/CNPJ <b>{{ $ordem->cliente?->cpf_cnpj ?? '___________________' }}</b>,
                declaro ser proprietário(a) do equipamento acima descrito, o qual deixo sob os cuidados da empresa
                <b>{{ $empresa['nome'] }}</b>, que fica como depositária durante a execução dos serviços,
                comprometendo-me a apresentar os documentos de aquisição. A depositária fica isenta da
                responsabilidade de comprovação da aquisição.
                <b>Autorizo serviços e/ou peças até R$250,00. Acima desse valor, sem autorização prévia,
                pagarei R$50,00 (cinquenta reais) referente ao laudo técnico.</b>
            </p>
        </div>
    </div>

    <div class="spacer"></div>

    {{-- Assinaturas --}}
    <div class="sigs">
        <div class="sig">
            <span class="sig-label">Técnico responsável</span>
            <div class="sig-line"></div>
            <div class="sig-name">{{ $ordem->tecnico?->name ?? 'Técnico' }}</div>
            <div class="sig-date">Data: ______ / ______ / ____________</div>
        </div>
        <div class="sig">
            <span class="sig-label">Assinatura do cliente</span>
            <div class="sig-line"></div>
            <div class="sig-name">{{ $ordem->cliente?->nome ?? '________________________________' }}</div>
            <div class="sig-cpf">{{ $ordem->cliente?->cpf_cnpj ?? '' }}</div>
            <div class="sig-date">Data: ______ / ______ / ____________</div>
        </div>
    </div>

</div>{{-- /via --}}
@if($i === 0)
<div class="cut">✂&ensp;recortar aqui</div>
@endif
@endforeach

</div>{{-- /sheet --}}
</div>{{-- /page-wrap --}}
</body>
</html>
