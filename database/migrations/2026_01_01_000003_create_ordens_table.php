<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20)->unique();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('equipamento_id')->nullable()->constrained('equipamentos')->nullOnDelete();
            $table->foreignId('tecnico_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', [
                'entrada',
                'analise',
                'execucao',
                'aguardando_cliente',
                'em_teste',
                'finalizado',
                'cancelado',
            ])->default('entrada');
            $table->text('problema_relatado');
            $table->text('diagnostico')->nullable();
            $table->text('solucao')->nullable();
            $table->decimal('valor_servico', 10, 2)->default(0);
            $table->decimal('valor_pecas', 10, 2)->default(0);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->text('observacoes')->nullable();
            $table->date('previsao_entrega')->nullable();
            $table->timestamp('finalizado_em')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordens');
    }
};
