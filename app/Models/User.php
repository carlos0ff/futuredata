<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Role helpers ─────────────────────────────────

    public function isGerente(): bool
    {
        return $this->role === 'gerente';
    }

    public function isTecnico(): bool
    {
        return $this->role === 'tecnico';
    }

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'gerente' => 'Gerente',
            'tecnico' => 'Técnico',
            default   => ucfirst($this->role),
        };
    }

    public function getIniciaisAttribute(): string
    {
        $palavras  = explode(' ', trim($this->name));
        $iniciais  = strtoupper(substr($palavras[0], 0, 1));
        $iniciais .= isset($palavras[1]) ? strtoupper(substr($palavras[1], 0, 1)) : '';
        return $iniciais;
    }

    // ── RBAC ────────────────────────────────────────

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role')
                    ->withPivot('assigned_at');
    }

    public function hasPermission(string $slug): bool
    {
        return $this->roles()
            ->whereHas('permissions', fn ($q) => $q->where('slug', $slug))
            ->exists();
    }

    public function hasRoleSlug(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    // ── Relacionamentos ──────────────────────────────

    public function ordens(): HasMany
    {
        return $this->hasMany(Ordem::class, 'tecnico_id');
    }
}
