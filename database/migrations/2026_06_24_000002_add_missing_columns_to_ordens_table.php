<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona colunas que podem estar faltando no banco de produção.
 * O schema base usa hasTable() então colunas adicionadas após o deploy
 * inicial não existem em produção. Cada adição verifica hasColumn().
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordens', function (Blueprint $table) {
            if (! Schema::hasColumn('ordens', 'tecnico_id')) {
                $table->foreignId('tecnico_id')->nullable()->constrained('users')->nullOnDelete()->after('equipamento_id');
            }
            if (! Schema::hasColumn('ordens', 'status_orcamento')) {
                $table->enum('status_orcamento', ['pendente', 'aprovado', 'recusado'])->nullable()->after('status');
            }
            if (! Schema::hasColumn('ordens', 'diagnostico')) {
                $table->text('diagnostico')->nullable()->after('problema_relatado');
            }
            if (! Schema::hasColumn('ordens', 'solucao')) {
                $table->text('solucao')->nullable()->after('diagnostico');
            }
            if (! Schema::hasColumn('ordens', 'observacoes')) {
                $table->text('observacoes')->nullable()->after('desconto');
            }
            if (! Schema::hasColumn('ordens', 'previsao_entrega')) {
                $table->date('previsao_entrega')->nullable()->after('observacoes');
            }
            if (! Schema::hasColumn('ordens', 'finalizado_em')) {
                $table->timestamp('finalizado_em')->nullable()->after('previsao_entrega');
            }
        });
    }

    public function down(): void
    {
        $cols = ['finalizado_em', 'previsao_entrega', 'observacoes', 'solucao', 'diagnostico', 'status_orcamento', 'tecnico_id'];

        Schema::table('ordens', function (Blueprint $table) use ($cols) {
            $existing = array_filter($cols, fn($c) => Schema::hasColumn('ordens', $c));
            if ($existing) {
                $table->dropColumn(array_values($existing));
            }
        });
    }
};
