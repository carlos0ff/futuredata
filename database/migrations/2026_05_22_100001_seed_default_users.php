<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        // Garantir que o enum aceita todos os valores necessários
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('gerente', 'tecnico', 'admin', 'atendente') NOT NULL DEFAULT 'tecnico'");

        $now = now();

        DB::table('users')->updateOrInsert(
            ['email' => 'carlos@futuredata.com.br'],
            [
                'name'       => 'Carlos Junior',
                'password'   => Hash::make('future@2026'),
                'role'       => 'gerente',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        DB::table('users')->updateOrInsert(
            ['email' => 'tecnico@futuredata.com.br'],
            [
                'name'       => 'Técnico',
                'password'   => Hash::make('tecnico@123'),
                'role'       => 'tecnico',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }

    public function down(): void
    {
        DB::table('users')->whereIn('email', [
            'carlos@futuredata.com.br',
            'tecnico@futuredata.com.br',
        ])->delete();
    }
};
