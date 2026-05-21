@props(['status'])

@php
$config = [
    'entrada'            => ['label' => 'Entrada',     'variant' => 'default'],
    'analise'            => ['label' => 'Em análise',  'variant' => 'warning'],
    'execucao'           => ['label' => 'Em execução', 'variant' => 'primary'],
    'aguardando_cliente' => ['label' => 'Aguardando',  'variant' => 'info'],
    'em_teste'           => ['label' => 'Em teste',    'variant' => 'info'],
    'finalizado'         => ['label' => 'Finalizado',  'variant' => 'success'],
    'cancelado'          => ['label' => 'Cancelado',   'variant' => 'danger'],
];
$c = $config[$status] ?? ['label' => $status, 'variant' => 'default'];
@endphp

<x-ui.badge :variant="$c['variant']" dot>{{ $c['label'] }}</x-ui.badge>
