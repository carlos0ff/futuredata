<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordem_arquivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordem_id')->constrained('ordens')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nome_original');
            $table->string('caminho');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('tamanho')->default(0);
            $table->enum('tipo', [
                'os_assinada',
                'foto_entrada',
                'foto_saida',
                'orcamento',
                'laudo',
                'nota_fiscal',
                'outro',
            ])->default('outro');
            $table->string('descricao')->nullable();
            $table->timestamps();

            $table->index('ordem_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordem_arquivos');
    }
};
