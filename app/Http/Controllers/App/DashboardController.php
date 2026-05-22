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
        $hoje = today();

        $totalOrdens     = Ordem::count();
        $abertas         = Ordem::whereNotIn('status', ['finalizado', 'cancelado'])->count();
        $finalizadas     = Ordem::where('status', 'finalizado')->count();
        $finalizadasHoje = Ordem::where('status', 'finalizado')->whereDate('updated_at', $hoje)->count();

        $atrasadas = Ordem::whereNotNull('previsao_entrega')
            ->whereNotIn('status', ['finalizado', 'cancelado'])
            ->whereDate('previsao_entrega', '<', $hoje)
            ->count();

        $urgentes = Ordem::with(['cliente', 'equipamento'])
            ->whereNotNull('previsao_entrega')
            ->whereNotIn('status', ['finalizado', 'cancelado'])
            ->whereDate('previsao_entrega', '<=', $hoje->copy()->addDay())
            ->orderBy('previsao_entrega')
            ->limit(5)
            ->get();

        $contagemStatus = Ordem::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Últimos 7 dias de criação
        $ultimos7dias = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dia = $hoje->copy()->subDays($i);
            $ultimos7dias->put($dia->format('d/m'), Ordem::whereDate('created_at', $dia)->count());
        }

        $semanaAtual    = Ordem::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $semanaAnterior = Ordem::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();
        $tendencia      = $semanaAnterior > 0
            ? round((($semanaAtual - $semanaAnterior) / $semanaAnterior) * 100)
            : ($semanaAtual > 0 ? 100 : 0);

        // Equipamentos em serviço (OS abertas com equipamento)
        $equipamentosEmServico = Ordem::with(['cliente', 'equipamento', 'tecnico'])
            ->whereNotNull('equipamento_id')
            ->whereNotIn('status', ['finalizado', 'cancelado'])
            ->latest()
            ->limit(10)
            ->get();

        // OS abertas por técnico
        $porTecnico = Ordem::selectRaw('tecnico_id, COUNT(*) as total')
            ->with('tecnico:id,name')
            ->whereNotNull('tecnico_id')
            ->whereNotIn('status', ['finalizado', 'cancelado'])
            ->groupBy('tecnico_id')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $stats = [
            'total_ordens'     => $totalOrdens,
            'em_aberto'        => $abertas,
            'finalizadas'      => $finalizadas,
            'finalizadas_hoje' => $finalizadasHoje,
            'atrasadas'        => $atrasadas,
            'total_clientes'   => Cliente::count(),
            'semana_atual'     => $semanaAtual,
            'tendencia'        => $tendencia,
            'taxa_conclusao'   => $totalOrdens > 0 ? round(($finalizadas / $totalOrdens) * 100) : 0,
        ];

        $recentes = Ordem::with(['cliente', 'equipamento', 'tecnico'])
            ->latest()
            ->limit(8)
            ->get();

        return view('app.dashboard.index', compact(
            'stats', 'recentes', 'contagemStatus', 'ultimos7dias',
            'porTecnico', 'equipamentosEmServico', 'urgentes'
        ));
    }
}
