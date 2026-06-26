<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adiciona campos para rastrear aprovação de orçamento via WhatsApp.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordens', function (Blueprint $table) {
            if (! Schema::hasColumn('ordens', 'orcamento_aprovado_em')) {
                $table->timestamp('orcamento_aprovado_em')->nullable()->after('status_orcamento');
            }
            if (! Schema::hasColumn('ordens', 'orcamento_aprovado_via')) {
                $table->string('orcamento_aprovado_via', 20)->nullable()->after('orcamento_aprovado_em');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ordens', function (Blueprint $table) {
            $cols = ['orcamento_aprovado_via', 'orcamento_aprovado_em'];
            $existing = array_filter($cols, fn ($c) => Schema::hasColumn('ordens', $c));
            if ($existing) {
                $table->dropColumn(array_values($existing));
            }
        });
    }
};
