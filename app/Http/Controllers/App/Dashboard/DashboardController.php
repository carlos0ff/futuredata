<?php

namespace App\Http\Controllers\App\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Mensagem;
use App\Models\Ordem;
use App\Models\OrdemHistorico;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Exibe o painel principal (dashboard) da plataforma interna.
 *
 * Responsabilidades:
 * - Agregar métricas de OS (abertas, atrasadas, urgentes, por status)
 * - Montar o feed de atividades recentes (histórico + novas OS + novos clientes)
 * - Exibir mensagens de clientes não lidas
 * - Mostrar gráfico de OS dos últimos 7 dias e distribuição por técnico
 */
class DashboardController extends Controller
{
    /**
     * Exibe o dashboard com todas as métricas e atividades recentes.
     */
    public function index(): View
    {
        $hoje = today();

        $totalOrdens           = Ordem::count();
        $abertas               = Ordem::whereNotIn('status', ['finalizado', 'cancelado'])->count();
        $finalizadas           = Ordem::where('status', 'finalizado')->count();
        $finalizadasHoje       = Ordem::where('status', 'finalizado')->whereDate('updated_at', $hoje)->count();
        $aguardandoAutorizacao = Ordem::where('status', 'aguardando_cliente')->count();

        $atrasadas = Ordem::whereNotNull('previsao_entrega')
            ->whereNotIn('status', ['finalizado', 'cancelado'])
            ->whereDate('previsao_entrega', '<', $hoje)
            ->count();

        $urgentes = Ordem::with(['cliente', 'equipamento'])
            ->whereNotNull('previsao_entrega')
            ->whereNotIn('status', ['finalizado', 'cancelado'])
            ->whereDate('previsao_entrega', '<=', $hoje->copy()->addDay())
            ->orderBy('previsao_entrega')
            ->limit(15)
            ->get();

        $contagemStatus = Ordem::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

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

        $equipamentosEmServico = Ordem::with(['cliente', 'equipamento', 'tecnico'])
            ->whereNotNull('equipamento_id')
            ->whereNotIn('status', ['finalizado', 'cancelado'])
            ->latest()
            ->limit(10)
            ->get();

        $porTecnico = Ordem::selectRaw('tecnico_id, COUNT(*) as total')
            ->with('tecnico:id,name')
            ->whereNotNull('tecnico_id')
            ->whereNotIn('status', ['finalizado', 'cancelado'])
            ->groupBy('tecnico_id')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $stats = [
            'total_ordens'           => $totalOrdens,
            'em_aberto'              => $abertas,
            'finalizadas'            => $finalizadas,
            'finalizadas_hoje'       => $finalizadasHoje,
            'atrasadas'              => $atrasadas,
            'aguardando_autorizacao' => $aguardandoAutorizacao,
            'total_clientes'         => Cliente::count(),
            'semana_atual'           => $semanaAtual,
            'semana_anterior'        => $semanaAnterior,
            'tendencia'              => $tendencia,
            'taxa_conclusao'         => $totalOrdens > 0 ? round(($finalizadas / $totalOrdens) * 100) : 0,
        ];

        $recentes = Ordem::with(['cliente', 'equipamento', 'tecnico'])
            ->latest()
            ->limit(8)
            ->get();

        $atividadesRecentes = $this->buildAtividades();

        $mensagensClientes = Mensagem::with(['ordem.cliente'])
            ->where('tipo', 'cliente')
            ->whereNull('lida_em')
            ->latest()
            ->limit(8)
            ->get();

        $totalMensagensNaoLidas = Mensagem::where('tipo', 'cliente')
            ->whereNull('lida_em')
            ->count();

        return view('app.dashboard.index', compact(
            'stats', 'recentes', 'contagemStatus', 'ultimos7dias',
            'porTecnico', 'equipamentosEmServico', 'urgentes',
            'atividadesRecentes', 'mensagensClientes', 'totalMensagensNaoLidas'
        ));
    }

    /**
     * Monta o feed de atividades recentes combinando:
     * - Histórico de mudanças de status
     * - OS criadas recentemente
     * - Clientes cadastrados recentemente
     *
     * Os eventos são ordenados por data decrescente e limitados a 15.
     */
    private function buildAtividades(): Collection
    {
        $historicos = OrdemHistorico::with(['ordem.cliente', 'usuario'])
            ->latest()
            ->limit(12)
            ->get()
            ->map(function ($h) {
                $tipo        = $h->status_novo === 'finalizado' ? 'os_finalizada' : 'status_alterado';
                $statusLabel = Ordem::STATUS[$h->status_novo]['label'] ?? $h->status_novo;
                return (object) [
                    'tipo'        => $tipo,
                    'descricao'   => 'OS ' . ($h->ordem?->numero ?? '—') . ': ' . $statusLabel,
                    'os_numero'   => $h->ordem?->numero,
                    'cliente'     => $h->ordem?->cliente?->nome,
                    'status_novo' => $h->status_novo,
                    'usuario'     => $h->usuario?->name ?? 'Sistema',
                    'created_at'  => $h->created_at,
                ];
            });

        $ordensNovas = Ordem::with(['cliente', 'tecnico'])
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn ($os) => (object) [
                'tipo'       => 'os_criada',
                'descricao'  => "OS {$os->numero} aberta",
                'os_numero'  => $os->numero,
                'cliente'    => $os->cliente?->nome,
                'usuario'    => $os->tecnico?->name ?? 'Sistema',
                'created_at' => $os->created_at,
            ]);

        $clientesNovos = Cliente::latest()
            ->limit(5)
            ->get()
            ->map(fn ($c) => (object) [
                'tipo'       => 'cliente_cadastrado',
                'descricao'  => "Cliente {$c->nome} cadastrado",
                'cliente'    => $c->nome,
                'usuario'    => 'Sistema',
                'created_at' => $c->created_at,
            ]);

        return $historicos
            ->concat($ordensNovas)
            ->concat($clientesNovos)
            ->sortByDesc('created_at')
            ->take(15)
            ->values();
    }
}
