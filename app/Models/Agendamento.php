<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agendamento extends Model
{
    protected $fillable = [
        'cliente_id',
        'atendido_por',
        'tipo_equipamento',
        'descricao_equipamento',
        'descricao_problema',
        'data_preferida',
        'periodo',
        'status',
        'observacoes_loja',
    ];

    protected $casts = [
        'data_preferida' => 'date',
    ];

    const STATUS = [
        'pendente'   => ['label' => 'Pendente',    'color' => 'warning'],
        'confirmado' => ['label' => 'Confirmado',  'color' => 'info'],
        'cancelado'  => ['label' => 'Cancelado',   'color' => 'danger'],
        'concluido'  => ['label' => 'Concluído',   'color' => 'success'],
    ];

    const PERIODOS = [
        'manha'    => 'Manhã (08h–12h)',
        'tarde'    => 'Tarde (13h–18h)',
        'qualquer' => 'Qualquer horário',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atendido_por');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status]['label'] ?? $this->status;
    }

    public function getPeriodoLabelAttribute(): string
    {
        return self::PERIODOS[$this->periodo] ?? $this->periodo;
    }
}
