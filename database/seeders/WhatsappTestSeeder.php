<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\Ordem;
use Illuminate\Database\Seeder;

class WhatsappTestSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            [
                'cliente' => [
                    'nome'      => 'Carlos Junior',
                    'email'     => 'carlos@teste.com',
                    'telefone'  => '5581994821792', // número real para teste no WhatsApp
                    'cpf_cnpj'  => '12345678901',
                    'cidade'    => 'Recife',
                    'estado'    => 'PE',
                ],
                'equipamento' => [
                    'tipo'          => 'Smartphone',
                    'marca'         => 'Samsung',
                    'modelo'        => 'Galaxy S23',
                    'condicao_entrada' => 'Tela trincada, não liga',
                ],
                'ordem' => [
                    'status'           => 'execucao',
                    'status_orcamento' => 'aprovado',
                    'diagnostico'      => 'Display danificado e bateria com mau contato. Necessário troca de display e limpeza da placa.',
                    'total'            => 380.00,
                    'previsao_entrega' => now()->addDays(3),
                ],
            ],
            [
                'cliente' => [
                    'nome'      => 'Ana Lima',
                    'email'     => 'ana.lima@teste.com',
                    'telefone'  => '5581991000001',
                    'cpf_cnpj'  => '23456789012',
                    'cidade'    => 'Recife',
                    'estado'    => 'PE',
                ],
                'equipamento' => [
                    'tipo'          => 'Notebook',
                    'marca'         => 'Dell',
                    'modelo'        => 'Inspiron 15',
                    'condicao_entrada' => 'Não liga, teclado com teclas travadas',
                ],
                'ordem' => [
                    'status'           => 'aguardando_cliente',
                    'status_orcamento' => 'pendente',
                    'diagnostico'      => 'Fonte interna queimada. Orçamento: troca da fonte + limpeza geral.',
                    'total'            => 250.00,
                ],
            ],
            [
                'cliente' => [
                    'nome'      => 'Pedro Souza',
                    'email'     => 'pedro.souza@teste.com',
                    'telefone'  => '5581991000002',
                    'cpf_cnpj'  => '34567890123',
                    'cidade'    => 'Olinda',
                    'estado'    => 'PE',
                ],
                'equipamento' => [
                    'tipo'          => 'Tablet',
                    'marca'         => 'Apple',
                    'modelo'        => 'iPad Air 5',
                    'condicao_entrada' => 'Tela quebrada, touch não responde',
                ],
                'ordem' => [
                    'status'           => 'analise',
                    'status_orcamento' => null,
                    'diagnostico'      => null,
                    'total'            => 0,
                ],
            ],
            [
                'cliente' => [
                    'nome'      => 'Maria Santos',
                    'email'     => 'maria.santos@teste.com',
                    'telefone'  => '5581991000003',
                    'cpf_cnpj'  => '45678901234',
                    'cidade'    => 'Caruaru',
                    'estado'    => 'PE',
                ],
                'equipamento' => [
                    'tipo'          => 'Smartphone',
                    'marca'         => 'iPhone',
                    'modelo'        => 'iPhone 14',
                    'condicao_entrada' => 'Molhado, não carrega',
                ],
                'ordem' => [
                    'status'           => 'em_teste',
                    'status_orcamento' => 'aprovado',
                    'diagnostico'      => 'Danos por umidade na placa-mãe. Realizada limpeza ultrassônica e troca do conector de carga.',
                    'total'            => 420.00,
                    'previsao_entrega' => now()->addDays(1),
                ],
            ],
            [
                'cliente' => [
                    'nome'      => 'João Ferreira',
                    'email'     => 'joao.ferreira@teste.com',
                    'telefone'  => '5581991000004',
                    'cpf_cnpj'  => '56789012345',
                    'cidade'    => 'Recife',
                    'estado'    => 'PE',
                ],
                'equipamento' => [
                    'tipo'          => 'Notebook',
                    'marca'         => 'Lenovo',
                    'modelo'        => 'IdeaPad 3',
                    'condicao_entrada' => 'Superaquecimento, desliga sozinho',
                ],
                'ordem' => [
                    'status'           => 'finalizado',
                    'status_orcamento' => 'aprovado',
                    'diagnostico'      => 'Pasta térmica ressecada e cooler com defeito. Trocado cooler e pasta térmica.',
                    'total'            => 180.00,
                    'previsao_entrega' => now()->subDays(2),
                ],
            ],
            [
                'cliente' => [
                    'nome'      => 'Fernanda Costa',
                    'email'     => 'fernanda.costa@teste.com',
                    'telefone'  => '5581991000005',
                    'cpf_cnpj'  => '67890123456',
                    'cidade'    => 'Recife',
                    'estado'    => 'PE',
                ],
                'equipamento' => [
                    'tipo'          => 'Smartphone',
                    'marca'         => 'Motorola',
                    'modelo'        => 'Moto G84',
                    'condicao_entrada' => 'Tela piscando, bateria inchaçada',
                ],
                'ordem' => [
                    'status'           => 'aguardando_cliente',
                    'status_orcamento' => 'pendente',
                    'diagnostico'      => 'Bateria inchada causando pressão no display. Troca de bateria necessária.',
                    'total'            => 160.00,
                ],
            ],
            [
                'cliente' => [
                    'nome'      => 'Roberto Alves',
                    'email'     => 'roberto.alves@teste.com',
                    'telefone'  => '5581991000006',
                    'cpf_cnpj'  => '78901234567',
                    'cidade'    => 'Paulista',
                    'estado'    => 'PE',
                ],
                'equipamento' => [
                    'tipo'          => 'Notebook',
                    'marca'         => 'Acer',
                    'modelo'        => 'Aspire 5',
                    'condicao_entrada' => 'HD com defeito, não inicia o sistema',
                ],
                'ordem' => [
                    'status'           => 'execucao',
                    'status_orcamento' => 'aprovado',
                    'diagnostico'      => 'HD com setores defeituosos. Backup realizado, aguardando instalação do novo SSD.',
                    'total'            => 320.00,
                    'previsao_entrega' => now()->addDays(2),
                ],
            ],
            [
                'cliente' => [
                    'nome'      => 'Juliana Mendes',
                    'email'     => 'juliana.mendes@teste.com',
                    'telefone'  => '5581991000007',
                    'cpf_cnpj'  => '89012345678',
                    'cidade'    => 'Jaboatão',
                    'estado'    => 'PE',
                ],
                'equipamento' => [
                    'tipo'          => 'Smartphone',
                    'marca'         => 'Xiaomi',
                    'modelo'        => 'Redmi Note 12',
                    'condicao_entrada' => 'Câmera não funciona, WiFi instável',
                ],
                'ordem' => [
                    'status'           => 'analise',
                    'status_orcamento' => null,
                    'diagnostico'      => null,
                    'total'            => 0,
                ],
            ],
            [
                'cliente' => [
                    'nome'      => 'Lucas Oliveira',
                    'email'     => 'lucas.oliveira@teste.com',
                    'telefone'  => '5581991000008',
                    'cpf_cnpj'  => '90123456789',
                    'cidade'    => 'Recife',
                    'estado'    => 'PE',
                ],
                'equipamento' => [
                    'tipo'          => 'Tablet',
                    'marca'         => 'Samsung',
                    'modelo'        => 'Galaxy Tab S7',
                    'condicao_entrada' => 'Não carrega, conector solto',
                ],
                'ordem' => [
                    'status'           => 'finalizado',
                    'status_orcamento' => 'aprovado',
                    'diagnostico'      => 'Conector USB-C danificado. Realizada troca do conector de carga.',
                    'total'            => 140.00,
                ],
            ],
            [
                'cliente' => [
                    'nome'      => 'Patricia Gomes',
                    'email'     => 'patricia.gomes@teste.com',
                    'telefone'  => '5581991000009',
                    'cpf_cnpj'  => '01234567890',
                    'cidade'    => 'Recife',
                    'estado'    => 'PE',
                ],
                'equipamento' => [
                    'tipo'          => 'Notebook',
                    'marca'         => 'HP',
                    'modelo'        => 'Pavilion 14',
                    'condicao_entrada' => 'Derramamento de líquido, teclado danificado',
                ],
                'ordem' => [
                    'status'           => 'aguardando_cliente',
                    'status_orcamento' => 'pendente',
                    'diagnostico'      => 'Teclado com curto-circuito por contato com líquido. Troca de teclado necessária.',
                    'total'            => 290.00,
                ],
            ],
        ];

        foreach ($clientes as $dados) {
            $cliente = Cliente::updateOrCreate(
                ['cpf_cnpj' => $dados['cliente']['cpf_cnpj']],
                $dados['cliente']
            );

            $equipamento = Equipamento::create(array_merge(
                $dados['equipamento'],
                ['cliente_id' => $cliente->id]
            ));

            Ordem::create(array_merge(
                [
                    'cliente_id'    => $cliente->id,
                    'equipamento_id' => $equipamento->id,
                    'total'         => 0,
                ],
                array_filter($dados['ordem'], fn($v) => $v !== null)
            ));
        }

        $this->command->info('✅ 10 OS de teste criadas com sucesso!');
        $this->command->info('📱 Número para teste no WhatsApp: 5581994821792 (CPF: 12345678901)');
    }
}
