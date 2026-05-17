<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordens', function (Blueprint $table) {
            $table->string('codigo_publico', 20)->unique()->nullable()->after('numero');
            $table->enum('status_orcamento', ['pendente', 'aprovado', 'recusado'])->nullable()->after('desconto');
        });

        // Populate existing records
        $ordens = DB::table('ordens')->orderBy('id')->get(['id']);
        foreach ($ordens as $ordem) {
            DB::table('ordens')->where('id', $ordem->id)->update([
                'codigo_publico' => 'OS' . str_pad($ordem->id, 5, '0', STR_PAD_LEFT),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('ordens', function (Blueprint $table) {
            $table->dropColumn(['codigo_publico', 'status_orcamento']);
        });
    }
};
