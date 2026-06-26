<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ordem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * KDS (Kitchen Display System) — endpoint público para o painel de chamados.
 *
 * Retorna todas as OS ativas agrupadas por status para os displays KDS.
 * Sem autenticação: os displays KDS rodam na rede interna.
 */
class KdsController extends Controller
{
    private const STATUS_MAP = [
        'entrada'            => 'entrada',
        'analise'            => 'analise',
        'execucao'           => 'execucao',
        'aguardando_cliente' => 'aguardando',
        'em_teste'           => 'teste',
        'finalizado'         => 'finalizado',
        'cancelado'          => 'cancelado',
    ];

    private const STATUS_LABELS = [
        'entrada'            => 'Entrada',
        'analise'            => 'Em Análise',
        'execucao'           => 'Em Execução',
        'aguardando_cliente' => 'Aguardando Cliente',
        'em_teste'           => 'Em Teste',
        'finalizado'         => 'Finalizado',
        'cancelado'          => 'Cancelado',
    ];

    public function ordens(Request $request): JsonResponse
    {
        $ordens = Ordem::with(['cliente', 'equipamento', 'tecnico'])
            ->whereNotIn('status', ['cancelado'])
            ->latest()
            ->get()
            ->map(function (Ordem $o) {
                $cliente   = $o->cliente;
                $equip     = $o->equipamento;
                $tecnico   = $o->tecnico;

                $nome    = $cliente?->nome ?? 'Cliente';
                $initials = collect(explode(' ', trim($nome)))->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->implode('');

                $device = $equip
                    ? trim("{$equip->marca} {$equip->modelo}") ?: $equip->tipo
                    : 'Equipamento';

                $phone = preg_replace('/\D/', '', $cliente?->telefone ?? '');
                if (strlen($phone) === 11) $phone = '55' . $phone;
                elseif (strlen($phone) === 10) $phone = '55' . $phone;

                $statusLabel = self::STATUS_LABELS[$o->status] ?? $o->status;
                $statusKey   = self::STATUS_MAP[$o->status]    ?? $o->status;

                return [
                    'id'          => $o->codigo_publico ?? $o->numero,
                    'numero'      => $o->numero,
                    'status'      => $statusKey,
                    'statusLabel' => $statusLabel,
                    'orcamento'   => $o->status_orcamento,
                    'client'      => $nome,
                    'initials'    => $initials ?: '?',
                    'contact'     => $this->formatPhone($cliente?->telefone ?? ''),
                    'phone'       => $phone,
                    'device'      => $device,
                    'defect'      => $o->problema_relatado ?? '',
                    'obs'         => $o->observacoes ?? '',
                    'tech'        => $tecnico?->name ?? '—',
                    'techInitials'=> $tecnico ? collect(explode(' ', trim($tecnico->name)))->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->implode('') : '—',
                    'value'       => $o->total > 0 ? 'R$ ' . number_format($o->total, 2, ',', '.') : '—',
                    'deadline'    => $o->previsao_entrega ? $o->previsao_entrega->format('d/m') : '—',
                    'timeOpen'    => $o->created_at->diffForHumans(),
                    'createdAt'   => $o->created_at->toIso8601String(),
                ];
            });

        $grouped = $ordens->groupBy('status');

        return response()->json([
            'ordens'  => $ordens->values(),
            'grouped' => $grouped,
            'counts'  => [
                'entrada'    => $grouped->get('entrada', collect())->count(),
                'analise'    => $grouped->get('analise', collect())->count(),
                'execucao'   => $grouped->get('execucao', collect())->count(),
                'aguardando' => $grouped->get('aguardando', collect())->count(),
                'teste'      => $grouped->get('teste', collect())->count(),
                'finalizado' => $ordens->where('status', 'finalizado')->count(),
            ],
            'updatedAt' => now()->toIso8601String(),
        ]);
    }

    private function formatPhone(string $phone): string
    {
        $d = preg_replace('/\D/', '', $phone);
        if (strlen($d) === 11) return '(' . substr($d, 0, 2) . ') ' . substr($d, 2, 5) . '-' . substr($d, 7);
        if (strlen($d) === 10) return '(' . substr($d, 0, 2) . ') ' . substr($d, 2, 4) . '-' . substr($d, 6);
        return $phone;
    }
}
