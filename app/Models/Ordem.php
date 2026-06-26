<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Representa uma Ordem de Serviço (OS).
 *
 * Fluxo de status:
 *   entrada → analise → execucao → em_teste → finalizado
 *                     ↘ aguardando_cliente ↗
 *   (qualquer estado pode ir para cancelado)
 *
 * @property int         $id
 * @property string      $numero            Gerado automaticamente: "OS{ANO}{SEQ}" (ex.: OS202500001)
 * @property string      $codigo_publico    Gerado após criação: "OS00001"
 * @property string      $token             Token aleatório de 7 chars para link direto do cliente
 * @property int         $cliente_id
 * @property int|null    $equipamento_id
 * @property int|null    $tecnico_id
 * @property string      $status            Chave de STATUS
 * @property string      $problema_relatado
 * @property string|null $diagnostico
 * @property string|null $solucao
 * @property float       $valor_servico
 * @property float       $valor_pecas
 * @property float       $desconto
 * @property string|null $status_orcamento      pendente|aprovado|recusado
 * @property \Carbon\Carbon|null $orcamento_aprovado_em
 * @property string|null $orcamento_aprovado_via whatsapp|portal|manual
 * @property string|null $observacoes
 * @property \Carbon\Carbon|null $previsao_entrega
 * @property \Carbon\Carbon|null $finalizado_em
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read float  $total         valor_servico - desconto
 * @property-read string $status_label  Label legível do status atual
 * @property-read string $status_color  Cor semântica do status (success, warning, info…)
 *
 * @property-read Cliente|null      $cliente
 * @property-read Equipamento|null  $equipamento
 * @property-read User|null         $tecnico
 * @property-read \Illuminate\Database\Eloquent\Collection<OrdemHistorico> $historico
 * @property-read \Illuminate\Database\Eloquent\Collection<Mensagem>       $mensagens
 * @property-read \Illuminate\Database\Eloquent\Collection<OrdemArquivo>   $arquivos
 */
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
        'orcamento_aprovado_em',
        'orcamento_aprovado_via',
        'observacoes',
        'previsao_entrega',
        'finalizado_em',
    ];

    protected $casts = [
        'valor_servico'    => 'decimal:2',
        'valor_pecas'      => 'decimal:2',
        'desconto'         => 'decimal:2',
        'previsao_entrega'      => 'date',
        'finalizado_em'         => 'datetime',
        'orcamento_aprovado_em' => 'datetime',
    ];

    /**
     * Mapa completo de status com label e cor semântica.
     * Usado em controllers, views e WhatsApp bot para exibição consistente.
     */
    const STATUS = [
        'entrada'            => ['label' => 'Entrada registrada', 'color' => 'default'],
        'analise'            => ['label' => 'Em análise',         'color' => 'warning'],
        'execucao'           => ['label' => 'Em execução',        'color' => 'info'],
        'aguardando_cliente' => ['label' => 'Aguardando cliente', 'color' => 'purple'],
        'em_teste'           => ['label' => 'Em teste',           'color' => 'primary'],
        'finalizado'         => ['label' => 'Finalizado',         'color' => 'success'],
        'cancelado'          => ['label' => 'Cancelado',          'color' => 'danger'],
    ];

    /** Status finais: OS encerrada, não admite mais edição. */
    const STATUS_FINAIS = ['finalizado', 'cancelado'];

    // ── Model events ─────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Ordem $ordem) {
            if (empty($ordem->numero)) {
                $ano  = ($ordem->created_at ?? now())->format('Y');
                $seq  = str_pad(static::max('id') + 1, 5, '0', STR_PAD_LEFT);
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

    // ── Relacionamentos ──────────────────────────────────────────────────────

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

    /** Histórico de mudanças de status, mais recente primeiro. */
    public function historico(): HasMany
    {
        return $this->hasMany(OrdemHistorico::class)->latest();
    }

    /** Mensagens da conversa (cliente ↔ técnico), mais antigas primeiro. */
    public function mensagens(): HasMany
    {
        return $this->hasMany(Mensagem::class)->oldest();
    }

    /** Arquivos e fotos anexados à OS, mais recentes primeiro. */
    public function arquivos(): HasMany
    {
        return $this->hasMany(OrdemArquivo::class)->latest();
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    /** Valor total cobrado ao cliente (mão de obra – desconto). */
    public function getTotalAttribute(): float
    {
        return max(0, (float) $this->valor_servico - (float) $this->desconto);
    }

    /** Label legível do status atual (ex.: "Em execução"). */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status]['label'] ?? $this->status;
    }

    /** Cor semântica do status (ex.: "success", "warning"). */
    public function getStatusColorAttribute(): string
    {
        return self::STATUS[$this->status]['color'] ?? 'default';
    }

    /** OS finalizada ou cancelada não pode mais ser editada pela equipe. */
    public function getBloqueadaParaEdicaoAttribute(): bool
    {
        return in_array($this->status, self::STATUS_FINAIS, true);
    }
}
