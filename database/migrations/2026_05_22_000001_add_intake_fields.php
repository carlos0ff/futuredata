<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('numero', 20)->nullable()->after('endereco');
            $table->string('complemento', 100)->nullable()->after('numero');
            $table->string('bairro', 100)->nullable()->after('complemento');
        });

        Schema::table('equipamentos', function (Blueprint $table) {
            $table->string('forma_entrada', 30)->nullable()->after('condicao_entrada');
            $table->string('estado_fisico')->nullable()->after('forma_entrada');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['numero', 'complemento', 'bairro']);
        });
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->dropColumn(['forma_entrada', 'estado_fisico']);
        });
    }
};
