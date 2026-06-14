<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Arquivo (foto, PDF, laudo) anexado a uma OS.
 *
 * Arquivos são armazenados em disco privado (`storage/app/ordens/{ordem_id}/`).
 * O arquivo físico é removido automaticamente quando o registro é deletado.
 *
 * @property int         $id
 * @property int         $ordem_id
 * @property int|null    $user_id
 * @property string      $nome_original   Nome original do upload
 * @property string      $caminho         Caminho relativo no disco privado
 * @property string|null $mime_type
 * @property int|null    $tamanho         Tamanho em bytes
 * @property string      $tipo            Chave de TIPOS
 * @property string|null $descricao
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read string $tamanho_formatado  Ex.: "1.2 MB"
 * @property-read Ordem    $ordem
 * @property-read User|null $usuario
 */
class OrdemArquivo extends Model
{
    protected $table = 'ordem_arquivos';

    protected $fillable = [
        'ordem_id',
        'user_id',
        'nome_original',
        'caminho',
        'mime_type',
        'tamanho',
        'tipo',
        'descricao',
    ];

    /** Tipos de arquivo aceitos, com label e ícone para exibição na UI. */
    const TIPOS = [
        'os_assinada'  => ['label' => 'OS Assinada',     'icon' => 'document-check'],
        'foto_entrada' => ['label' => 'Foto de Entrada',  'icon' => 'camera'],
        'foto_saida'   => ['label' => 'Foto de Saída',    'icon' => 'camera'],
        'orcamento'    => ['label' => 'Orçamento',         'icon' => 'currency'],
        'laudo'        => ['label' => 'Laudo Técnico',    'icon' => 'clipboard'],
        'nota_fiscal'  => ['label' => 'Nota Fiscal',       'icon' => 'receipt'],
        'outro'        => ['label' => 'Outro',             'icon' => 'document'],
    ];

    // ── Model events ─────────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::deleting(function (OrdemArquivo $arquivo) {
            Storage::disk('local')->delete($arquivo->caminho);
        });
    }

    // ── Relacionamentos ──────────────────────────────────────────────────────

    public function ordem(): BelongsTo
    {
        return $this->belongsTo(Ordem::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Accessors e helpers ──────────────────────────────────────────────────

    /** Tamanho legível (ex.: "256 KB", "1.4 MB"). */
    public function getTamanhoFormatadoAttribute(): string
    {
        $bytes = $this->tamanho;
        if ($bytes < 1024)    return "{$bytes} B";
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function isImagem(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }
}
