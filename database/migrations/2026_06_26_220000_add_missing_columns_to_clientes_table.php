<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona colunas que estão faltando em produção na tabela clientes.
 * O schema base usa hasTable(), então se a tabela já existia antes da
 * consolidação, essas colunas nunca foram criadas. Cada adição verifica
 * hasColumn() para ser idempotente.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (! Schema::hasColumn('clientes', 'data_nascimento')) {
                $table->date('data_nascimento')->nullable()->after('cpf_cnpj');
            }
            if (! Schema::hasColumn('clientes', 'endereco')) {
                $table->string('endereco')->nullable()->after('data_nascimento');
            }
            if (! Schema::hasColumn('clientes', 'numero')) {
                $table->string('numero', 20)->nullable()->after('endereco');
            }
            if (! Schema::hasColumn('clientes', 'complemento')) {
                $table->string('complemento', 100)->nullable()->after('numero');
            }
            if (! Schema::hasColumn('clientes', 'bairro')) {
                $table->string('bairro', 100)->nullable()->after('complemento');
            }
            if (! Schema::hasColumn('clientes', 'cidade')) {
                $table->string('cidade', 100)->nullable()->after('bairro');
            }
            if (! Schema::hasColumn('clientes', 'estado')) {
                $table->string('estado', 2)->nullable()->after('cidade');
            }
            if (! Schema::hasColumn('clientes', 'cep')) {
                $table->string('cep', 10)->nullable()->after('estado');
            }
            if (! Schema::hasColumn('clientes', 'observacoes')) {
                $table->text('observacoes')->nullable()->after('cep');
            }
        });
    }

    public function down(): void
    {
        $cols = ['data_nascimento', 'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'estado', 'cep', 'observacoes'];

        Schema::table('clientes', function (Blueprint $table) use ($cols) {
            $existing = array_filter($cols, fn ($c) => Schema::hasColumn('clientes', $c));
            if ($existing) {
                $table->dropColumn(array_values($existing));
            }
        });
    }
};
