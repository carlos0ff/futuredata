<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('atendido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo_equipamento');
            $table->string('descricao_equipamento');
            $table->text('descricao_problema');
            $table->date('data_preferida');
            $table->enum('periodo', ['manha', 'tarde', 'qualquer'])->default('qualquer');
            $table->enum('status', ['pendente', 'confirmado', 'cancelado', 'concluido'])->default('pendente');
            $table->text('observacoes_loja')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
