<?php

namespace App\Http\Controllers\App\Notificacoes;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use stdClass;

/**
 * Gerencia as notificações do usuário autenticado.
 *
 * Notificações são geradas pelos eventos:
 * - OrdemCriada         — nova OS aberta
 * - OrdemStatusAlterado — status de OS alterado
 * - MensagemPortal      — cliente enviou mensagem pelo portal
 *
 * A action `open()` marca como lida e redireciona para a URL da notificação.
 * O método `buildDemoNotification()` gera dados fictícios para a tela de preview.
 *
 * Rotas (prefixo: app/notificacoes):
 * - GET    /                  → index()
 * - GET    /{id}/abrir        → open()
 * - GET    /{id}              → show()
 * - PUT    /marcar-todas      → markAllAsRead()
 * - DELETE /limpar-todas      → destroyAll()
 * - DELETE /{id}              → destroy()
 */
class NotificacaoController extends Controller
{
    /**
     * Lista paginada de notificações com filtros de leitura e período.
     */
    public function index(Request $request): View
    {
        $user   = auth()->user();
        $filtro = $request->get('filtro', 'todas');

        $query = $user->notifications();

        match ($filtro) {
            'nao_lidas' => $query->whereNull('read_at'),
            'lidas'     => $query->whereNotNull('read_at'),
            default     => null,
        };

        $periodo = $request->get('periodo', 'tudo');
        match ($periodo) {
            'hoje'   => $query->whereDate('created_at', today()),
            'ontem'  => $query->whereDate('created_at', today()->subDay()),
            'semana' => $query->where('created_at', '>=', now()->subDays(7)->startOfDay()),
            'mes'    => $query->where('created_at', '>=', now()->subDays(30)->startOfDay()),
            default  => null,
        };

        $notificacoes  = $query->latest()->paginate(20)->withQueryString();
        $totalNaoLidas = $user->unreadNotifications()->count();

        return view('app.notificacoes.index', compact('notificacoes', 'totalNaoLidas', 'filtro', 'periodo'));
    }

    /**
     * Marca a notificação como lida e redireciona para a URL da ação.
     * IDs com prefixo "demo-" exibem dados fictícios sem persistência.
     */
    public function open(string $id): RedirectResponse|View
    {
        if (str_starts_with($id, 'demo-')) {
            $notificacao = $this->buildDemoNotification((int) substr($id, 5));
            return view('app.notificacoes.show', ['notificacao' => $notificacao, 'isDemo' => true]);
        }

        $notificacao = auth()->user()->notifications()->findOrFail($id);
        $notificacao->markAsRead();

        $url = $notificacao->data['url'] ?? route('app.notificacoes.index');

        return redirect($url);
    }

    /**
     * Exibe o detalhe de uma notificação e a marca como lida.
     */
    public function show(string $id): View
    {
        if (str_starts_with($id, 'demo-')) {
            $notificacao = $this->buildDemoNotification((int) substr($id, 5));
            return view('app.notificacoes.show', ['notificacao' => $notificacao, 'isDemo' => true]);
        }

        $notificacao = auth()->user()->notifications()->findOrFail($id);
        $notificacao->markAsRead();

        return view('app.notificacoes.show', ['notificacao' => $notificacao, 'isDemo' => false]);
    }

    /**
     * Marca todas as notificações não lidas como lidas.
     */
    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Todas as notificações foram marcadas como lidas.');
    }

    /**
     * Remove uma notificação específica.
     */
    public function destroy(string $id): RedirectResponse
    {
        auth()->user()->notifications()->findOrFail($id)->delete();

        return back()->with('success', 'Notificação removida.');
    }

    /**
     * Remove todas as notificações do usuário.
     */
    public function destroyAll(): RedirectResponse
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'Todas as notificações foram removidas.');
    }

    /**
     * Gera um objeto stdClass com dados fictícios para preview de notificação.
     * Usado apenas na tela de demonstração (IDs "demo-{n}").
     */
    private function buildDemoNotification(int $idx): stdClass
    {
        $nomes    = ['João Silva', 'Maria Oliveira', 'Pedro Santos', 'Ana Costa', 'Carlos Mendes',
                     'Fernanda Lima', 'Roberto Alves', 'Juliana Ferreira', 'Marcos Pereira', 'Luciana Santos'];
        $equips   = ['notebook Dell', 'iPhone 14', 'Samsung S23', 'MacBook Pro', 'PS5',
                     'impressora HP', 'tablet iPad', 'PC desktop', 'Apple Watch', 'Motorola Edge'];
        $servicos = ['troca de display', 'reparo da placa-mãe', 'troca de bateria',
                     'limpeza geral', 'formatação e reinstalação', 'troca de HD por SSD'];
        $waMsgs   = [
            'Oi, meu aparelho já ficou pronto? Posso buscar hoje à tarde?',
            'Qual o prazo estimado para o conserto?',
            'Vocês trabalham no sábado?',
            'Preciso do notebook urgente, tem previsão?',
            'Quanto vai custar no total com as peças?',
        ];
        $tipos   = ['whatsapp', 'aprovado', 'recusado', 'os_criada', 'os_status', 'mensagem_portal'];
        $statusL = ['Em execução', 'Aguardando peça', 'Em análise', 'Finalizado', 'Em teste'];

        $nome = $nomes[$idx % count($nomes)];
        $eq   = $equips[$idx % count($equips)];
        $sv   = $servicos[$idx % count($servicos)];
        $tipo = $tipos[$idx % count($tipos)];
        $num  = 'OS-2024-' . str_pad(100 + $idx, 3, '0', STR_PAD_LEFT);
        $val  = number_format([280, 320, 450, 480, 560, 680, 750, 890, 1200][$idx % 9], 2, ',', '.');

        [$titulo, $mensagem] = match ($tipo) {
            'whatsapp'        => ['Nova mensagem via WhatsApp', $waMsgs[$idx % count($waMsgs)]],
            'aprovado'        => ['Cliente aprovou o orçamento', "{$nome} aceitou o orçamento de R\$ {$val} para {$sv}."],
            'recusado'        => ['Cliente recusou o orçamento', "{$nome} não aprovou R\$ {$val}. Equipamento aguarda retirada."],
            'os_criada'       => ['Nova OS registrada', "OS aberta para {$nome} — {$eq} com defeito identificado."],
            'os_status'       => ['Status da OS atualizado', "OS {$num} movida para \"{$statusL[$idx % count($statusL)]}\"."],
            'mensagem_portal' => ['Mensagem no portal do cliente', 'Cliente enviou mensagem: "Qual o prazo para o reparo do meu equipamento?"'],
            default           => ['Notificação do sistema', 'Evento registrado automaticamente.'],
        };

        $minsAgo = [3, 8, 15, 22, 35, 50, 65, 80, 95, 110, 130, 150, 175, 200, 220, 240, 260, 280, 300, 320][$idx % 20];

        $o             = new stdClass();
        $o->id         = "demo-{$idx}";
        $o->data       = ['tipo' => $tipo, 'titulo' => $titulo, 'mensagem' => $mensagem, 'numero' => $num, 'url' => '#'];
        $o->read_at    = ($idx % 3 === 2) ? Carbon::now()->subMinutes($minsAgo + 5) : null;
        $o->created_at = Carbon::now()->subMinutes($minsAgo);

        return $o;
    }
}
