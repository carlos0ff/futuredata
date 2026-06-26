<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordens', function (Blueprint $table) {
            if (! Schema::hasColumn('ordens', 'codigo_publico')) {
                $table->string('codigo_publico', 20)->unique()->nullable()->after('numero');
            }
            if (! Schema::hasColumn('ordens', 'token')) {
                $table->string('token', 10)->unique()->nullable()->after('codigo_publico');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ordens', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('ordens', 'codigo_publico') ? 'codigo_publico' : null,
                Schema::hasColumn('ordens', 'token') ? 'token' : null,
            ]));
        });
    }
};
