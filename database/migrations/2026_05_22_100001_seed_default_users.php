<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $users = [
            [
                'name'       => 'Carlos Junior',
                'email'      => 'carlos@futuredata.com.br',
                'password'   => Hash::make('future@2026'),
                'role'       => 'gerente',
                'updated_at' => now(),
                'created_at' => now(),
            ],
            [
                'name'       => 'Técnico',
                'email'      => 'tecnico@futuredata.com.br',
                'password'   => Hash::make('tecnico@123'),
                'role'       => 'tecnico',
                'updated_at' => now(),
                'created_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }
    }

    public function down(): void
    {
        DB::table('users')->whereIn('email', [
            'carlos@futuredata.com.br',
            'tecnico@futuredata.com.br',
        ])->delete();
    }
};
