<?php

declare(strict_types=1);

use App\Http\Controllers\Webhook\WhatsappController;
use Illuminate\Support\Facades\Route;

/** Redirect raiz → login */
Route::redirect('/', '/auth/entrar')->name('home');

/** Webhook WhatsApp (sem autenticação nem CSRF) */
Route::post('/webhook/whatsapp', [WhatsappController::class, 'receive'])->name('webhook.whatsapp');
