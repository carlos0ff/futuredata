<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Dev',          'slug' => 'dev',       'level' => 1, 'color' => 'purple', 'description' => 'Desenvolvedor com acesso total ao sistema.'],
            ['name' => 'Administrador','slug' => 'admin',     'level' => 2, 'color' => 'blue',   'description' => 'Gerencia ordens, clientes e relatórios.'],
            ['name' => 'Técnico',      'slug' => 'tecnico',   'level' => 3, 'color' => 'emerald','description' => 'Atualiza ordens e adiciona diagnósticos.'],
            ['name' => 'Atendente',    'slug' => 'atendente', 'level' => 4, 'color' => 'amber',  'description' => 'Cria ordens e visualiza clientes.'],
            ['name' => 'Cliente',      'slug' => 'cliente',   'level' => 5, 'color' => 'slate',  'description' => 'Visualiza apenas suas próprias ordens.'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
