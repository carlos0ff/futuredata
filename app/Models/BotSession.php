<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Estado de conversa do chatbot (WhatsApp ou portal).
 *
 * Persiste o estado entre mensagens para suportar fluxos multi-turno.
 * Uma sessão por número de telefone (WhatsApp) ou por cliente (portal).
 *
 * Estados possíveis:
 *   idle               — sem conversa ativa
 *   menu               — menu principal exibido
 *   waiting_os         — múltiplas OS, esperando escolha
 *   waiting_cpf        — cliente desconhecido, pedindo CPF/código OS
 *   human_requested    — aguardando equipe humana
 *
 * @property int              $id
 * @property string|null      $phone
 * @property int|null         $cliente_id
 * @property int|null         $ordem_id
 * @property string           $channel       whatsapp | portal
 * @property string           $state
 * @property array|null       $context
 * @property \Carbon\Carbon|null $last_activity
 * @property \Carbon\Carbon   $created_at
 * @property \Carbon\Carbon   $updated_at
 *
 * @property-read Cliente|null $cliente
 * @property-read Ordem|null   $ordem
 */
class BotSession extends Model
{
    protected $table = 'bot_sessions';

    protected $fillable = [
        'phone',
        'cliente_id',
        'ordem_id',
        'channel',
        'state',
        'context',
        'last_activity',
    ];

    protected $casts = [
        'context'       => 'array',
        'last_activity' => 'datetime',
    ];

    // ── Relacionamentos ──────────────────────────────────────────────────────

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function ordem(): BelongsTo
    {
        return $this->belongsTo(Ordem::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /** Avança para um novo estado, limpando ou atualizando o contexto. */
    public function transition(string $newState, array $context = []): void
    {
        $this->update([
            'state'         => $newState,
            'context'       => array_merge($this->context ?? [], $context),
            'last_activity' => now(),
        ]);
    }

    /** Reseta a sessão para o estado inicial. */
    public function reset(): void
    {
        $this->update([
            'state'         => 'idle',
            'context'       => null,
            'ordem_id'      => null,
            'cliente_id'    => null,
            'last_activity' => now(),
        ]);
    }

    /** Retorna ou cria a sessão para um número de telefone (WhatsApp). */
    public static function forPhone(string $phone): static
    {
        return static::firstOrCreate(
            ['phone' => $phone, 'channel' => 'whatsapp'],
            ['state' => 'idle', 'last_activity' => now()],
        );
    }

    /** Retorna ou cria a sessão para um cliente no portal. */
    public static function forPortal(int $clienteId): static
    {
        return static::firstOrCreate(
            ['cliente_id' => $clienteId, 'channel' => 'portal'],
            ['state' => 'idle', 'last_activity' => now()],
        );
    }

    /** True se a sessão está inativa há mais de 30 minutos. */
    public function isExpired(): bool
    {
        return $this->last_activity && $this->last_activity->lt(now()->subMinutes(30));
    }
}
