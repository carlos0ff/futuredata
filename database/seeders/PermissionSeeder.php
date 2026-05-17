<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ── Definição das permissões ─────────────────
        $permissions = [
            // Sistema
            ['group' => 'sistema',    'slug' => 'acesso_total',          'name' => 'Acesso Total',           'description' => 'Acesso irrestrito a todo o sistema.'],
            ['group' => 'sistema',    'slug' => 'gerenciar_usuarios',     'name' => 'Gerenciar Usuários',     'description' => 'Criar, editar e remover usuários.'],
            ['group' => 'sistema',    'slug' => 'gerenciar_permissoes',   'name' => 'Gerenciar Permissões',   'description' => 'Atribuir e revogar permissões.'],
            ['group' => 'sistema',    'slug' => 'acessar_configuracoes',  'name' => 'Acessar Configurações',  'description' => 'Configurações gerais do sistema.'],
            ['group' => 'sistema',    'slug' => 'acessar_logs',           'name' => 'Acessar Logs',           'description' => 'Visualizar logs de auditoria.'],
            // Ordens
            ['group' => 'ordens',     'slug' => 'gerenciar_ordens',       'name' => 'Gerenciar Ordens',       'description' => 'Acesso completo às ordens de serviço.'],
            ['group' => 'ordens',     'slug' => 'criar_ordens',           'name' => 'Criar Ordens',           'description' => 'Abrir novas ordens de serviço.'],
            ['group' => 'ordens',     'slug' => 'atualizar_ordens',       'name' => 'Atualizar Ordens',       'description' => 'Alterar status e dados de ordens.'],
            ['group' => 'ordens',     'slug' => 'adicionar_diagnostico',  'name' => 'Adicionar Diagnóstico',  'description' => 'Inserir diagnóstico técnico na OS.'],
            ['group' => 'ordens',     'slug' => 'visualizar_ordens',      'name' => 'Visualizar Ordens',      'description' => 'Ver detalhes das ordens de serviço.'],
            // Clientes
            ['group' => 'clientes',   'slug' => 'gerenciar_clientes',     'name' => 'Gerenciar Clientes',     'description' => 'Cadastrar e editar clientes.'],
            ['group' => 'clientes',   'slug' => 'visualizar_clientes',    'name' => 'Visualizar Clientes',    'description' => 'Ver listagem e dados dos clientes.'],
            // Relatórios
            ['group' => 'relatorios', 'slug' => 'visualizar_relatorios',  'name' => 'Visualizar Relatórios',  'description' => 'Acessar relatórios e métricas.'],
            // Portal
            ['group' => 'portal',     'slug' => 'visualizar_propria_os',  'name' => 'Visualizar Própria OS',  'description' => 'Cliente vê apenas suas ordens.'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(['slug' => $perm['slug']], $perm);
        }

        // ── Atribuição de permissões por role ────────
        $mapa = [
            'dev'       => ['acesso_total','gerenciar_usuarios','gerenciar_permissoes','acessar_configuracoes','acessar_logs','gerenciar_ordens','criar_ordens','atualizar_ordens','adicionar_diagnostico','visualizar_ordens','gerenciar_clientes','visualizar_clientes','visualizar_relatorios'],
            'admin'     => ['gerenciar_ordens','criar_ordens','atualizar_ordens','visualizar_ordens','gerenciar_clientes','visualizar_clientes','visualizar_relatorios'],
            'tecnico'   => ['atualizar_ordens','adicionar_diagnostico','visualizar_ordens'],
            'atendente' => ['criar_ordens','visualizar_ordens','visualizar_clientes'],
            'cliente'   => ['visualizar_propria_os'],
        ];

        foreach ($mapa as $roleSlug => $permSlugs) {
            $role = Role::where('slug', $roleSlug)->first();
            if (! $role) continue;

            $ids = Permission::whereIn('slug', $permSlugs)->pluck('id');
            $role->permissions()->sync($ids);
        }
    }
}
