<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OS {{ $ordem->numero }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #000;
            background: #fff;
        }

        .via {
            width: 190mm;
            margin: 8mm auto;
            padding: 0;
            page-break-inside: avoid;
        }

        /* ── Header ──────────────────────────────── */
        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-box {
            width: 52px;
            height: 52px;
            border: 2px solid #1e3a8a;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 900;
            color: #1e3a8a;
            letter-spacing: -1px;
            flex-shrink: 0;
        }

        .header-company {
            text-align: center;
            flex: 1;
        }

        .header-company .brand {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        .header-company .brand span { color: #2563eb; }

        .header-company p {
            font-size: 9px;
            line-height: 1.4;
            color: #444;
        }

        .header-company .os-title {
            font-size: 12px;
            font-weight: 700;
            margin-top: 3px;
        }

        .header-right {
            text-align: right;
            flex-shrink: 0;
        }

        .via-label {
            font-size: 9px;
            font-weight: 700;
            color: #555;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border: 1px solid #ccc;
            padding: 2px 6px;
            border-radius: 3px;
        }

        .abertura {
            font-size: 9.5px;
            margin-top: 4px;
        }

        /* ── Sections ────────────────────────────── */
        table.section {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        table.section td, table.section th {
            border: 1px solid #000;
            padding: 3px 5px;
            vertical-align: top;
            font-size: 9.5px;
            line-height: 1.4;
        }

        table.section th {
            background: #f0f0f0;
            font-weight: 700;
            font-size: 9px;
        }

        .label { font-weight: 700; }

        .queixa-cell { width: 50%; min-height: 30px; }
        .obs-cell    { width: 50%; min-height: 30px; }

        /* ── Conditions ──────────────────────────── */
        .conditions {
            border: 1px solid #000;
            border-top: 0;
            padding: 4px 5px;
        }

        .conditions p {
            font-size: 8.5px;
            line-height: 1.45;
            margin-bottom: 1px;
        }

        .conditions .title {
            font-size: 9px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        /* ── Commitment ──────────────────────────── */
        .commitment {
            border: 1px solid #000;
            border-top: 0;
            padding: 4px 5px;
        }

        .commitment .title {
            font-size: 9px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .commitment p {
            font-size: 8.5px;
            line-height: 1.45;
        }

        /* ── Signature ───────────────────────────── */
        .signature-row {
            border: 1px solid #000;
            border-top: 0;
            padding: 5px 5px 12px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 9px;
        }

        .sig-block {
            text-align: center;
            flex: 1;
        }

        .sig-line {
            border-top: 1px solid #000;
            margin: 0 20px 2px;
            margin-top: 20px;
        }

        .sig-label { font-size: 9px; font-weight: 700; }
        .sig-sub   { font-size: 8px; color: #444; }

        .tech-field {
            font-size: 9px;
        }

        /* ── Separator ───────────────────────────── */
        .separator {
            border: none;
            border-top: 1px dashed #666;
            margin: 6mm auto;
            width: 190mm;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            body { background: #fff; }

            .no-print { display: none !important; }

            .via { margin: 7mm auto; }

            .separator { margin: 5mm auto; }
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
    ])->filter()->implode(' ');

    $equip = $equip ?: '– - –';

    $endereco = collect([
        $ordem->cliente?->endereco,
        $ordem->cliente?->cidade,
        $ordem->cliente?->estado,
        $ordem->cliente?->cep ? 'CEP ' . $ordem->cliente->cep : null,
    ])->filter()->implode(', ');

    $conditions = [
        "A empresa {$empresa['nome']} não se responsabilizará por quaisquer perdas de dados (arquivos) gravados em disco rígido (HD) e/ou softwares instalados que possam ocorrer. Fica sob inteira responsabilidade do cliente todo e qualquer tipo de cópia de segurança (backup) dos mesmos.",
        "Os Serviços executados têm garantia corrida de 15 dias a partir da data de entrega. Caso o problema for causado por mau uso, o cliente perderá a garantia.",
        "Equipamentos em garantia de venda só estarão livres de taxas e valores de serviços mediante apresentação de nota fiscal;",
        "Após 60 dias do orçamento apresentado ao cliente, o mesmo se compromete a pagar taxa de armazenagem de R\$5,00 por dia, caso não venha retirar o equipamento neste prazo.",
        "Decorrido o prazo de 90 dias após o orçamento apresentado ao cliente, a {$empresa['nome']} fica autorizada a alienar o equipamento para ressarcimentos dos custos de armazenagem e dos serviços realizados;",
    ];

    $vias = [
        ['label' => 'VIA DO CLIENTE'],
        ['label' => 'VIA DA EMPRESA'],
    ];
@endphp

{{-- Botão de impressão (não imprime) --}}
<div class="no-print" style="text-align:center;padding:12px;background:#f8fafc;border-bottom:1px solid #e2e8f0;">
    <a href="{{ route('app.os.show', $ordem) }}"
       style="display:inline-block;margin-right:10px;padding:7px 18px;border:1px solid #cbd5e1;border-radius:8px;font-size:12px;color:#475569;text-decoration:none;">
        ← Voltar
    </a>
    <button onclick="window.print()"
            style="padding:7px 22px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;">
        Imprimir
    </button>
</div>

@foreach($vias as $i => $via)

<div class="via">

    {{-- Header --}}
    <div class="header">
        <div class="header-logo">
            <div class="logo-box">F<span style="color:#2563eb">D</span></div>
            <div>
                <div class="brand">Future Dat<span>@</span></div>
            </div>
        </div>
        <div class="header-company">
            <p style="font-weight:700;">{{ $empresa['nome'] }}</p>
            <p>{{ $empresa['endereco'] }}</p>
            <p>{{ $empresa['telefone'] }}</p>
            <p class="os-title">Ordem de Serviço – nº {{ $ordem->numero }}</p>
        </div>
        <div class="header-right">
            <div class="via-label">{{ $via['label'] }}</div>
            <div class="abertura">Abertura: <strong>{{ $ordem->created_at->format('d/m/Y') }}</strong></div>
        </div>
    </div>

    {{-- Cliente --}}
    <table class="section">
        <tr>
            <td style="width:50%">
                <span class="label">Cliente:</span> {{ $ordem->cliente?->nome ?? '—' }}
            </td>
            <td style="width:50%">
                <span class="label">Endereço:</span> {{ $endereco ?: '—' }}
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">CPF/CNPJ:</span> {{ $ordem->cliente?->cpf_cnpj ?? '—' }}
            </td>
            <td>
                <span class="label">Cidade:</span>
                {{ $ordem->cliente?->cidade ?? '' }}{{ $ordem->cliente?->estado ? ', ' . $ordem->cliente->estado : '' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="label">Telefone:</span>
                Celular: {{ $ordem->cliente?->telefone ?? '—' }}
            </td>
        </tr>
    </table>

    {{-- Equipamento --}}
    <table class="section">
        <tr>
            <td>
                <span class="label">Equipamento:</span>
                {{ $equip }}
                @if($ordem->equipamento?->numero_serie)
                    &nbsp;|&nbsp; Nº Série: {{ $ordem->equipamento->numero_serie }}
                @endif
                @if($ordem->equipamento?->acessorios)
                    &nbsp;|&nbsp; Acessórios: {{ $ordem->equipamento->acessorios }}
                @endif
            </td>
        </tr>
    </table>

    {{-- Queixa / Observações --}}
    <table class="section">
        <tr>
            <th class="queixa-cell">Queixa</th>
            <th class="obs-cell">Observações</th>
        </tr>
        <tr>
            <td class="queixa-cell" style="min-height:40px; height:40px;">
                {{ $ordem->problema_relatado }}
            </td>
            <td class="obs-cell" style="min-height:40px; height:40px;">
                {{ $ordem->observacoes ?? '' }}
            </td>
        </tr>
    </table>

    {{-- Condições --}}
    <div class="conditions">
        <p class="title">Condição de Assistência Técnica</p>
        @foreach($conditions as $n => $cond)
            <p>{{ $n + 1 }}- {{ $cond }}</p>
        @endforeach
    </div>

    {{-- Termo de Compromisso --}}
    <div class="commitment">
        <p class="title">Termo de Compromisso</p>
        <p>
            Eu, <strong>{{ $ordem->cliente?->nome ?? '___________________' }}</strong> declaro que sou
            proprietário do equipamento acima citado, o qual deixo aos cuidados da empresa
            <strong>{{ $empresa['nome'] }}</strong>, que fica como depositária durante a execução do(s)
            serviços(s) e, comprometo-me a apresentar os documentos de aquisição do mesmo, ou seja,
            a depositária fica isenta da responsabilidade de comprovação da aquisição deste equipamento.
            <strong>Comprometo-me também, a autorizar com a assinatura desta Ordem de Serviço qualquer
            serviço e/ou peça no valor de até R$250,00. Acima desse valor, caso não for autorizado o
            serviço, pagarei uma taxa de R$50,00 (CINQUENTA REAIS), referente ao laudo técnico.</strong>
        </p>
    </div>

    {{-- Assinaturas --}}
    <div class="signature-row">
        <div class="tech-field">
            Técnico: {{ $ordem->tecnico?->name ?? '' }}<br><br>
        </div>
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-label">{{ $ordem->cliente?->nome ?? '___________________' }}</div>
            <div class="sig-sub">{{ $ordem->cliente?->cpf_cnpj ?? '' }}</div>
        </div>
    </div>

</div>

@if(! $loop->last)
    <hr class="separator">
@endif

@endforeach

</body>
</html>
