<?php

declare(strict_types=1);

use App\Http\Controllers\Webhook\N8nController;
use App\Http\Controllers\Webhook\WhatsappController;
use Illuminate\Support\Facades\Route;

/** Redirect raiz → login */
Route::redirect('/', '/auth/entrar')->name('home');

/** Webhook WhatsApp (sem autenticação nem CSRF) */
Route::post('/webhook/whatsapp', [WhatsappController::class, 'receive'])->name('webhook.whatsapp');
Route::get('/webhook/whatsapp', fn () => response()->json(['ok' => true]))->name('webhook.whatsapp.verify');

/** API interna para o n8n (token via header X-N8N-Token) */
Route::get('/api/n8n/cliente', [N8nController::class, 'buscarCliente'])->name('n8n.cliente');
