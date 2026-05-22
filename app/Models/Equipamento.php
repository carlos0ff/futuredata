<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'tipo',
        'marca',
        'modelo',
        'numero_serie',
        'patrimonio',
        'acessorios',
        'condicao_entrada',
        'forma_entrada',
        'estado_fisico',
        'em_garantia',
        'observacoes',
    ];

    protected $casts = [
        'em_garantia' => 'boolean',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function ordens(): HasMany
    {
        return $this->hasMany(Ordem::class);
    }

    public function getNomeCompletoAttribute(): string
    {
        return "{$this->marca} {$this->modelo}";
    }
}
