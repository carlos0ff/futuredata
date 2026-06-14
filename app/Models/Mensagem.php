<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Representa uma mensagem na conversa de uma OS.
 *
 * Uma mensagem pode ter dois tipos (campo `tipo`):
 * - "cliente"  — enviada pelo cliente via portal ou recebida via WhatsApp
 * - "tecnico"  — enviada pelo técnico/sistema via plataforma ou bot do WhatsApp
 *
 * Quando `lida_em` é null, a mensagem do cliente ainda não foi visualizada
 * pela equipe interna (badge de não lida no chat da plataforma).
 *
 * @property int         $id
 * @property int         $ordem_id
 * @property int|null    $user_id      Null quando enviado pelo cliente ou pelo bot
 * @property string      $tipo         "cliente" | "tecnico"
 * @property string      $conteudo
 * @property \Carbon\Carbon|null $lida_em
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read bool   $lida    Atalho: se lida_em não é null
 * @property-read Ordem  $ordem
 * @property-read User|null $autor
 */
class Mensagem extends Model
{
    protected $table = 'mensagens';

    protected $fillable = [
        'ordem_id',
        'user_id',
        'tipo',
        'conteudo',
        'lida_em',
    ];

    protected $casts = [
        'lida_em' => 'datetime',
    ];

    // ── Relacionamentos ──────────────────────────────────────────────────────

    public function ordem(): BelongsTo
    {
        return $this->belongsTo(Ordem::class);
    }

    /** Usuário interno que enviou a mensagem (null se foi o cliente ou o bot). */
    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    /** Retorna true se a mensagem já foi visualizada pela equipe. */
    public function getLidaAttribute(): bool
    {
        return $this->lida_em !== null;
    }
}
