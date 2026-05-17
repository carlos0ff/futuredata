<?php

namespace App\Http\Requests\Ordens;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $novoCliente = $this->input('tipo_cliente') === 'novo';

        return [
            'tipo_cliente'          => ['required', 'in:existente,novo'],
            // Cliente existente
            'cliente_id'            => [$novoCliente ? 'nullable' : 'required', 'exists:clientes,id'],
            // Novo cliente
            'novo_cliente_nome'     => [$novoCliente ? 'required' : 'nullable', 'string', 'max:150'],
            'novo_cliente_telefone' => ['nullable', 'string', 'max:20'],
            'novo_cliente_email'    => ['nullable', 'email', 'max:150'],
            'novo_cliente_cpf'      => ['nullable', 'string', 'max:20'],
            'novo_cliente_cidade'   => ['nullable', 'string', 'max:100'],
            'novo_cliente_estado'   => ['nullable', 'string', 'max:2'],
            'equipamento_id'        => ['nullable', 'exists:equipamentos,id'],
            'equipamento_tipo'      => ['nullable', 'string', 'max:80'],
            'equipamento_marca'     => ['nullable', 'string', 'max:80'],
            'equipamento_modelo'    => ['nullable', 'string', 'max:120'],
            'equipamento_serie'     => ['nullable', 'string', 'max:100'],
            'equipamento_acessorios'=> ['nullable', 'string', 'max:200'],
            'equipamento_condicao'  => ['nullable', 'string', 'max:200'],
            'equipamento_garantia'  => ['nullable'],
            'tecnico_id'            => ['nullable', 'exists:users,id'],
            'status'                => ['required', 'in:entrada,analise,execucao,aguardando_cliente,em_teste,finalizado,cancelado'],
            'problema_relatado'     => ['required', 'string', 'max:2000'],
            'diagnostico'           => ['nullable', 'string', 'max:2000'],
            'solucao'               => ['nullable', 'string', 'max:2000'],
            'valor_servico'         => ['nullable', 'numeric', 'min:0'],
            'valor_pecas'           => ['nullable', 'numeric', 'min:0'],
            'desconto'              => ['nullable', 'numeric', 'min:0'],
            'previsao_entrega'      => ['nullable', 'date'],
            'observacoes'           => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.required'           => 'Selecione um cliente.',
            'cliente_id.exists'             => 'Cliente não encontrado.',
            'novo_cliente_nome.required'    => 'Informe o nome do cliente.',
            'status.required'               => 'Informe o status.',
            'problema_relatado.required'    => 'Descreva o problema relatado.',
        ];
    }
}
