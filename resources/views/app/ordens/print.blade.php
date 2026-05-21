<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OS {{ $ordem->numero }} — Impressão</title>
    <style>
        /* ── Reset ───────────────────────────────────────────────────────────── */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        /* ── Base ────────────────────────────────────────────────────────────── */
        html { font-size: 9.5px; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 1rem;
            color: #000;
            background: #e8eaf0;
            line-height: 1.35;
        }

        /* ── Toolbar (no-print) ──────────────────────────────────────────────── */
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #1e293b;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.3);
        }

        .toolbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toolbar-title {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .02em;
        }

        .toolbar-sub {
            font-size: 10.5px;
            color: #94a3b8;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            border: 1px solid #475569;
            border-radius: 7px;
            font-size: 11.5px;
            color: #cbd5e1;
            text-decoration: none;
            transition: background .15s;
        }

        .btn-back:hover { background: #334155; }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 20px;
            background: #2563eb;
            border: none;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-print:hover { background: #1d4ed8; }

        /* ── Page wrapper ────────────────────────────────────────────────────── */
        .page-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px 12px 32px;
            gap: 0;
        }

        /* ── Via (each copy) ─────────────────────────────────────────────────── */
        .via {
            width: 210mm;
            background: #fff;
            box-shadow: 0 1px 6px rgba(0,0,0,.14);
        }

        .via-inner {
            padding: 7mm 8mm 5mm;
        }

        /* ── Header ──────────────────────────────────────────────────────────── */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .header-logo-cell,
        .header-company-cell,
        .header-right-cell {
            display: table-cell;
            vertical-align: middle;
        }

        .header-logo-cell {
            width: 36mm;
        }

        .header-logo-cell img {
            width: 32mm;
            height: auto;
            display: block;
        }

        .header-company-cell {
            text-align: center;
            padding: 0 4mm;
        }

        .company-name {
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1e293b;
            letter-spacing: .03em;
        }

        .company-address {
            font-size: 8px;
            color: #475569;
            margin-top: 1px;
        }

        .os-number {
            font-size: 11.5px;
            font-weight: 900;
            color: #1e293b;
            margin-top: 3px;
            letter-spacing: -.02em;
        }

        .header-right-cell {
            width: 36mm;
            text-align: right;
            vertical-align: top;
            padding-top: 2px;
        }

        .via-badge {
            display: inline-block;
            padding: 2px 7px;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #fff;
            border-radius: 3px;
        }

        .via-badge.cliente { background: #1e3a8a; }
        .via-badge.empresa { background: #991b1b; }

        .abertura {
            font-size: 8.5px;
            margin-top: 4px;
            color: #1e293b;
        }

        .abertura strong { font-weight: 800; }

        /* ── Divider ─────────────────────────────────────────────────────────── */
        .divider {
            border: none;
            border-top: 1.5px solid #1e293b;
            margin: 3px 0;
        }

        .divider-thin {
            border: none;
            border-top: 1px solid #cbd5e1;
            margin: 0;
        }

        /* ── Data grid ───────────────────────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #94a3b8;
        }

        .data-table td {
            border: 1px solid #94a3b8;
            padding: 2.5px 5px;
            font-size: 9px;
            vertical-align: top;
        }

        .data-table .field-label {
            font-weight: 700;
            color: #374151;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: .04em;
            display: block;
            margin-bottom: 1px;
        }

        .data-table .field-value {
            font-size: 9.5px;
            color: #111827;
        }

        /* ── Section header ──────────────────────────────────────────────────── */
        .section-header {
            background: #f1f5f9;
            border: 1px solid #94a3b8;
            border-bottom: none;
            padding: 2px 5px;
            font-size: 7.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #475569;
        }

        /* ── Text area cells ─────────────────────────────────────────────────── */
        .text-box {
            border: 1px solid #94a3b8;
            padding: 3px 5px;
            min-height: 18mm;
            font-size: 9px;
            color: #111827;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .text-box-half {
            display: table-cell;
            width: 50%;
            border: 1px solid #94a3b8;
            border-left: none;
            padding: 3px 5px;
            min-height: 18mm;
            vertical-align: top;
        }

        .text-box-half:first-child {
            border-left: 1px solid #94a3b8;
        }

        .text-box-row {
            display: table;
            width: 100%;
            border-collapse: collapse;
            border-top: none;
        }

        /* ── Financial ───────────────────────────────────────────────────────── */
        .financial-row {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .financial-cell {
            display: table-cell;
            border: 1px solid #94a3b8;
            border-top: none;
            padding: 3px 6px;
            text-align: center;
            vertical-align: middle;
        }

        .financial-cell + .financial-cell {
            border-left: none;
        }

        .financial-cell .f-label {
            font-size: 7px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
            display: block;
        }

        .financial-cell .f-value {
            font-size: 10.5px;
            font-weight: 800;
            color: #111827;
            display: block;
            margin-top: 1px;
        }

        .financial-cell.total {
            background: #1e293b;
        }

        .financial-cell.total .f-label { color: #94a3b8; }
        .financial-cell.total .f-value { color: #fff; font-size: 12px; }

        /* ── Conditions ──────────────────────────────────────────────────────── */
        .conditions-box {
            border: 1px solid #94a3b8;
            border-top: none;
            padding: 3px 5px;
        }

        .conditions-box p {
            font-size: 7.8px;
            line-height: 1.4;
            color: #1e293b;
        }

        .conditions-box p + p { margin-top: 1.5px; }

        /* ── Commitment ──────────────────────────────────────────────────────── */
        .commitment-box {
            border: 1px solid #94a3b8;
            border-top: none;
            padding: 3px 5px;
        }

        .commitment-box p {
            font-size: 8px;
            line-height: 1.4;
            color: #1e293b;
            text-align: justify;
        }

        /* ── Signatures ──────────────────────────────────────────────────────── */
        .sig-table {
            display: table;
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #94a3b8;
            border-top: none;
        }

        .sig-cell {
            display: table-cell;
            width: 33.33%;
            padding: 4px 6px 8px;
            border-right: 1px solid #94a3b8;
            vertical-align: bottom;
        }

        .sig-cell:last-child { border-right: none; }

        .sig-label-top {
            font-size: 7.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #64748b;
            margin-bottom: 10px;
            display: block;
        }

        .sig-name-value {
            font-size: 9px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1px;
        }

        .sig-line {
            border-top: 1px solid #1e293b;
            margin-bottom: 2px;
        }

        .sig-sub {
            font-size: 7.5px;
            color: #64748b;
        }

        .sig-date {
            font-size: 8px;
            color: #475569;
            margin-top: 3px;
        }

        /* ── Portal box ──────────────────────────────────────────────────────── */
        .portal-box {
            display: table;
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #94a3b8;
            border-top: none;
            background: #f8fafc;
        }

        .portal-cell {
            display: table-cell;
            padding: 3px 6px;
            vertical-align: middle;
        }

        .portal-cell .p-label {
            font-size: 7px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
        }

        .portal-cell .p-value {
            font-size: 9px;
            font-weight: 700;
            color: #1e40af;
            font-family: 'Courier New', monospace;
            letter-spacing: .1em;
        }

        .portal-cell .p-url {
            font-size: 8px;
            color: #475569;
            font-family: 'Courier New', monospace;
        }

        /* ── Page separator (between vias) ───────────────────────────────────── */
        .page-sep {
            width: 210mm;
            padding: 5px 8mm;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #94a3b8;
            font-size: 9px;
        }

        .page-sep::before,
        .page-sep::after {
            content: '';
            flex: 1;
            border-top: 1px dashed #94a3b8;
        }

        /* ── Print ───────────────────────────────────────────────────────────── */
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            html { font-size: 9.5px; }
            body { background: #fff; }

            .toolbar { display: none !important; }

            .page-wrap {
                padding: 0;
                gap: 0;
            }

            .via {
                box-shadow: none;
                page-break-inside: avoid;
            }

            .page-sep {
                page-break-after: avoid;
                padding: 3px 8mm;
            }

            /* Force black borders for print */
            .data-table,
            .data-table td,
            .text-box,
            .text-box-half,
            .financial-cell,
            .conditions-box,
            .commitment-box,
            .sig-table,
            .sig-cell,
            .portal-box { border-color: #000 !important; }

            .section-header { border-color: #000 !important; }
            .divider { border-top-color: #000 !important; }
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
    ])->filter()->join(' ');
    $equip = $equip ?: '–';

    $endereco = collect([
        $ordem->cliente?->endereco,
        $ordem->cliente?->cidade && $ordem->cliente?->estado
            ? $ordem->cliente->cidade . ' – ' . $ordem->cliente->estado
            : ($ordem->cliente?->cidade ?? $ordem->cliente?->estado ?? null),
        $ordem->cliente?->cep ? 'CEP ' . $ordem->cliente->cep : null,
    ])->filter()->join(', ');

    $temValores = ($ordem->valor_servico + $ordem->valor_pecas) > 0;

    $conditions = [
        "A empresa {$empresa['nome']} não se responsabilizará por quaisquer perdas de dados (arquivos) gravados em disco rígido (HD) e/ou softwares instalados que possam ocorrer. Fica sob inteira responsabilidade do cliente todo e qualquer tipo de cópia de segurança (backup) dos mesmos.",
        "Os Serviços executados têm garantia corrida de 15 dias a partir da data de entrega. Caso o problema for causado por mau uso, o cliente perderá a garantia.",
        "Equipamentos em garantia de venda só estarão livres de taxas e valores de serviços mediante apresentação de nota fiscal.",
        "Após 60 dias do orçamento apresentado ao cliente, o mesmo se compromete a pagar taxa de armazenagem de R\$5,00 por dia, caso não venha retirar o equipamento neste prazo.",
        "Decorrido o prazo de 90 dias após o orçamento apresentado ao cliente, a {$empresa['nome']} fica autorizada a alienar o equipamento para ressarcimentos dos custos de armazenagem e dos serviços realizados.",
    ];

    $vias = [
        ['key' => 'cliente', 'label' => 'Via do Cliente'],
        ['key' => 'empresa', 'label' => 'Via da Empresa'],
    ];

    $statusLabels = [
        'entrada'            => 'Entrada registrada',
        'analise'            => 'Em análise',
        'execucao'           => 'Em execução',
        'aguardando_cliente' => 'Aguardando cliente',
        'em_teste'           => 'Em teste',
        'finalizado'         => 'Finalizado',
        'cancelado'          => 'Cancelado',
    ];
@endphp

{{-- ── Toolbar ──────────────────────────────────────────────────────────────── --}}
<div class="toolbar">
    <div class="toolbar-left">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#60a5fa;flex-shrink:0"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
        <div>
            <div class="toolbar-title">Ordem de Serviço — {{ $ordem->numero }}</div>
            <div class="toolbar-sub">{{ $ordem->cliente?->nome }} · {{ $ordem->created_at->format('d/m/Y') }} · Duas vias</div>
        </div>
    </div>
    <div class="toolbar-actions">
        <a href="{{ route('app.os.show', $ordem) }}" class="btn-back">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m12 19-7-7 7-7M19 12H5"/></svg>
            Voltar
        </a>
        <button onclick="window.print()" class="btn-print">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
            Imprimir / Salvar PDF
        </button>
    </div>
</div>

<div class="page-wrap">

@foreach($vias as $via)

<div class="via">
<div class="via-inner">

    {{-- ── Header ─────────────────────────────────────────────────────── --}}
    <div class="header">
        <div class="header-logo-cell">
            <img src="{{ asset('images/futuredata.png') }}" alt="FutureData">
        </div>
        <div class="header-company-cell">
            <div class="company-name">{{ $empresa['nome'] }}</div>
            <div class="company-address">{{ $empresa['endereco'] }} · {{ $empresa['telefone'] }}</div>
            <div class="os-number">Ordem de Serviço – nº {{ $ordem->numero }}</div>
        </div>
        <div class="header-right-cell">
            <span class="via-badge {{ $via['key'] }}">{{ $via['label'] }}</span>
            <div class="abertura">Abertura: <strong>{{ $ordem->created_at->format('d/m/Y') }}</strong></div>
            @if($ordem->previsao_entrega)
            <div class="abertura" style="margin-top:2px;">Previsão: <strong>{{ $ordem->previsao_entrega->format('d/m/Y') }}</strong></div>
            @endif
        </div>
    </div>

    <hr class="divider">

    {{-- ── Dados do Cliente ────────────────────────────────────────────── --}}
    <table class="data-table">
        <tr>
            <td style="width:55%">
                <span class="field-label">Cliente</span>
                <span class="field-value">{{ $ordem->cliente?->nome ?? '—' }}</span>
            </td>
            <td style="width:45%">
                <span class="field-label">Endereço</span>
                <span class="field-value">{{ $endereco ?: '—' }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="field-label">CPF / CNPJ</span>
                <span class="field-value">{{ $ordem->cliente?->cpf_cnpj ?? '—' }}</span>
            </td>
            <td>
                <span class="field-label">Telefone</span>
                <span class="field-value">{{ $ordem->cliente?->telefone ?? '—' }}</span>
            </td>
        </tr>
    </table>

    {{-- ── Equipamento ─────────────────────────────────────────────────── --}}
    <table class="data-table" style="border-top:none;">
        <tr>
            <td style="width:40%">
                <span class="field-label">Equipamento</span>
                <span class="field-value">{{ $equip }}</span>
            </td>
            <td style="width:25%">
                <span class="field-label">Nº de Série</span>
                <span class="field-value">{{ $ordem->equipamento?->numero_serie ?: '—' }}</span>
            </td>
            <td style="width:20%">
                <span class="field-label">Condição</span>
                <span class="field-value">{{ $ordem->equipamento?->condicao_entrada ?: '—' }}</span>
            </td>
            <td style="width:15%">
                <span class="field-label">Garantia</span>
                <span class="field-value">{{ $ordem->equipamento?->em_garantia ? 'Sim' : 'Não' }}</span>
            </td>
        </tr>
        @if($ordem->equipamento?->acessorios)
        <tr>
            <td colspan="4">
                <span class="field-label">Acessórios / Itens inclusos</span>
                <span class="field-value">{{ $ordem->equipamento->acessorios }}</span>
            </td>
        </tr>
        @endif
    </table>

    {{-- ── Queixa / Diagnóstico ────────────────────────────────────────── --}}
    <div class="section-header" style="margin-top:-1px; display:table; width:100%;">
        <div style="display:table-cell; width:50%;">Defeito / Queixa relatada pelo cliente</div>
        <div style="display:table-cell; width:50%; border-left:1px solid #94a3b8; padding-left:5px;">Diagnóstico técnico / Observações</div>
    </div>
    <div class="text-box-row">
        <div class="text-box-half">{{ $ordem->problema_relatado }}</div>
        <div class="text-box-half">{{ $ordem->diagnostico ?: ($via['key'] === 'empresa' ? '' : 'Aguardando diagnóstico.') }}</div>
    </div>

    @if($ordem->solucao && $via['key'] === 'empresa')
    <div class="section-header" style="border-top:none;">Solução aplicada</div>
    <div class="text-box" style="min-height:8mm;">{{ $ordem->solucao }}</div>
    @endif

    {{-- ── Financeiro (só se tiver valores) ───────────────────────────── --}}
    @if($temValores)
    <div class="financial-row">
        <div class="financial-cell" style="width:26%">
            <span class="f-label">Mão de Obra / Serviços</span>
            <span class="f-value">R$ {{ number_format($ordem->valor_servico, 2, ',', '.') }}</span>
        </div>
        <div class="financial-cell" style="width:24%">
            <span class="f-label">Peças / Componentes</span>
            <span class="f-value">R$ {{ number_format($ordem->valor_pecas, 2, ',', '.') }}</span>
        </div>
        <div class="financial-cell" style="width:20%">
            <span class="f-label">Desconto</span>
            <span class="f-value">{{ $ordem->desconto > 0 ? '− R$ '.number_format($ordem->desconto, 2, ',', '.') : '—' }}</span>
        </div>
        <div class="financial-cell total" style="width:30%; border-left:none;">
            <span class="f-label">Total do Orçamento</span>
            <span class="f-value">R$ {{ number_format($ordem->total, 2, ',', '.') }}</span>
        </div>
    </div>
    @endif

    {{-- ── Status + Portal ─────────────────────────────────────────────── --}}
    <div class="portal-box">
        <div class="portal-cell" style="width:40%">
            <span class="p-label">Status</span>
            <span class="p-value" style="color:#111827;font-family:Arial;letter-spacing:0;font-size:9.5px;">
                {{ $statusLabels[$ordem->status] ?? $ordem->status }}
                @if($ordem->status_orcamento === 'aprovado') · Orçamento aprovado
                @elseif($ordem->status_orcamento === 'recusado') · Orçamento recusado
                @endif
            </span>
        </div>
        @if($ordem->token)
        <div class="portal-cell" style="border-left:1px solid #94a3b8; width:30%">
            <span class="p-label">Código do portal</span>
            <span class="p-value">{{ $ordem->token }}</span>
        </div>
        <div class="portal-cell" style="border-left:1px solid #94a3b8;">
            <span class="p-label">Acesso em</span>
            <span class="p-url">{{ config('app.url') }}/r/{{ $ordem->token }}</span>
        </div>
        @endif
    </div>

    {{-- ── Condições de Assistência ─────────────────────────────────────── --}}
    <div class="section-header" style="border-top:none;">Condições de Assistência Técnica</div>
    <div class="conditions-box">
        @foreach($conditions as $n => $cond)
        <p><strong>{{ $n + 1 }}-</strong> {{ $cond }}</p>
        @endforeach
    </div>

    {{-- ── Termo de Compromisso ─────────────────────────────────────────── --}}
    <div class="section-header" style="border-top:none;">Termo de Compromisso</div>
    <div class="commitment-box">
        <p>
            Eu, <strong>{{ $ordem->cliente?->nome ?? '_______________________________' }}</strong>,
            portador(a) do CPF/CNPJ <strong>{{ $ordem->cliente?->cpf_cnpj ?? '___________________' }}</strong>,
            declaro que sou proprietário(a) do equipamento acima citado, o qual deixo aos cuidados da empresa
            <strong>{{ $empresa['nome'] }}</strong>, que fica como depositária durante a execução do(s) serviço(s),
            comprometendo-me a apresentar os documentos de aquisição do mesmo. A depositária fica isenta da
            responsabilidade de comprovação da aquisição deste equipamento.
            <strong>Autorizo qualquer serviço e/ou peça no valor de até R$250,00. Acima desse valor, caso não
            autorizado, pagarei a taxa de R$50,00 (CINQUENTA REAIS) referente ao laudo técnico.</strong>
        </p>
    </div>

    {{-- ── Assinaturas ─────────────────────────────────────────────────── --}}
    <div class="sig-table">
        <div class="sig-cell">
            <span class="sig-label-top">Técnico responsável</span>
            <div class="sig-line"></div>
            <div class="sig-name-value">{{ $ordem->tecnico?->name ?? 'Técnico' }}</div>
            <div class="sig-date">Data: ____/____/________</div>
        </div>
        <div class="sig-cell">
            <span class="sig-label-top">Assinatura do cliente (entrada)</span>
            <div class="sig-line"></div>
            <div class="sig-name-value">{{ $ordem->cliente?->nome ?? '_______________________________' }}</div>
            <div class="sig-sub">{{ $ordem->cliente?->cpf_cnpj ?? '' }}</div>
            <div class="sig-date">Data: ____/____/________</div>
        </div>
        <div class="sig-cell">
            <span class="sig-label-top">Assinatura do cliente (retirada)</span>
            <div class="sig-line"></div>
            <div class="sig-name-value" style="color:transparent">x</div>
            <div class="sig-sub" style="color:transparent">-</div>
            <div class="sig-date">Data: ____/____/________</div>
        </div>
    </div>

</div>{{-- /via-inner --}}
</div>{{-- /via --}}

@if(! $loop->last)
<div class="page-sep">recortar aqui</div>
@endif

@endforeach

</div>{{-- /page-wrap --}}

</body>
</html>
