<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['name', 'email', 'phone', 'avatar_url', 'password', 'role', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class Admin extends Authenticatable
{
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function roleModel(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function roleLabel(): string
    {
        return $this->roleModel?->name ?? match ($this->role) {
            'superadmin' => 'مدير النظام',
            'admin' => 'مشرف',
            default => $this->role ?? '—',
        };
    }

    public function hasPermission(string $slug): bool
    {
        if ($this->role === 'superadmin' || $this->roleModel?->slug === 'superadmin') {
            return true;
        }

        return $this->roleModel?->hasPermission($slug) ?? false;
    }
}
