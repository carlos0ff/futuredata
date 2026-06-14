<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Representa um equipamento pertencente a um cliente.
 *
 * @property int         $id
 * @property int         $cliente_id
 * @property string      $tipo              Ex.: "Notebook", "Celular", "Impressora"
 * @property string|null $marca
 * @property string|null $modelo
 * @property string|null $numero_serie
 * @property string|null $patrimonio
 * @property string|null $acessorios
 * @property string|null $condicao_entrada
 * @property string|null $forma_entrada     balcao|coleta|motoboy|correios|outro
 * @property string|null $estado_fisico
 * @property bool        $em_garantia
 * @property string|null $observacoes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read string  $nome_completo    "{marca} {modelo}"
 * @property-read Cliente $cliente
 * @property-read \Illuminate\Database\Eloquent\Collection<Ordem> $ordens
 */
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

    // ── Relacionamentos ──────────────────────────────────────────────────────

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function ordens(): HasMany
    {
        return $this->hasMany(Ordem::class);
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    /** Retorna marca e modelo concatenados (ex.: "Dell Inspiron 15"). */
    public function getNomeCompletoAttribute(): string
    {
        return "{$this->marca} {$this->modelo}";
    }
}
