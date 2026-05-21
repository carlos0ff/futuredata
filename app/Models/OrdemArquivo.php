<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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

    const TIPOS = [
        'os_assinada'  => ['label' => 'OS Assinada',    'icon' => 'document-check'],
        'foto_entrada' => ['label' => 'Foto de Entrada', 'icon' => 'camera'],
        'foto_saida'   => ['label' => 'Foto de Saída',   'icon' => 'camera'],
        'orcamento'    => ['label' => 'Orçamento',        'icon' => 'currency'],
        'laudo'        => ['label' => 'Laudo Técnico',   'icon' => 'clipboard'],
        'nota_fiscal'  => ['label' => 'Nota Fiscal',      'icon' => 'receipt'],
        'outro'        => ['label' => 'Outro',            'icon' => 'document'],
    ];

    public function ordem(): BelongsTo
    {
        return $this->belongsTo(Ordem::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTamanhoFormatadoAttribute(): string
    {
        $bytes = $this->tamanho;
        if ($bytes < 1024)       return "{$bytes} B";
        if ($bytes < 1048576)    return round($bytes / 1024, 1) . ' KB';
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

    protected static function booted(): void
    {
        static::deleting(function (OrdemArquivo $arquivo) {
            Storage::disk('local')->delete($arquivo->caminho);
        });
    }
}
