<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Schema;

#[Fillable(['name', 'email', 'phone', 'avatar_url', 'password', 'role', 'role_id', 'status'])]
#[Hidden(['password', 'remember_token'])]
class Admin extends Authenticatable
{
    public const PRIMARY_SUPERADMIN_EMAIL = 'ayoub@gmail.com';

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Admin $admin): void {
            if (! static::hasStatusColumn() || ! $admin->isPrimarySuperAdmin()) {
                return;
            }

            $admin->status = 'active';
            $admin->role = 'superadmin';

            if ($admin->role_id === null) {
                $admin->role_id = Role::query()->where('slug', 'superadmin')->value('id');
            }
        });
    }

    public function isPrimarySuperAdmin(): bool
    {
        return strcasecmp($this->email, self::PRIMARY_SUPERADMIN_EMAIL) === 0;
    }

    public function isActive(): bool
    {
        if (! static::hasStatusColumn()) {
            return true;
        }

        return ($this->status ?? 'active') === 'active';
    }

    public static function hasStatusColumn(): bool
    {
        static $hasColumn = null;

        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn('admins', 'status');
        }

        return $hasColumn;
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
