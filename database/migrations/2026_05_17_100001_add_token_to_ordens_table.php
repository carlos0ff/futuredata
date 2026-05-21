<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordens', function (Blueprint $table) {
            $table->string('token', 10)->unique()->nullable()->after('codigo_publico');
        });

        // Gerar token para registos existentes
        $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $usados = [];

        DB::table('ordens')->orderBy('id')->each(function ($ordem) use ($chars, &$usados) {
            do {
                $token = '';
                for ($i = 0; $i < 7; $i++) {
                    $token .= $chars[random_int(0, 35)];
                }
            } while (in_array($token, $usados));

            $usados[] = $token;
            DB::table('ordens')->where('id', $ordem->id)->update(['token' => $token]);
        });
    }

    public function down(): void
    {
        Schema::table('ordens', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
};
