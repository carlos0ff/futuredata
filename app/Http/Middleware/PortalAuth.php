<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PortalAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('portal_cliente_id')) {
            return redirect()->route('portal.entrar')
                ->with('info', 'Faça login para acessar o portal.');
        }

        return $next($request);
    }
}
