<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

Route::middleware(['auth', 'role:gerente'])
    ->prefix('app')
    ->name('app.')
    ->group(function () {

        Route::get('/', [AppController::class, 'index'])
            ->name('index');

    });
