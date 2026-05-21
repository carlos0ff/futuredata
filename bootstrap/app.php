<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Usuários não autenticados → login da equipe
        $middleware->redirectGuestsTo(fn () => route('auth.entrar'));

        // Usuários autenticados que acessam rotas guest → dashboard
        $middleware->redirectUsersTo(fn () => route('app.dashboard'));

        $middleware->alias([
            'role'         => \App\Http\Middleware\RoleMiddleware::class,
            'ordem.access' => \App\Http\Middleware\CheckOrdemAccess::class,
            'portal.auth'  => \App\Http\Middleware\PortalAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
