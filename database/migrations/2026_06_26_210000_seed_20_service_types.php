<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $userId = DB::table('users')->value('id');
        if (! $userId) return;

        $now = now();

        $services = [
            ['name' => 'Diagnóstico de hardware',            'category' => 'Diagnóstico',           'base_price' => 80.00],
            ['name' => 'Diagnóstico de software',            'category' => 'Diagnóstico',           'base_price' => 60.00],
            ['name' => 'Formatação e reinstalação de SO',    'category' => 'Reparo de Software',    'base_price' => 150.00],
            ['name' => 'Remoção de vírus e malware',         'category' => 'Reparo de Software',    'base_price' => 120.00],
            ['name' => 'Desbloqueio e recuperação de senha', 'category' => 'Reparo de Software',    'base_price' => 80.00],
            ['name' => 'Substituição de tela',               'category' => 'Reparo de Hardware',    'base_price' => 250.00],
            ['name' => 'Substituição de bateria',            'category' => 'Reparo de Hardware',    'base_price' => 180.00],
            ['name' => 'Substituição de teclado',            'category' => 'Reparo de Hardware',    'base_price' => 200.00],
            ['name' => 'Troca de HD por SSD',                'category' => 'Reparo de Hardware',    'base_price' => 220.00],
            ['name' => 'Upgrade de memória RAM',             'category' => 'Reparo de Hardware',    'base_price' => 100.00],
            ['name' => 'Reparo de conector de carregamento', 'category' => 'Reparo de Hardware',    'base_price' => 160.00],
            ['name' => 'Limpeza interna completa',           'category' => 'Limpeza',               'base_price' => 90.00],
            ['name' => 'Limpeza anti-fungos e oxidação',     'category' => 'Limpeza',               'base_price' => 110.00],
            ['name' => 'Instalação e configuração de programas', 'category' => 'Instalação',        'base_price' => 70.00],
            ['name' => 'Configuração de rede e Wi-Fi',       'category' => 'Instalação',            'base_price' => 80.00],
            ['name' => 'Configuração de e-mail e contas',    'category' => 'Instalação',            'base_price' => 60.00],
            ['name' => 'Troca de pasta térmica',             'category' => 'Manutenção Preventiva', 'base_price' => 80.00],
            ['name' => 'Manutenção preventiva completa',     'category' => 'Manutenção Preventiva', 'base_price' => 150.00],
            ['name' => 'Recuperação de dados de HD/SSD',     'category' => 'Recuperação de Dados',  'base_price' => 300.00],
            ['name' => 'Recuperação de dados de celular',    'category' => 'Recuperação de Dados',  'base_price' => 350.00],
        ];

        foreach ($services as $service) {
            $exists = DB::table('services')
                ->where('user_id', $userId)
                ->where('name', $service['name'])
                ->exists();

            if (! $exists) {
                DB::table('services')->insert(array_merge($service, [
                    'user_id'    => $userId,
                    'status'     => 'ativo',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }

    public function down(): void {}
};
