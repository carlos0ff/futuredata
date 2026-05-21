<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Portal\LoginController as PortalLoginController;
use App\Http\Controllers\Portal\PortalController;
use App\Http\Controllers\Portal\MessageController;


/*
|--------------------------------------------------------------------------
| Rotas de autenticação — staff (admin / gerente / técnico / atendente)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->name('auth.')->middleware('guest')->group(function () {

    Route::get('/entrar',     [AuthController::class, 'index'])->name('entrar');
    Route::post('/entrar',    [AuthController::class, 'authenticate'])->name('entrar.post');

    Route::get('/recuperar',  [AuthController::class, 'recuperar'])->name('recuperar');
    Route::post('/recuperar', [AuthController::class, 'recuperarPost'])->name('recuperar.post');
});

Route::post('/auth/sair', [AuthController::class, 'sair'])->name('auth.sair')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rotas de autenticação — portal do cliente
|--------------------------------------------------------------------------
*/
Route::prefix('portal')->name('portal.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/entrar',  [PortalLoginController::class, 'index'])->name('entrar');
        Route::post('/entrar', [PortalLoginController::class, 'authenticate'])->name('entrar.post');
    });

    Route::post('/sair', [PortalLoginController::class, 'sair'])
        ->name('sair')
        ->middleware('auth');

    /** Dashboard do portal (autenticado) */
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [PortalController::class, 'index'])->name('index');
        Route::get('/os/{ordemServico}', [PortalController::class, 'show'])->name('show');

        Route::prefix('mensagens')->name('mensagens.')->group(function () {
            Route::get('/', [MessageController::class, 'index'])->name('index');
            Route::post('/', [MessageController::class, 'store'])->name('store');
        });
    });
});

/** Acesso público via token curto (sem login) */
Route::get('/r/{token}', [PortalController::class, 'showByToken'])->name('portal.token');
