<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Portal\LoginController as PortalLoginController;
use App\Http\Controllers\Portal\PortalController;
use App\Http\Controllers\Portal\MessageController;

/**
 * 
 */
Route::prefix('auth')->name('auth.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/entrar',     [AuthController::class, 'index'])->name('entrar');
        Route::post('/entrar',    [AuthController::class, 'authenticate'])->name('entrar.post');

        Route::get('/recuperar',  [AuthController::class, 'recuperar'])->name('recuperar');
        Route::post('/recuperar', [AuthController::class, 'recuperarPost'])->name('recuperar.post');
    });

    Route::post('/sair', [AuthController::class, 'sair'])->name('sair')->middleware('auth');
});

/*
|--------------------------------------------------------------------------
| Portal do cliente — autenticação via CPF + data de nascimento (sessão)
|--------------------------------------------------------------------------
*/
Route::prefix('portal')->name('portal.')->group(function () {

    // Login / logout (sem middleware — lógica interna no controller)
    Route::get('/entrar',  [PortalLoginController::class, 'index'])->name('entrar');
    Route::post('/entrar', [PortalLoginController::class, 'authenticate'])->name('entrar.post');
    
    Route::post('/sair',   [PortalLoginController::class, 'sair'])->name('sair');

    // Páginas protegidas pelo middleware de sessão do portal
    Route::middleware('portal.auth')->group(function () {
        Route::get('/',              [PortalController::class, 'index'])->name('index');
        Route::get('/os/{ordem}',    [PortalController::class, 'show'])->name('show');

        Route::prefix('mensagens')->name('mensagens.')->group(function () {
            Route::get('/',  [MessageController::class, 'index'])->name('index');
            Route::post('/', [MessageController::class, 'store'])->name('store');
        });
    });
});

// Acesso público por token (WhatsApp / e-mail) — sem nenhum login
Route::get('/r/{token}', [PortalController::class, 'showByToken'])->name('portal.token');
