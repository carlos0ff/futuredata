<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;

use App\Models\Ordem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FinanceiroController extends Controller
{
    public function index(Request $request): View
    {
        $mes = $request->integer('mes', now()->month);
        $ano = $request->integer('ano', now()->year);

        $faturamento = Ordem::where('status', 'finalizado')
            ->whereMonth('finalizado_em', $mes)
            ->whereYear('finalizado_em', $ano)
            ->sum(DB::raw('valor_servico + valor_pecas - desconto'));

        $qtdMes = Ordem::where('status', 'finalizado')
            ->whereMonth('finalizado_em', $mes)
            ->whereYear('finalizado_em', $ano)
            ->count();

        $stats = [
            'faturamento' => $faturamento,
            'finalizadas' => $qtdMes,
            'ticket_medio'=> $qtdMes > 0 ? round($faturamento / $qtdMes, 2) : 0,
            'em_aberto'   => Ordem::whereNotIn('status', ['finalizado', 'cancelado'])->count(),
            'total_ano'   => Ordem::where('status', 'finalizado')
                                ->whereYear('finalizado_em', $ano)
                                ->sum(DB::raw('valor_servico + valor_pecas - desconto')),
        ];

        $recentes = Ordem::with(['cliente', 'tecnico'])
            ->where('status', 'finalizado')
            ->whereMonth('finalizado_em', $mes)
            ->whereYear('finalizado_em', $ano)
            ->latest('finalizado_em')
            ->limit(10)
            ->get();

        $mensal = Ordem::select(
                DB::raw('MONTH(finalizado_em) as mes'),
                DB::raw('SUM(valor_servico + valor_pecas - desconto) as total'),
                DB::raw('COUNT(*) as qtd')
            )
            ->where('status', 'finalizado')
            ->whereYear('finalizado_em', $ano)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->keyBy('mes');

        return view('app.financeiro.index', compact('stats', 'recentes', 'mensal', 'mes', 'ano'));
    }

    public function receitas(Request $request): View
    {
        $mes = $request->integer('mes', now()->month);
        $ano = $request->integer('ano', now()->year);

        $ordens = Ordem::with(['cliente', 'tecnico'])
            ->where('status', 'finalizado')
            ->whereMonth('finalizado_em', $mes)
            ->whereYear('finalizado_em', $ano)
            ->latest('finalizado_em')
            ->paginate(20)
            ->withQueryString();

        $totalPeriodo = Ordem::where('status', 'finalizado')
            ->whereMonth('finalizado_em', $mes)
            ->whereYear('finalizado_em', $ano)
            ->sum(DB::raw('valor_servico + valor_pecas - desconto'));

        return view('app.financeiro.receitas', compact('ordens', 'totalPeriodo', 'mes', 'ano'));
    }

    public function despesas(Request $request): View
    {
        return view('app.financeiro.despesas');
    }

    public function caixa(Request $request): View
    {
        return redirect()->route('app.financeiro.index');
    }

    public function fluxoCaixa(Request $request): View
    {
        return redirect()->route('app.financeiro.index');
    }
}
