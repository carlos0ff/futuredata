<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('ordens.index'));

require __DIR__ . '/auth.php';
require __DIR__ . '/app.php';
require __DIR__ . '/ordens.php';
require __DIR__ . '/portal.php';
require __DIR__ . '/relatorios.php';
