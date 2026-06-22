<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// ── Módulo: App ───────────────────────────────────────────────────────────────
use App\Http\Controllers\App\Dashboard\DashboardController;
use App\Http\Controllers\App\Perfil\PerfilController;
use App\Http\Controllers\App\Clientes\ClienteController;
use App\Http\Controllers\App\Clientes\EquipamentoController;
use App\Http\Controllers\App\Mensagens\MensagensController;
use App\Http\Controllers\App\Ordens\OrdemServicoController;
use App\Http\Controllers\App\Ordens\OrdemArquivoController;
use App\Http\Controllers\App\Estoque\EstoqueController;
use App\Http\Controllers\App\Financeiro\FinanceiroController;
use App\Http\Controllers\App\Relatorios\RelatorioController;
use App\Http\Controllers\App\Notificacoes\NotificacaoController;
use App\Http\Controllers\App\Usuarios\UsuarioController;
use App\Http\Controllers\App\Configuracoes\ConfiguracaoController;
use App\Http\Controllers\App\Configuracoes\WhatsappConfigController;
use App\Http\Controllers\App\Servicos\ServiceController;

Route::middleware('auth')->prefix('app')->name('app.')->group(function () {

    // ── Dashboard ─────────────────────────────────────────────────────────────
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ── Perfil ────────────────────────────────────────────────────────────────
    Route::prefix('perfil')->name('perfil.')->controller(PerfilController::class)->group(function () {
        Route::get('/',          'perfil')->name('index');
        Route::put('/atualizar', 'atualizarPerfil')->name('atualizar');
        Route::put('/senha',     'alterarSenha')->name('senha');
    });

    // ── Clientes ──────────────────────────────────────────────────────────────
    Route::middleware('role:gerente,admin,atendente')
        ->prefix('clientes')->name('clientes.')
        ->controller(ClienteController::class)
        ->group(function () {
            Route::get('/',                       'index')->name('index');
            Route::get('/novo',                   'create')->name('create');
            Route::post('/',                      'store')->name('store');
            Route::get('/{cliente}',              'show')->name('show');
            Route::get('/{cliente}/editar',       'edit')->name('edit');
            Route::put('/{cliente}',              'update')->name('update');
            Route::delete('/{cliente}',           'destroy')->name('destroy');
            Route::get('/{cliente}/equipamentos', 'equipamentos')->name('equipamentos');
        });

    // ── Equipamentos ──────────────────────────────────────────────────────────
    Route::prefix('equipamentos')->name('equipamentos.')->controller(EquipamentoController::class)
        ->group(function () {
            Route::get('/',                     'index')->name('index');
            Route::get('/novo',                 'create')->name('create');
            Route::post('/',                    'store')->name('store');
            Route::get('/{equipamento}',        'show')->name('show');
            Route::get('/{equipamento}/editar', 'edit')->name('edit');
            Route::put('/{equipamento}',        'update')->name('update');
            Route::delete('/{equipamento}',     'destroy')->name('destroy');
        });

    // ── Mensagens (chat interno ↔ WhatsApp) ───────────────────────────────────
    Route::prefix('mensagens')->name('mensagens.')->controller(MensagensController::class)
        ->group(function () {
            Route::get('/',                'index')->name('index');
            Route::post('/',               'store')->name('store');
            Route::get('/{ordem}/chat',    'mensagens')->name('chat');
        });

    // ── Ordens de Serviço ─────────────────────────────────────────────────────
    Route::prefix('ordens-servico')->name('os.')
        ->controller(OrdemServicoController::class)
        ->group(function () {
            Route::get('/',                        'index')->name('index');
            Route::get('/nova',                    'create')->name('create');
            Route::post('/',                       'store')->name('store');
            Route::get('/{ordemServico}',          'show')->name('show');
            Route::get('/{ordemServico}/editar',   'edit')->name('edit');
            Route::put('/{ordemServico}',          'update')->name('update');
            Route::delete('/{ordemServico}',       'destroy')->name('destroy');
            Route::put('/{ordemServico}/status',   'updateStatus')->name('status.update');
            Route::post('/{ordemServico}/mensagem','storeMensagem')->name('mensagem.store');
            Route::get('/{ordemServico}/imprimir', 'print')->name('print');
        });

    // ── Arquivos da OS ────────────────────────────────────────────────────────
    Route::prefix('ordens-servico/{ordemServico}/arquivos')->name('os.arquivos.')->controller(OrdemArquivoController::class)
        ->group(function () {
            Route::post('/',                  'store')->name('store');
            Route::get('/{arquivo}/download', 'download')->name('download');
            Route::delete('/{arquivo}',       'destroy')->name('destroy');
        });

    // ── Serviços ──────────────────────────────────────────────────────────────
    Route::prefix('servicos')->name('servicos.')->controller(ServiceController::class)
        ->group(function () {
            Route::get('/',          'index')->name('index');
            Route::post('/',         'store')->name('store');
            Route::put('/{service}', 'update')->name('update');
            Route::delete('/{service}', 'destroy')->name('destroy');
        });

    // ── Estoque ───────────────────────────────────────────────────────────────
    Route::get('/estoque', [EstoqueController::class, 'index'])->name('estoque.index');

    // ── Financeiro ────────────────────────────────────────────────────────────
    Route::middleware('role:gerente,admin')
        ->prefix('financeiro')->name('financeiro.')
        ->controller(FinanceiroController::class)
        ->group(function () {
            Route::get('/',               'index')->name('index');
            Route::get('/receitas',       'receitas')->name('receitas');
            Route::get('/despesas',       'despesas')->name('despesas');
            Route::get('/caixa',          'caixa')->name('caixa');
            Route::get('/fluxo-de-caixa', 'fluxoCaixa')->name('fluxo-caixa');
        });

    // ── Relatórios ────────────────────────────────────────────────────────────
    Route::middleware('role:gerente,admin')
        ->prefix('relatorios')->name('relatorios.')
        ->controller(RelatorioController::class)
        ->group(function () {
            Route::get('/',               'index')->name('index');
            Route::get('/clientes',       'clientes')->name('clientes');
            Route::get('/ordens-servico', 'ordensServico')->name('os');
            Route::get('/financeiro',     'financeiro')->name('financeiro');
            Route::get('/tecnicos',       'tecnicos')->name('tecnicos');
        });

    // ── Notificações ──────────────────────────────────────────────────────────
    Route::prefix('notificacoes')->name('notificacoes.')
        ->controller(NotificacaoController::class)
        ->group(function () {
            Route::get('/',                'index')->name('index');
            Route::get('/{id}/abrir',      'open')->name('open');
            Route::get('/{id}',            'show')->name('show');
            Route::put('/marcar-todas',    'markAllAsRead')->name('read-all');
            Route::delete('/limpar-todas', 'destroyAll')->name('destroy-all');
            Route::delete('/{id}',         'destroy')->name('destroy');
        });

    // ── Usuários ──────────────────────────────────────────────────────────────
    Route::middleware('role:gerente,admin')
        ->prefix('usuarios')->name('usuarios.')
        ->controller(UsuarioController::class)
        ->group(function () {
            Route::get('/',                 'index')->name('index');
            Route::get('/novo',             'create')->name('create');
            Route::post('/',                'store')->name('store');
            Route::get('/{usuario}/editar', 'edit')->name('edit');
            Route::put('/{usuario}',        'update')->name('update');
            Route::delete('/{usuario}',     'destroy')->name('destroy');
        });

    // ── Configurações ─────────────────────────────────────────────────────────
    Route::middleware('role:gerente,admin')
    ->prefix('configuracoes')->name('configuracoes.')
        ->controller(ConfiguracaoController::class)
        ->group(function () {
            Route::get('/',        'index')->name('index');
            Route::put('/empresa', 'empresa')->name('empresa');
            Route::put('/sistema', 'sistema')->name('sistema');
            Route::put('/email',   'email')->name('email');
        });

    // ── WhatsApp / Bot ────────────────────────────────────────────────────────
    Route::middleware('role:gerente,admin')
        ->prefix('whatsapp')->name('whatsapp.')
        ->controller(WhatsappConfigController::class)
        ->group(function () {
            Route::get('/',             'index')->name('index');
            Route::post('/save',        'save')->name('save');
            Route::get('/status',       'status')->name('status');
            Route::get('/qrcode',       'qrcode')->name('qrcode');
            Route::post('/bot-toggle',  'botToggle')->name('bot-toggle');
        });
});
