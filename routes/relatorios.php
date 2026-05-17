<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RelatorioController;

// Relatórios: apenas gerente
Route::middleware(['auth', 'role:gerente'])
    ->prefix('relatorios')
    ->name('relatorios.')
    ->group(function () {
        Route::get('/', [RelatorioController::class, 'dashboard'])->name('dashboard');
    });
