<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'color', 'level'];

    // ── Cores por slug ──────────────────────────────
    const COLORS = [
        'dev'         => ['bg' => 'bg-purple-500/10', 'text' => 'text-purple-400', 'border' => 'border-purple-500/20', 'dot' => 'bg-purple-500', 'badge' => 'bg-purple-500/15 text-purple-300'],
        'admin'       => ['bg' => 'bg-blue-500/10',   'text' => 'text-blue-400',   'border' => 'border-blue-500/20',   'dot' => 'bg-blue-500',   'badge' => 'bg-blue-500/15 text-blue-300'],
        'tecnico'     => ['bg' => 'bg-emerald-500/10','text' => 'text-emerald-400','border' => 'border-emerald-500/20','dot' => 'bg-emerald-500','badge' => 'bg-emerald-500/15 text-emerald-300'],
        'atendente'   => ['bg' => 'bg-amber-500/10',  'text' => 'text-amber-400',  'border' => 'border-amber-500/20',  'dot' => 'bg-amber-500',  'badge' => 'bg-amber-500/15 text-amber-300'],
        'cliente'     => ['bg' => 'bg-slate-500/10',  'text' => 'text-slate-400',  'border' => 'border-slate-500/20',  'dot' => 'bg-slate-500',  'badge' => 'bg-slate-500/15 text-slate-300'],
    ];

    public function getColorSetAttribute(): array
    {
        return self::COLORS[$this->slug] ?? self::COLORS['cliente'];
    }

    // ── Relacionamentos ──────────────────────────────

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role')
                    ->withPivot('assigned_at');
    }
}
