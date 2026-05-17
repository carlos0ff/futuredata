<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Portal\MessageController;
use App\Http\Controllers\Portal\PortalController;

// ── Rotas públicas (sem autenticação) ──────────────────────────────────────
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/{codigo}', [PortalController::class, 'show'])->name('show');
    Route::post('/{codigo}/mensagem', [PortalController::class, 'storeMessage'])->name('message.store');
    Route::post('/{codigo}/orcamento', [PortalController::class, 'orcamento'])->name('orcamento');
});

// ── Rotas autenticadas ──────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', [PortalController::class, 'index'])->name('index');

    Route::prefix('mensagens')->name('mensagens.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::post('/', [MessageController::class, 'store'])->name('store');
    });
});
