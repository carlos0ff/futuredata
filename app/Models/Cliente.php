<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function ordens(): HasMany
    {
        return $this->hasMany(Ordem::class);
    }

    public function equipamentos(): HasMany
    {
        return $this->hasMany(Equipamento::class);
    }

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
