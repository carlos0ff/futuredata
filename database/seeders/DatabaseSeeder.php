<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Equipamento;
use App\Models\Ordem;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles e permissões primeiro
        $this->call([RoleSeeder::class, PermissionSeeder::class]);

        $roleGerente  = Role::where('slug', 'admin')->first();
        $roleDev      = Role::where('slug', 'dev')->first();
        $roleTecnico  = Role::where('slug', 'tecnico')->first();
        $roleAtend    = Role::where('slug', 'atendente')->first();

        // 2. Usuários
        $gerente = User::factory()->create([
            'name'     => 'Carlos Junior',
            'email'    => 'carlos@futuredata.com.br',
            'password' => bcrypt('future@2026'),
            'role'     => 'gerente',
        ]);
        $gerente->roles()->sync([$roleDev->id, $roleGerente->id]);

        $tecnicos = collect([
            ['name' => 'Rafael Técnico', 'email' => 'rafael@futuredata.com.br', 'password' => bcrypt('tecnico@123'), 'role' => 'tecnico'],
            ['name' => 'Amanda Técnica', 'email' => 'amanda@futuredata.com.br', 'password' => bcrypt('tecnico@123'), 'role' => 'tecnico'],
            ['name' => 'Bruno Técnico',  'email' => 'bruno@futuredata.com.br',  'password' => bcrypt('tecnico@123'), 'role' => 'tecnico'],
        ])->map(function ($dados) use ($roleTecnico) {
            $user = User::factory()->create($dados);
            $user->roles()->sync([$roleTecnico->id]);
            return $user;
        });

        $atendente = User::factory()->create([
            'name'     => 'Carla Atendente',
            'email'    => 'carla@futuredata.com.br',
            'password' => bcrypt('atend@123'),
            'role'     => 'tecnico',
        ]);
        $atendente->roles()->sync([$roleAtend->id]);

        $todosLotados = $tecnicos->push($gerente);

        // 3. Clientes e equipamentos
        $clientes = Cliente::factory(30)->create();
        $clientes->each(function (Cliente $cliente) {
            Equipamento::factory(fake()->numberBetween(1, 2))
                ->create(['cliente_id' => $cliente->id]);
        });

        // 4. Ordens distribuídas
        Ordem::factory(30)->create([
            'tecnico_id' => fn () => $todosLotados->random()->id,
        ]);
    }
}
