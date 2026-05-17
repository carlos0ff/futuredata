<?php

namespace App\Http\Requests\Ordens;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrdemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id'        => ['sometimes', 'exists:clientes,id'],
            'equipamento_id'    => ['nullable', 'exists:equipamentos,id'],
            'tecnico_id'        => ['nullable', 'exists:users,id'],
            'status'            => ['sometimes', 'in:entrada,analise,execucao,aguardando_cliente,em_teste,finalizado,cancelado'],
            'problema_relatado' => ['sometimes', 'string', 'max:2000'],
            'diagnostico'       => ['nullable', 'string', 'max:2000'],
            'solucao'           => ['nullable', 'string', 'max:2000'],
            'valor_servico'     => ['nullable', 'numeric', 'min:0'],
            'valor_pecas'       => ['nullable', 'numeric', 'min:0'],
            'desconto'          => ['nullable', 'numeric', 'min:0'],
            'previsao_entrega'  => ['nullable', 'date'],
            'observacoes'       => ['nullable', 'string', 'max:1000'],
            'observacao_status' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
