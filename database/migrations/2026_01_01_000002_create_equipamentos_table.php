<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('tipo', 80);
            $table->string('marca', 80)->nullable();
            $table->string('modelo', 120)->nullable();
            $table->string('numero_serie', 100)->nullable();
            $table->string('patrimonio', 60)->nullable();
            $table->string('acessorios')->nullable();
            $table->string('condicao_entrada')->nullable();
            $table->boolean('em_garantia')->default(false);
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipamentos');
    }
};
