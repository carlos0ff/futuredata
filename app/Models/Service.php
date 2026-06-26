<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property int    $user_id
 * @property string $name
 * @property string $category
 * @property float  $base_price
 * @property string $status  ativo|feito|notificado_whatsapp
 */
class Service extends Model
{
    use HasFactory;

    const STATUS = [
        'ativo'                => ['label' => 'Ativo',                   'color' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
        'feito'                => ['label' => 'Feito',                   'color' => 'bg-blue-50 text-blue-700 ring-blue-200'],
        'notificado_whatsapp'  => ['label' => 'Notificado por WhatsApp', 'color' => 'bg-green-50 text-green-700 ring-green-200'],
    ];

    protected $fillable = ['user_id', 'name', 'category', 'base_price', 'status'];

    protected $casts = ['base_price' => 'float'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
