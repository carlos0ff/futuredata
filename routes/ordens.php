<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdemController;

Route::middleware(['auth'])
    ->prefix('ordens')
    ->name('ordens.')
    ->group(function () {

        // Ambos os roles
        Route::get('/', [OrdemController::class, 'index'])->name('index');
        Route::get('/nova', [OrdemController::class, 'create'])->name('create');
        Route::post('/', [OrdemController::class, 'store'])->name('store');

        // Com verificação de acesso por OS (técnico só acessa as suas)
        Route::middleware('ordem.access')->group(function () {
            Route::get('/{ordem}', [OrdemController::class, 'show'])->name('show');
            Route::get('/{ordem}/editar', [OrdemController::class, 'edit'])->name('edit');
            Route::put('/{ordem}', [OrdemController::class, 'update'])->name('update');
        });

        // Exclusivo para gerente
        Route::middleware('role:gerente')->group(function () {
            Route::delete('/{ordem}', [OrdemController::class, 'destroy'])->name('destroy');
        });
    });
