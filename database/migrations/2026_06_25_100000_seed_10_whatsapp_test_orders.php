<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Insere 10 clientes e OS de teste para validar o fluxo completo do bot WhatsApp.
 * Cada registro só é criado se o CPF ainda não existir — seguro em re-deploy.
 */
return new class extends Migration
{
    private array $clientes = [
        [
            'cpf'       => '11111111111',
            'nome'      => 'Carlos Junior',
            'telefone'  => '5581994821792', // número real do dono — teste direto pelo celular
            'email'     => 'carlos@futuredata.com.br',
            'cidade'    => 'Recife', 'estado' => 'PE',
            'equip'     => ['Smartphone', 'Samsung', 'Galaxy S23', 'Tela trincada, não liga'],
            'os'        => ['execucao', 'aprovado', 'Display danificado e bateria com mau contato. Necessário troca de display.', 380.00, 3],
        ],
        [
            'cpf'       => '22222222222',
            'nome'      => 'Ana Lima',
            'telefone'  => '5581991000001',
            'email'     => 'ana.lima@teste.com',
            'cidade'    => 'Recife', 'estado' => 'PE',
            'equip'     => ['Notebook', 'Dell', 'Inspiron 15', 'Não liga, teclado travado'],
            'os'        => ['aguardando_cliente', 'pendente', 'Fonte interna queimada. Orçamento: troca da fonte + limpeza geral.', 250.00, null],
        ],
        [
            'cpf'       => '33333333333',
            'nome'      => 'Pedro Souza',
            'telefone'  => '5581991000002',
            'email'     => 'pedro.souza@teste.com',
            'cidade'    => 'Olinda', 'estado' => 'PE',
            'equip'     => ['Tablet', 'Apple', 'iPad Air 5', 'Tela quebrada, touch não responde'],
            'os'        => ['analise', null, null, 0, null],
        ],
        [
            'cpf'       => '44444444444',
            'nome'      => 'Maria Santos',
            'telefone'  => '5581991000003',
            'email'     => 'maria.santos@teste.com',
            'cidade'    => 'Caruaru', 'estado' => 'PE',
            'equip'     => ['Smartphone', 'Apple', 'iPhone 14', 'Molhado, não carrega'],
            'os'        => ['em_teste', 'aprovado', 'Danos por umidade na placa-mãe. Limpeza ultrassônica e troca do conector de carga.', 420.00, 1],
        ],
        [
            'cpf'       => '55555555555',
            'nome'      => 'João Ferreira',
            'telefone'  => '5581991000004',
            'email'     => 'joao.ferreira@teste.com',
            'cidade'    => 'Recife', 'estado' => 'PE',
            'equip'     => ['Notebook', 'Lenovo', 'IdeaPad 3', 'Superaquecimento, desliga sozinho'],
            'os'        => ['finalizado', 'aprovado', 'Pasta térmica ressecada e cooler com defeito. Trocado cooler e pasta térmica.', 180.00, -2],
        ],
        [
            'cpf'       => '66666666666',
            'nome'      => 'Fernanda Costa',
            'telefone'  => '5581991000005',
            'email'     => 'fernanda.costa@teste.com',
            'cidade'    => 'Recife', 'estado' => 'PE',
            'equip'     => ['Smartphone', 'Motorola', 'Moto G84', 'Tela piscando, bateria inchada'],
            'os'        => ['aguardando_cliente', 'pendente', 'Bateria inchada causando pressão no display. Troca de bateria necessária.', 160.00, null],
        ],
        [
            'cpf'       => '77777777777',
            'nome'      => 'Roberto Alves',
            'telefone'  => '5581991000006',
            'email'     => 'roberto.alves@teste.com',
            'cidade'    => 'Paulista', 'estado' => 'PE',
            'equip'     => ['Notebook', 'Acer', 'Aspire 5', 'HD com defeito, não inicia o sistema'],
            'os'        => ['execucao', 'aprovado', 'HD com setores defeituosos. Backup realizado, instalando novo SSD 480GB.', 320.00, 2],
        ],
        [
            'cpf'       => '88888888888',
            'nome'      => 'Juliana Mendes',
            'telefone'  => '5581991000007',
            'email'     => 'juliana.mendes@teste.com',
            'cidade'    => 'Jaboatão', 'estado' => 'PE',
            'equip'     => ['Smartphone', 'Xiaomi', 'Redmi Note 12', 'Câmera não funciona, WiFi instável'],
            'os'        => ['analise', null, null, 0, null],
        ],
        [
            'cpf'       => '99999999999',
            'nome'      => 'Lucas Oliveira',
            'telefone'  => '5581991000008',
            'email'     => 'lucas.oliveira@teste.com',
            'cidade'    => 'Recife', 'estado' => 'PE',
            'equip'     => ['Tablet', 'Samsung', 'Galaxy Tab S7', 'Não carrega, conector solto'],
            'os'        => ['finalizado', 'aprovado', 'Conector USB-C danificado. Realizada troca do conector de carga.', 140.00, -1],
        ],
        [
            'cpf'       => '10111213141',
            'nome'      => 'Patricia Gomes',
            'telefone'  => '5581991000009',
            'email'     => 'patricia.gomes@teste.com',
            'cidade'    => 'Recife', 'estado' => 'PE',
            'equip'     => ['Notebook', 'HP', 'Pavilion 14', 'Derramamento de líquido, teclado danificado'],
            'os'        => ['aguardando_cliente', 'pendente', 'Teclado com curto-circuito por contato com líquido. Troca de teclado necessária.', 290.00, null],
        ],
    ];

    public function up(): void
    {
        foreach ($this->clientes as $dados) {
            if (DB::table('clientes')->where('cpf_cnpj', $dados['cpf'])->exists()) {
                continue;
            }

            $clienteId = DB::table('clientes')->insertGetId([
                'nome'       => $dados['nome'],
                'telefone'   => $dados['telefone'],
                'cpf_cnpj'   => $dados['cpf'],
                'email'      => $dados['email'],
                'cidade'     => $dados['cidade'],
                'estado'     => $dados['estado'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            [$tipo, $marca, $modelo, $condicao] = $dados['equip'];

            $equipamentoId = DB::table('equipamentos')->insertGetId([
                'cliente_id'       => $clienteId,
                'tipo'             => $tipo,
                'marca'            => $marca,
                'modelo'           => $modelo,
                'condicao_entrada' => $condicao,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            [$status, $statusOrc, $diagnostico, $valorServico, $diasEntrega] = $dados['os'];
            [, , , $condicao] = $dados['equip'];

            $ano   = date('Y');
            $seq   = str_pad(DB::table('ordens')->count() + 1, 5, '0', STR_PAD_LEFT);
            $token = $this->gerarToken();

            $ordemId = DB::table('ordens')->insertGetId(array_filter([
                'numero'            => "OS{$ano}{$seq}",
                'token'             => $token,
                'cliente_id'        => $clienteId,
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

    public function down(): void
    {
        $cpfs = array_column($this->clientes, 'cpf');
        $clientes = DB::table('clientes')->whereIn('cpf_cnpj', $cpfs)->get();

        foreach ($clientes as $c) {
            DB::table('ordens')->where('cliente_id', $c->id)->delete();
            DB::table('equipamentos')->where('cliente_id', $c->id)->delete();
        }

        DB::table('clientes')->whereIn('cpf_cnpj', $cpfs)->delete();
    }
};
