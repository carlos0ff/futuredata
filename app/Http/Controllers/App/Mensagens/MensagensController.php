<?php

namespace App\Http\Controllers\App\Mensagens;

use App\Http\Controllers\Controller;
use App\Models\Mensagem;
use App\Models\Ordem;
use App\Services\WhatsappService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Central de mensagens — chat bidirecional entre plataforma e clientes via WhatsApp.
 *
 * Arquitetura:
 * - A view (`app.mensagens.index`) é full-height (sem padding do layout padrão).
 *   Para isso, declara `@section('fullpage')` que é detectado em `layouts/app.blade.php`.
 * - O frontend (Alpine.js) carrega a lista de OS como JSON via `Js::from()` injetado
 *   na view, seleciona um contato e faz polling a cada 5 segundos via `mensagens()`.
 * - Ao enviar mensagem (`store()`), a plataforma tenta entregar via WhatsApp
 *   (falha silenciosa se Evolution API não estiver configurada).
 *
 * Rotas:
 * - GET  /app/mensagens                → index()
 * - POST /app/mensagens                → store()
 * - GET  /app/mensagens/{ordem}/chat   → mensagens()
 */
class MensagensController extends Controller
{
    /** Cores de avatar para diferenciar contatos visualmente na lista. */
    private static array $CORES = [
        'bg-blue-500', 'bg-pink-500', 'bg-emerald-500', 'bg-amber-500',
        'bg-violet-500', 'bg-rose-500', 'bg-cyan-500', 'bg-orange-500',
    ];

    /**
     * Exibe a tela de chat com a lista de OS ativas (últimas 30).
     * Os dados de contatos são passados como JSON para o Alpine.js na view.
     */
    public function index(): View
    {
        $ordens = Ordem::with(['cliente', 'equipamento', 'tecnico'])
            ->whereNotIn('status', ['cancelado'])
            ->latest()
            ->take(30)
            ->get();

        $contacts = $ordens->map(function (Ordem $ordem) {
            $ultimaMensagem = $ordem->mensagens()->latest()->first();
            $nome    = $ordem->cliente->nome ?? 'Cliente';
            $partes  = explode(' ', trim($nome));
            $initials = strtoupper(
                substr($partes[0] ?? '', 0, 1) .
                substr($partes[1] ?? $partes[0] ?? '', 0, 1)
            );

            return [
                'ordemId'     => $ordem->id,
                'id'          => $ordem->id,
                'name'        => $nome,
                'initials'    => $initials,
                'color'       => self::$CORES[$ordem->id % count(self::$CORES)],
                'os'          => $ordem->numero,
                'device'      => $ordem->equipamento
                    ? trim("{$ordem->equipamento->marca} {$ordem->equipamento->modelo}")
                    : 'Dispositivo',
                'phone'       => $ordem->cliente->telefone ?? null,
                'email'       => $ordem->cliente->email ?? null,
                'status'      => 'offline',
                'statusLabel' => $ordem->status_label,
                'statusBadge' => $this->statusBadge($ordem->status),
                'valor'       => $ordem->total > 0
                    ? 'R$ ' . number_format($ordem->total, 2, ',', '.')
                    : null,
                'dataEntrada' => $ordem->created_at->format('d/m/Y'),
                'tecnico'     => $ordem->tecnico?->name ?? null,
                'problema'    => $ordem->problema_relatado ?? null,
                'lastMsg'     => $ultimaMensagem?->conteudo ?? 'Sem mensagens ainda.',
                'lastTime'    => $ultimaMensagem ? $ultimaMensagem->created_at->diffForHumans(null, true) : '',
                'unread'      => $ordem->mensagens()->where('tipo', 'cliente')->whereNull('lida_em')->count(),
                'dateLabel'   => 'Hoje',
                'messages'    => [],
            ];
        })->values()->toArray();

        return view('app.mensagens.index', compact('contacts'));
    }

    /**
     * Persiste uma mensagem do técnico e tenta entregá-la via WhatsApp.
     *
     * Retorna JSON com os dados da mensagem criada e se o WhatsApp foi acionado.
     */
    public function store(Request $request, WhatsappService $whatsapp): JsonResponse
    {
        $validated = $request->validate([
            'ordem_id' => 'required|exists:ordens,id',
            'conteudo' => 'required|string|max:2000',
        ]);

        $mensagem = Mensagem::create([
            'ordem_id' => $validated['ordem_id'],
            'user_id'  => auth()->id(),
            'tipo'     => 'tecnico',
            'conteudo' => $validated['conteudo'],
        ]);

        $ordem = Ordem::with('cliente')->find($validated['ordem_id']);
        $sent  = false;
        if ($ordem?->cliente?->telefone) {
            $sent = $whatsapp->send($ordem->cliente->telefone, $validated['conteudo']);
        }

        return response()->json([
            'from'     => 'me',
            'text'     => $mensagem->conteudo,
            'time'     => $mensagem->created_at->format('H:i'),
            'whatsapp' => $sent,
        ]);
    }

    /**
     * Retorna todas as mensagens de uma OS para o polling do frontend.
     * Marca como lidas todas as mensagens não lidas do cliente.
     */
    public function mensagens(Ordem $ordem): JsonResponse
    {
        $ordem->mensagens()
            ->where('tipo', 'cliente')
            ->whereNull('lida_em')
            ->update(['lida_em' => now()]);

        $data = $ordem->mensagens()
            ->orderBy('created_at')
            ->get()
            ->map(fn ($m) => [
                'from' => $m->tipo === 'tecnico' ? 'me' : 'client',
                'text' => $m->conteudo,
                'time' => $m->created_at->format('H:i'),
            ]);

        return response()->json($data);
    }

    /**
     * Retorna as classes Tailwind do badge de status para a lista de contatos.
     */
    private function statusBadge(string $status): string
    {
        return match ($status) {
            'analise'            => 'bg-amber-100 text-amber-700',
            'execucao'           => 'bg-blue-100 text-blue-700',
            'aguardando_cliente' => 'bg-purple-100 text-purple-700',
            'em_teste'           => 'bg-sky-100 text-sky-700',
            'finalizado'         => 'bg-emerald-100 text-emerald-700',
            default              => 'bg-slate-100 text-slate-500',
        };
    }
}
