<?php
declare(strict_types=1);

/** Web Routes **/
use Illuminate\Support\Facades\Route;

// Route::get('/', fn() => redirect()->route('ordens.index'));

/**
 * Rotas públicas
 **/
require __DIR__.'/web/public.php';

/**
 * Rotas de autenticação
 **/
require __DIR__.'/web/auth.php';

/**
 * Rotas da aplicação (usuários autenticados)
 **/
require __DIR__.'/web/app.php';
