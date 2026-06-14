<?php

namespace App\Http\Controllers\App\Estoque;
use App\Http\Controllers\Controller;

use App\Models\Equipamento;
use App\Models\Ordem;
use Illuminate\View\View;

class EstoqueController extends Controller
{
    public function index(): View
    {
        $porTipo = Equipamento::select('tipo')
            ->selectRaw('count(*) as total')
            ->selectRaw('sum(em_garantia) as em_garantia')
            ->groupBy('tipo')
            ->orderByDesc('total')
            ->get();

        $stats = [
            'total'           => Equipamento::count(),
            'em_garantia'     => Equipamento::where('em_garantia', true)->count(),
            'aguardando'      => Ordem::where('status', 'finalizado')
                                    ->whereNull('finalizado_em')
                                    ->count(),
            'tipos'           => $porTipo->count(),
        ];

        $recentes = Equipamento::with('cliente')
            ->latest()
            ->limit(10)
            ->get();

        return view('app.estoque.index', compact('stats', 'porTipo', 'recentes'));
    }
}
