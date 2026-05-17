<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

/**
 * carlos@futuredata.com.br
 * future@2026
 */
Route::middleware('guest')
    ->prefix('auth')
    ->name('auth.')
    ->group(function () {

        Route::get('/entrar', [AuthController::class, 'entrar'])
            ->name('login');

        Route::post('/entrar', [AuthController::class, 'entrarPost'])
            ->name('entrar.post');

        Route::get('/recuperar', [AuthController::class, 'recuperar'])
            ->name('recuperar');

        Route::post('/recuperar', [AuthController::class, 'recuperarPost'])
            ->name('recuperar.post');
    });

Route::middleware('auth')
    ->post('/auth/sair', [AuthController::class, 'sair'])
    ->name('auth.sair');