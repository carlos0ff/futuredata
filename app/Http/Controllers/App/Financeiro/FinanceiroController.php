<?php

namespace App\Http\Controllers\App\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Ordem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Painel financeiro — faturamento, receitas e métricas por período.
 *
 * Acesso restrito a: role:gerente, admin
 *
 * Rotas (prefixo: app/financeiro):
 * - GET /              → index()   — painel principal com gráficos
 * - GET /receitas      → receitas() — listagem paginada de OS finalizadas
 * - GET /despesas      → despesas() — placeholder (a implementar)
 * - GET /caixa         → redireciona para index
 * - GET /fluxo-de-caixa → redireciona para index
 */
class FinanceiroController extends Controller
{
    /**
     * Painel financeiro do mês/ano selecionado.
     *
     * Exibe: faturamento total, ticket médio, variação vs. mês anterior,
     * breakdown (serviços/peças/descontos), top 5 clientes, gráfico anual.
     */
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
            'faturamento'  => $faturamento,
            'finalizadas'  => $qtdMes,
            'ticket_medio' => $qtdMes > 0 ? round($faturamento / $qtdMes, 2) : 0,
            'em_aberto'    => Ordem::whereNotIn('status', ['finalizado', 'cancelado'])->count(),
            'total_ano'    => Ordem::where('status', 'finalizado')
                                ->whereYear('finalizado_em', $ano)
                                ->sum(DB::raw('valor_servico + valor_pecas - desconto')),
        ];

        $mesPrev = $mes == 1 ? 12 : $mes - 1;
        $anoPrev = $mes == 1 ? $ano - 1 : $ano;
        $fatPrev = (float) Ordem::where('status', 'finalizado')
            ->whereMonth('finalizado_em', $mesPrev)
            ->whereYear('finalizado_em', $anoPrev)
            ->sum(DB::raw('valor_servico + valor_pecas - desconto'));

        $variacaoMes = $fatPrev > 0
            ? round((((float) $faturamento - $fatPrev) / $fatPrev) * 100)
            : null;

        $breakdown = Ordem::where('status', 'finalizado')
            ->whereMonth('finalizado_em', $mes)
            ->whereYear('finalizado_em', $ano)
            ->select(
                DB::raw('COALESCE(SUM(valor_servico), 0) as servicos'),
                DB::raw('COALESCE(SUM(valor_pecas), 0)   as pecas'),
                DB::raw('COALESCE(SUM(desconto), 0)      as descontos')
            )
            ->first();

        $topClientes = Ordem::with('cliente:id,nome')
            ->where('status', 'finalizado')
            ->whereMonth('finalizado_em', $mes)
            ->whereYear('finalizado_em', $ano)
            ->select(
                'cliente_id',
                DB::raw('SUM(valor_servico + valor_pecas - desconto) as total'),
                DB::raw('COUNT(*) as qtd')
            )
            ->groupBy('cliente_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $recentes = Ordem::with(['cliente', 'tecnico'])
            ->where('status', 'finalizado')
            ->whereMonth('finalizado_em', $mes)
            ->whereYear('finalizado_em', $ano)
            ->latest('finalizado_em')
            ->limit(15)
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

        return view('app.financeiro.index', compact(
            'stats', 'recentes', 'mensal', 'mes', 'ano',
            'breakdown', 'topClientes', 'variacaoMes', 'fatPrev'
        ));
    }

    /**
     * Listagem paginada de OS finalizadas no mês/ano selecionado.
     */
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

    /** Placeholder — módulo de despesas a implementar. */
    public function despesas(Request $request): View
    {
        return view('app.financeiro.despesas');
    }

    public function caixa(Request $request)
    {
        return redirect()->route('app.financeiro.index');
    }

    public function fluxoCaixa(Request $request)
    {
        return redirect()->route('app.financeiro.index');
    }
}
