<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Ordem;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_ordens'   => Ordem::count(),
            'em_aberto'      => Ordem::whereNotIn('status', ['finalizado', 'cancelado'])->count(),
            'finalizadas'    => Ordem::where('status', 'finalizado')->count(),
            'total_clientes' => Cliente::count(),
            'tecnicos'       => User::count(),
        ];

        $recentes = Ordem::with(['cliente', 'equipamento', 'tecnico'])
            ->latest()
            ->limit(8)
            ->get();

        return view('app.dashboard.index', compact('stats', 'recentes'));
    }
}
