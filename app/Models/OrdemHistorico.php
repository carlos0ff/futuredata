<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function ordem(): BelongsTo
    {
        return $this->belongsTo(Ordem::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
