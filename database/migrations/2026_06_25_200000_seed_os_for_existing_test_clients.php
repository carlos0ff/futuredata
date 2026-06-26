<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Cria as OS para os 10 clientes de teste que já existem no banco.
 * Necessário porque a migration anterior falhava silenciosamente por não
 * incluir o campo problema_relatado (NOT NULL) no INSERT da ordem.
 */
return new class extends Migration
{
    private array $clientes = [
        [
            'cpf'  => '11111111111',
            'equip' => ['Smartphone', 'Samsung', 'Galaxy S23', 'Tela trincada, não liga'],
            'os'   => ['execucao', 'aprovado', 'Display danificado e bateria com mau contato. Necessário troca de display.', 380.00, 3],
        ],
        [
            'cpf'  => '22222222222',
            'equip' => ['Notebook', 'Dell', 'Inspiron 15', 'Não liga, teclado travado'],
            'os'   => ['aguardando_cliente', 'pendente', 'Fonte interna queimada. Orçamento: troca da fonte + limpeza geral.', 250.00, null],
        ],
        [
            'cpf'  => '33333333333',
            'equip' => ['Tablet', 'Apple', 'iPad Air 5', 'Tela quebrada, touch não responde'],
            'os'   => ['analise', null, null, 0, null],
        ],
        [
            'cpf'  => '44444444444',
            'equip' => ['Smartphone', 'Apple', 'iPhone 14', 'Molhado, não carrega'],
            'os'   => ['em_teste', 'aprovado', 'Danos por umidade na placa-mãe. Limpeza ultrassônica e troca do conector de carga.', 420.00, 1],
        ],
        [
            'cpf'  => '55555555555',
            'equip' => ['Notebook', 'Lenovo', 'IdeaPad 3', 'Superaquecimento, desliga sozinho'],
            'os'   => ['finalizado', 'aprovado', 'Pasta térmica ressecada e cooler com defeito. Trocado cooler e pasta térmica.', 180.00, -2],
        ],
        [
            'cpf'  => '66666666666',
            'equip' => ['Smartphone', 'Motorola', 'Moto G84', 'Tela piscando, bateria inchada'],
            'os'   => ['aguardando_cliente', 'pendente', 'Bateria inchada causando pressão no display. Troca de bateria necessária.', 160.00, null],
        ],
        [
            'cpf'  => '77777777777',
            'equip' => ['Notebook', 'Acer', 'Aspire 5', 'HD com defeito, não inicia o sistema'],
            'os'   => ['execucao', 'aprovado', 'HD com setores defeituosos. Backup realizado, instalando novo SSD 480GB.', 320.00, 2],
        ],
        [
            'cpf'  => '88888888888',
            'equip' => ['Smartphone', 'Xiaomi', 'Redmi Note 12', 'Câmera não funciona, WiFi instável'],
            'os'   => ['analise', null, null, 0, null],
        ],
        [
            'cpf'  => '99999999999',
            'equip' => ['Tablet', 'Samsung', 'Galaxy Tab S7', 'Não carrega, conector solto'],
            'os'   => ['finalizado', 'aprovado', 'Conector USB-C danificado. Realizada troca do conector de carga.', 140.00, -1],
        ],
        [
            'cpf'  => '10111213141',
            'equip' => ['Notebook', 'HP', 'Pavilion 14', 'Derramamento de líquido, teclado danificado'],
            'os'   => ['aguardando_cliente', 'pendente', 'Teclado com curto-circuito por contato com líquido. Troca de teclado necessária.', 290.00, null],
        ],
    ];

    public function up(): void
    {
        foreach ($this->clientes as $dados) {
            $cliente = DB::table('clientes')->where('cpf_cnpj', $dados['cpf'])->first();

            if (! $cliente) {
                continue;
            }

            // Já tem OS vinculada — nada a fazer
            if (DB::table('ordens')->where('cliente_id', $cliente->id)->exists()) {
                continue;
            }

            // Reutiliza equipamento existente ou cria novo
            $equipamento = DB::table('equipamentos')->where('cliente_id', $cliente->id)->first();
            if (! $equipamento) {
                [$tipo, $marca, $modelo, $condicao] = $dados['equip'];
                $equipamentoId = DB::table('equipamentos')->insertGetId([
                    'cliente_id'       => $cliente->id,
                    'tipo'             => $tipo,
                    'marca'            => $marca,
                    'modelo'           => $modelo,
                    'condicao_entrada' => $condicao,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            } else {
                $equipamentoId = $equipamento->id;
            }

            [, , , $condicao] = $dados['equip'];
            [$status, $statusOrc, $diagnostico, $valorServico, $diasEntrega] = $dados['os'];

            $ano   = date('Y');
            $seq   = str_pad(DB::table('ordens')->count() + 1, 5, '0', STR_PAD_LEFT);
            $token = $this->gerarToken();

            $ordemId = DB::table('ordens')->insertGetId(array_filter([
                'numero'            => "OS{$ano}{$seq}",
                'token'             => $token,
                'cliente_id'        => $cliente->id,
                'equipamento_id'    => $equipamentoId,
                'status'            => $status,
                'problema_relatado' => $condicao,
                'status_orcamento'  => $statusOrc,
                'diagnostico'       => $diagnostico,
                'valor_servico'     => $valorServico,
                'valor_pecas'       => 0.00,
                'desconto'          => 0.00,
                'previsao_entrega'  => $diasEntrega !== null ? now()->addDays($diasEntrega)->toDateString() : null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ], fn($v) => $v !== null));

            DB::table('ordens')->where('id', $ordemId)
                ->update(['codigo_publico' => 'OS' . str_pad($ordemId, 5, '0', STR_PAD_LEFT)]);
        }
    }

    public function down(): void
    {
        $cpfs     = array_column($this->clientes, 'cpf');
        $clientes = DB::table('clientes')->whereIn('cpf_cnpj', $cpfs)->get();

        foreach ($clientes as $c) {
            DB::table('ordens')->where('cliente_id', $c->id)->delete();
        }
    }

    private function gerarToken(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $token = '';
            for ($i = 0; $i < 7; $i++) {
                $token .= $chars[random_int(0, 35)];
            }
        } while (DB::table('ordens')->where('token', $token)->exists());

        return $token;
    }
};
