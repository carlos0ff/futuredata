<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('ordens', 'codigo_publico')) {
            Schema::table('ordens', function (Blueprint $table) {
                $table->string('codigo_publico', 20)->unique()->nullable()->after('numero');
            });
        }

        if (! Schema::hasColumn('ordens', 'token')) {
            Schema::table('ordens', function (Blueprint $table) {
                $table->string('token', 10)->unique()->nullable()->after('codigo_publico');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ordens', 'token')) {
            Schema::table('ordens', function (Blueprint $table) {
                $table->dropColumn('token');
            });
        }

        if (Schema::hasColumn('ordens', 'codigo_publico')) {
            Schema::table('ordens', function (Blueprint $table) {
                $table->dropColumn('codigo_publico');
            });
        }
    }
};
