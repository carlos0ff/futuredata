<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ordem extends Model
{
    use HasFactory;

    protected $table = 'ordens';

    protected $fillable = [
        'numero',
        'codigo_publico',
        'token',
        'cliente_id',
        'equipamento_id',
        'tecnico_id',
        'status',
        'problema_relatado',
        'diagnostico',
        'solucao',
        'valor_servico',
        'valor_pecas',
        'desconto',
        'status_orcamento',
        'observacoes',
        'previsao_entrega',
        'finalizado_em',
    ];

    protected $casts = [
        'valor_servico'    => 'decimal:2',
        'valor_pecas'      => 'decimal:2',
        'desconto'         => 'decimal:2',
        'previsao_entrega' => 'date',
        'finalizado_em'    => 'datetime',
    ];

    const STATUS = [
        'entrada'           => ['label' => 'Entrada registrada', 'color' => 'default'],
        'analise'           => ['label' => 'Em análise',         'color' => 'warning'],
        'execucao'          => ['label' => 'Em execução',        'color' => 'info'],
        'aguardando_cliente'=> ['label' => 'Aguardando cliente', 'color' => 'purple'],
        'em_teste'          => ['label' => 'Em teste',           'color' => 'primary'],
        'finalizado'        => ['label' => 'Finalizado',         'color' => 'success'],
        'cancelado'         => ['label' => 'Cancelado',          'color' => 'danger'],
    ];

    protected static function booted(): void
    {
        static::creating(function (Ordem $ordem) {
            if (empty($ordem->numero)) {
                $ano = ($ordem->created_at ?? now())->format('Y');
                $seq = str_pad(static::max('id') + 1, 5, '0', STR_PAD_LEFT);
                $ordem->numero = "OS{$ano}{$seq}";
            }

            if (empty($ordem->token)) {
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                do {
                    $token = '';
                    for ($i = 0; $i < 7; $i++) {
                        $token .= $chars[random_int(0, 35)];
                    }
                } while (static::where('token', $token)->exists());
                $ordem->token = $token;
            }
        });

        static::created(function (Ordem $ordem) {
            if (empty($ordem->codigo_publico)) {
                $ordem->updateQuietly([
                    'codigo_publico' => 'OS' . str_pad($ordem->id, 5, '0', STR_PAD_LEFT),
                ]);
            }
        });
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function equipamento(): BelongsTo
    {
        return $this->belongsTo(Equipamento::class);
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function historico(): HasMany
    {
        return $this->hasMany(OrdemHistorico::class)->latest();
    }

    public function mensagens(): HasMany
    {
        return $this->hasMany(Mensagem::class)->oldest();
    }

    public function arquivos(): HasMany
    {
        return $this->hasMany(OrdemArquivo::class)->latest();
    }

    public function getTotalAttribute(): float
    {
        return max(0, (float) $this->valor_servico - (float) $this->desconto);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS[$this->status]['color'] ?? 'default';
    }
}
