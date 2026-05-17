<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'group'];

    const GROUPS = [
        'sistema'    => ['label' => 'Sistema',    'color' => 'text-purple-400'],
        'ordens'     => ['label' => 'Ordens',     'color' => 'text-blue-400'],
        'clientes'   => ['label' => 'Clientes',   'color' => 'text-emerald-400'],
        'relatorios' => ['label' => 'Relatórios', 'color' => 'text-amber-400'],
        'portal'     => ['label' => 'Portal',     'color' => 'text-slate-400'],
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
