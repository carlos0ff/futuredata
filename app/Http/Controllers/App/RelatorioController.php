<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;

use App\Models\Ordem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RelatorioController extends Controller
{
    public function index(Request $request): View
    {
        return $this->dashboard($request);
    }

    public function dashboard(Request $request): View
    {
        $mes = $request->integer('mes', now()->month);
        $ano = $request->integer('ano', now()->year);

        $stats = [
            'total'          => Ordem::count(),
            'em_aberto'      => Ordem::whereNotIn('status', ['finalizado', 'cancelado'])->count(),
            'finalizadas_mes'=> Ordem::where('status', 'finalizado')
                                    ->whereMonth('finalizado_em', $mes)
                                    ->whereYear('finalizado_em', $ano)
                                    ->count(),
            'faturamento'    => (float) Ordem::where('status', 'finalizado')
                                    ->whereMonth('finalizado_em', $mes)
                                    ->whereYear('finalizado_em', $ano)
                                    ->sum(DB::raw('valor_servico + valor_pecas - desconto')),
        ];

        $porStatus = Ordem::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $faturamentoMensal = Ordem::select(
                DB::raw('MONTH(finalizado_em) as mes'),
                DB::raw('SUM(valor_servico + valor_pecas - desconto) as total')
            )
            ->where('status', 'finalizado')
            ->whereYear('finalizado_em', $ano)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $recentes = Ordem::with(['cliente', 'equipamento'])
            ->latest()
            ->limit(10)
            ->get();

        return view('app.relatorios.index', compact(
            'stats', 'porStatus', 'faturamentoMensal', 'recentes', 'mes', 'ano'
        ));
    }

    public function clientes(Request $request): View
    {
        return redirect()->route('app.relatorios.index');
    }

    public function ordensServico(Request $request): View
    {
        return redirect()->route('app.relatorios.index');
    }

    public function financeiro(Request $request): View
    {
        return redirect()->route('app.financeiro.index');
    }

    public function tecnicos(Request $request): View
    {
        return redirect()->route('app.relatorios.index');
    }
}
