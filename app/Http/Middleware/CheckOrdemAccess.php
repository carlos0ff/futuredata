<?php

namespace App\Http\Middleware;

use App\Models\Ordem;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrdemAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Gerente tem acesso irrestrito
        if ($user->isGerente()) {
            return $next($request);
        }

        // Técnico só acessa OS atribuídas a ele
        $ordem = $request->route('ordem');

        if ($ordem instanceof Ordem && $ordem->tecnico_id !== $user->id) {
            abort(403, 'Você só pode acessar ordens atribuídas a você.');
        }

        return $next($request);
    }
}
