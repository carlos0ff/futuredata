<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bot_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->nullable()->index();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('ordem_id')->nullable()->constrained('ordens')->nullOnDelete();
            $table->string('channel', 20)->default('whatsapp'); // whatsapp | portal
            $table->string('state', 50)->default('idle');
            $table->json('context')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();

            $table->unique(['phone', 'channel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_sessions');
    }
};
