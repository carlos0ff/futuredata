<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/** Redirect raiz → login */
Route::redirect('/', '/auth/entrar')->name('home');
