<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Registro imutável de cada mudança de status de uma OS.
 *
 * Criado automaticamente em OrdemServicoController sempre que
 * o campo `status` da OS é alterado.
 *
 * @property int         $id
 * @property int         $ordem_id
 * @property int|null    $user_id       Null quando alterado pelo sistema
 * @property string|null $status_anterior
 * @property string      $status_novo
 * @property string|null $observacao    Nota opcional do técnico sobre a mudança
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read Ordem    $ordem
 * @property-read User|null $usuario
 */
class OrdemHistorico extends Model
{
    protected $table = 'ordem_historicos';

    protected $fillable = [
        'ordem_id',
        'user_id',
        'status_anterior',
        'status_novo',
        'observacao',
    ];

    // ── Relacionamentos ──────────────────────────────────────────────────────

    public function ordem(): BelongsTo
    {
        return $this->belongsTo(Ordem::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
