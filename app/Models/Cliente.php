<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Representa um cliente da assistência técnica.
 *
 * @property int         $id
 * @property string      $nome
 * @property string|null $email
 * @property string|null $telefone
 * @property string|null $cpf_cnpj
 * @property \Carbon\Carbon|null $data_nascimento
 * @property string|null $endereco
 * @property string|null $numero
 * @property string|null $complemento
 * @property string|null $bairro
 * @property string|null $cidade
 * @property string|null $estado
 * @property string|null $cep
 * @property string|null $observacoes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read string $iniciais       Duas letras iniciais do nome
 * @property-read \Illuminate\Database\Eloquent\Collection<Ordem>      $ordens
 * @property-read \Illuminate\Database\Eloquent\Collection<Equipamento> $equipamentos
 */
class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf_cnpj',
        'data_nascimento',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'observacoes',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    // ── Relacionamentos ──────────────────────────────────────────────────────

    public function ordens(): HasMany
    {
        return $this->hasMany(Ordem::class);
    }

    public function equipamentos(): HasMany
    {
        return $this->hasMany(Equipamento::class);
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    /** Retorna as duas primeiras iniciais do nome (ex.: "João Silva" → "JS"). */
    public function getIniciaisAttribute(): string
    {
        $palavras = explode(' ', trim($this->nome));
        $iniciais = strtoupper(substr($palavras[0], 0, 1));
        if (isset($palavras[1])) {
            $iniciais .= strtoupper(substr($palavras[1], 0, 1));
        }
        return $iniciais;
    }
}
