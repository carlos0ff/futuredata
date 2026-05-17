<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordem_id')->constrained('ordens')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tipo', ['tecnico', 'cliente'])->default('tecnico');
            $table->text('conteudo');
            $table->timestamp('lida_em')->nullable();
            $table->timestamps();

            $table->index(['ordem_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensagens');
    }
};
