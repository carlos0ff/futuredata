<?php

use Illuminate\Support\Facades\Route;

/** Auth Controllers  **/
use App\Http\Controllers\Auth\AuthController;


/**
 * Auth Routes - Grupo de rotas de autenticação
 * Todas as rotas deste grupo terão prefixo "auth"
*/
Route::prefix('auth')->middleware('guest')->group(function(){

    /**  Rota de login **/
    Route::get('/entrar', [AuthController::class, "index"])->name('auth.login.form');
    Route::post('/entrar', [AuthController::class, "authenticate"])->name('auth.login');

    /** Recuperação de senha **/
    Route::get('/recuperar', [AuthController::class, "index"])->name('auth.forget.form');
    Route::post('/recuperar', [AuthController::class, "index"])->name('auth.forget');
});

// GET /auth/sair → redireciona para login (para links directos)
Route::redirect('/auth/sair', '/auth/entrar');

// POST /auth/sair → logout real (usado pelo formulário da sidebar)
Route::post('/auth/sair', [AuthController::class, 'sair'])->name('auth.sair')->middleware('auth');