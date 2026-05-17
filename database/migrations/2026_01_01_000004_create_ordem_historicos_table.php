<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordem_historicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordem_id')->constrained('ordens')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status_anterior', 30)->nullable();
            $table->string('status_novo', 30);
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->index('ordem_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordem_historicos');
    }
};
