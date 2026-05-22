<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'carlos@futuredata.com.br'],
            [
                'name'     => 'Carlos Junior',
                'password' => Hash::make('future@2026'),
                'role'     => 'gerente',
            ]
        );

        User::firstOrCreate(
            ['email' => 'tecnico@futuredata.com.br'],
            [
                'name'     => 'Técnico',
                'password' => Hash::make('tecnico@123'),
                'role'     => 'tecnico',
            ]
        );
    }
}
