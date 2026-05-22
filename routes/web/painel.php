<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Gerente\ConfiguracaoController;
use App\Http\Controllers\Gerente\RelatorioController;


/**
 * 
 */
Route::middleware(['auth', 'role:gerente,admin'])->prefix('gerente')->name('gerente.')->group(function () {

    /** Dashboard do gerente **/
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /** Relatórios do gerente **/
    Route::prefix('relatorios')->name('relatorios.')->controller(RelatorioController::class)->group(function () {
        Route::get('/',          'index')->name('index');
        Route::get('/os',        'ordens')->name('os');
        Route::get('/financeiro','financeiro')->name('financeiro');
        Route::get('/tecnicos',  'tecnicos')->name('tecnicos');
        Route::get('/clientes',  'clientes')->name('clientes');
    });

    /** Configurações gerenciais **/
    Route::prefix('configuracoes')->name('configuracoes.')->controller(ConfiguracaoController::class)->group(function () {
        Route::get('/',        'index')->name('index');
        Route::put('/empresa', 'empresa')->name('empresa');
        Route::put('/sistema', 'sistema')->name('sistema');
    });
});