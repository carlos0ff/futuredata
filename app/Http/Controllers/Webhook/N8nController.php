<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Ordem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class N8nController extends Controller
{
    public function buscarCliente(Request $request): JsonResponse
    {
        if ($request->header('X-N8N-Token') !== config('services.n8n.token')) {
            return response()->json(['erro' => 'Não autorizado'], 401);
        }

        $input = trim((string) $request->query('cpf', ''));

        if (! $input) {
            return response()->json(['erro' => 'Parâmetro cpf obrigatório'], 422);
        }

        $cliente = $this->findCliente($input);

        if (! $cliente) {
            return response()->json(['encontrado' => false]);
        }

        $ordens = Ordem::where('cliente_id', $cliente->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id', 'numero', 'codigo_publico', 'status', 'problema_relatado', 'status_orcamento', 'valor_servico', 'valor_pecas', 'desconto', 'previsao_entrega', 'created_at'])
            ->map(fn (Ordem $o) => [
                'numero'           => $o->codigo_publico ?: $o->numero,
                'status'           => $o->status_label,
                'orcamento'        => $o->status_orcamento,
                'problema'         => $o->problema_relatado,
                'valor_total'      => number_format($o->valor_servico + $o->valor_pecas - $o->desconto, 2, ',', '.'),
                'previsao_entrega' => $o->previsao_entrega?->format('d/m/Y'),
                'criada_em'        => $o->created_at->format('d/m/Y'),
            ]);

        return response()->json([
            'encontrado' => true,
            'cliente'    => [
                'nome'     => $cliente->nome,
                'telefone' => $cliente->telefone,
                'cpf'      => $cliente->cpf_cnpj,
            ],
            'ordens' => $ordens,
        ]);
    }

    private function findCliente(string $input): ?Cliente
    {
        $digits = preg_replace('/\D/', '', $input);
        $upper  = strtoupper(trim($input));

        if (strlen($digits) === 11 || strlen($digits) === 14) {
            $formatted = strlen($digits) === 11
                ? substr($digits, 0, 3) . '.' . substr($digits, 3, 3) . '.' . substr($digits, 6, 3) . '-' . substr($digits, 9, 2)
                : substr($digits, 0, 2) . '.' . substr($digits, 2, 3) . '.' . substr($digits, 5, 3) . '/' . substr($digits, 8, 4) . '-' . substr($digits, 12, 2);

            return Cliente::where('cpf_cnpj', $digits)->orWhere('cpf_cnpj', $formatted)->first();
        }

        if (preg_match('/^OS\d+$/', $upper)) {
            $ordem = Ordem::where('numero', $upper)->orWhere('codigo_publico', $upper)->first();
            return $ordem ? Cliente::find($ordem->cliente_id) : null;
        }

        return null;
    }
}
