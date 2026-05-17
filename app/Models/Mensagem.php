<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function ordem(): BelongsTo
    {
        return $this->belongsTo(Ordem::class);
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getLidaAttribute(): bool
    {
        return $this->lida_em !== null;
    }
}
