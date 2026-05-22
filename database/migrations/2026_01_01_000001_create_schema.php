<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('role', ['gerente', 'tecnico'])->default('tecnico')->after('email');
                $table->rememberToken();
                $table->timestamps();
            });
        } elseif (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['gerente', 'tecnico'])->default('tecnico')->after('email');
            });
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        if (! Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        if (! Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }

        if (! Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }

        if (! Schema::hasTable('job_batches')) {
            Schema::create('job_batches', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('name');
                $table->integer('total_jobs');
                $table->integer('pending_jobs');
                $table->integer('failed_jobs');
                $table->longText('failed_job_ids');
                $table->mediumText('options')->nullable();
                $table->integer('cancelled_at')->nullable();
                $table->integer('created_at');
                $table->integer('finished_at')->nullable();
            });
        }

        if (! Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        if (! Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('description')->nullable();
                $table->string('color', 30)->default('slate');
                $table->unsignedTinyInteger('level')->default(99);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('description')->nullable();
                $table->string('group', 60)->default('geral');
                $table->timestamps();
                $table->index('group');
            });
        }

        if (! Schema::hasTable('role_permission')) {
            Schema::create('role_permission', function (Blueprint $table) {
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
                $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
                $table->primary(['role_id', 'permission_id']);
            });
        }

        if (! Schema::hasTable('user_role')) {
            Schema::create('user_role', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
                $table->primary(['user_id', 'role_id']);
                $table->timestamp('assigned_at')->useCurrent();
            });
        }

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('clientes')) {
            Schema::create('clientes', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('email')->nullable();
                $table->string('telefone', 20)->nullable();
                $table->string('cpf_cnpj', 20)->nullable()->unique();
                $table->date('data_nascimento')->nullable();
                $table->string('endereco')->nullable();
                $table->string('numero', 20)->nullable();
                $table->string('complemento', 100)->nullable();
                $table->string('bairro', 100)->nullable();
                $table->string('cidade', 100)->nullable();
                $table->string('estado', 2)->nullable();
                $table->string('cep', 10)->nullable();
                $table->text('observacoes')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->index('nome');
                $table->index('email');
            });
        }

        if (! Schema::hasTable('equipamentos')) {
            Schema::create('equipamentos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
                $table->string('tipo', 80);
                $table->string('marca', 80)->nullable();
                $table->string('modelo', 120)->nullable();
                $table->string('numero_serie', 100)->nullable();
                $table->string('patrimonio', 60)->nullable();
                $table->string('acessorios')->nullable();
                $table->string('condicao_entrada')->nullable();
                $table->string('forma_entrada', 30)->nullable();
                $table->string('estado_fisico')->nullable();
                $table->boolean('em_garantia')->default(false);
                $table->text('observacoes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('ordens')) {
            Schema::create('ordens', function (Blueprint $table) {
                $table->id();
                $table->string('numero', 20)->unique();
                $table->string('codigo_publico', 20)->unique()->nullable();
                $table->string('token', 10)->unique()->nullable();
                $table->foreignId('cliente_id')->constrained('clientes');
                $table->foreignId('equipamento_id')->nullable()->constrained('equipamentos')->nullOnDelete();
                $table->foreignId('tecnico_id')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('status', [
                    'entrada', 'analise', 'execucao',
                    'aguardando_cliente', 'em_teste', 'finalizado', 'cancelado',
                ])->default('entrada');
                $table->enum('status_orcamento', ['pendente', 'aprovado', 'recusado'])->nullable();
                $table->text('problema_relatado');
                $table->text('diagnostico')->nullable();
                $table->text('solucao')->nullable();
                $table->decimal('valor_servico', 10, 2)->default(0);
                $table->decimal('valor_pecas', 10, 2)->default(0);
                $table->decimal('desconto', 10, 2)->default(0);
                $table->text('observacoes')->nullable();
                $table->date('previsao_entrega')->nullable();
                $table->timestamp('finalizado_em')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->index('status');
                $table->index('created_at');
            });
        }

        if (! Schema::hasTable('ordem_historicos')) {
            Schema::create('ordem_historicos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ordem_id')->constrained('ordens')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('status_anterior', 30)->nullable();
                $table->string('status_novo', 30);
                $table->text('observacao')->nullable();
                $table->timestamps();
                $table->index('ordem_id');
            });
        }

        if (! Schema::hasTable('mensagens')) {
            Schema::create('mensagens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ordem_id')->constrained('ordens')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('tipo', ['tecnico', 'cliente'])->default('tecnico');
                $table->text('conteudo');
                $table->timestamp('lida_em')->nullable();
                $table->timestamps();
                $table->index(['ordem_id', 'created_at']);
            });
        }

        if (! Schema::hasTable('ordem_arquivos')) {
            Schema::create('ordem_arquivos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ordem_id')->constrained('ordens')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('nome_original');
                $table->string('caminho');
                $table->string('mime_type', 100)->nullable();
                $table->unsignedBigInteger('tamanho')->default(0);
                $table->enum('tipo', [
                    'os_assinada', 'foto_entrada', 'foto_saida',
                    'orcamento', 'laudo', 'nota_fiscal', 'outro',
                ])->default('outro');
                $table->string('descricao')->nullable();
                $table->timestamps();
                $table->index('ordem_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ordem_arquivos');
        Schema::dropIfExists('mensagens');
        Schema::dropIfExists('ordem_historicos');
        Schema::dropIfExists('ordens');
        Schema::dropIfExists('equipamentos');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
