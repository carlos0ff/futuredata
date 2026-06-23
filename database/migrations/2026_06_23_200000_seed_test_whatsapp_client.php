<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Insere cliente e OS de teste para validar o fluxo completo do bot WhatsApp.
 * Só insere se o CPF ainda não existir — seguro rodar em produção.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Evita duplicata em re-deploy
        if (DB::table('clientes')->where('cpf_cnpj', '00000000191')->exists()) {
            return;
        }

        // Cliente de teste
        $clienteId = DB::table('clientes')->insertGetId([
            'nome'       => 'Cliente Teste Bot',
            'telefone'   => '5581994821792',
            'cpf_cnpj'   => '00000000191',
            'email'      => 'teste@futuredata.com.br',
            'cidade'     => 'Recife',
            'estado'     => 'PE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Equipamento do cliente
        $equipamentoId = DB::table('equipamentos')->insertGetId([
            'cliente_id'      => $clienteId,
            'tipo'            => 'Smartphone',
            'marca'           => 'Samsung',
            'modelo'          => 'Galaxy S21',
            'numero_serie'    => 'SN-TESTE-001',
            'condicao_entrada'=> 'Tela trincada e não liga',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // OS com orçamento pendente
        $ano = date('Y');
        $seq = str_pad(DB::table('ordens')->count() + 1, 5, '0', STR_PAD_LEFT);

        DB::table('ordens')->insert([
            'numero'           => "OS{$ano}{$seq}",
            'cliente_id'       => $clienteId,
            'equipamento_id'   => $equipamentoId,
            'status'           => 'analise',
            'status_orcamento' => 'pendente',
            'problema_relatado'=> 'Tela trincada e aparelho não liga após queda.',
            'diagnostico'      => 'Display danificado (LCD + touch) e bateria com mau contato. Necessário troca de tela e revisão da bateria.',
            'valor_servico'    => 350.00,
            'valor_pecas'      => 0.00,
            'desconto'         => 0.00,
            'previsao_entrega' => now()->addDays(3)->toDateString(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    public function down(): void
    {
        $cliente = DB::table('clientes')->where('cpf_cnpj', '00000000191')->first();
        if ($cliente) {
            DB::table('ordens')->where('cliente_id', $cliente->id)->delete();
            DB::table('equipamentos')->where('cliente_id', $cliente->id)->delete();
            DB::table('clientes')->where('id', $cliente->id)->delete();
        }
    }
};
