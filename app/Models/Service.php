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
 */
class Service extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'category', 'base_price'];

    protected $casts = ['base_price' => 'float'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
