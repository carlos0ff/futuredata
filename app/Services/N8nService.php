<?php

namespace App\Services;

use App\Models\Ordem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Dispara eventos do FutureData para um webhook n8n.
 *
 * Eventos disponíveis:
 *   os.criada              — nova OS aberta
 *   os.status_alterado     — status da OS mudou
 *   os.orcamento_enviado   — orçamento adicionado e aguardando resposta
 *   os.orcamento_respondido — cliente aprovou ou recusou o orçamento
 *   mensagem.tecnico       — técnico enviou mensagem ao cliente
 */
class N8nService
{
    public function dispatch(string $event, Ordem $ordem, array $extra = []): void
    {
        $url = config('services.n8n.webhook_url');
        if (! $url) return;

        $ordem->loadMissing(['cliente', 'equipamento']);

        $cliente = $ordem->cliente;
        $equip   = $ordem->equipamento;

        $payload = array_merge([
            'event'     => $event,
            'timestamp' => now()->toIso8601String(),
            'os' => [
                'id'               => $ordem->id,
                'numero'           => $ordem->numero,
                'status'           => $ordem->status,
                'status_label'     => $ordem->status_label,
                'diagnostico'      => $ordem->diagnostico,
                'status_orcamento' => $ordem->status_orcamento,
                'previsao_entrega' => $ordem->previsao_entrega?->format('d/m/Y'),
                'url_portal'       => route('portal.token', $ordem->token),
            ],
            'equipamento' => $equip ? [
                'tipo'   => $equip->tipo,
                'marca'  => $equip->marca,
                'modelo' => $equip->modelo,
                'nome'   => trim("{$equip->tipo} {$equip->marca} {$equip->modelo}"),
            ] : null,
            'cliente' => $cliente ? [
                'nome'     => $cliente->nome,
                'telefone' => preg_replace('/\D/', '', $cliente->telefone ?? ''),
                'email'    => $cliente->email,
            ] : null,
        ], $extra);

        try {
            Http::timeout(5)->post($url, $payload);
        } catch (\Throwable $e) {
            Log::warning('N8nService: falha ao disparar evento', [
                'event' => $event,
                'os'    => $ordem->numero,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
