<?php

use Illuminate\Support\Facades\Route;


/**
 * Auth - Autenticação
 */
Route::middleware('auth')->prefix('auth')->name('auth.')->group(function () {

    /** Entrar | Login | Get **/
    Route::get('/entrar', [AuthController::class, 'index'])->name('entrar');

    /** Entrar | Login | Post **/
    Route::post('/entrar', [AuthController::class, 'authenticate'])->name('entrar.post');
});


