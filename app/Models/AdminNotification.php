<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends Model
{
    public const TYPE_NEW_ORDER = 'new_order';

    public const TYPE_ORDER_CANCELLED = 'order_cancelled';

    protected $fillable = [
        'order_id',
        'type',
        'title',
        'message',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            self::TYPE_NEW_ORDER => 'طلب جديد',
            self::TYPE_ORDER_CANCELLED => 'طلب ملغى',
            default => $this->type,
        };
    }

    public function typeBadgeClasses(): string
    {
        return match ($this->type) {
            self::TYPE_NEW_ORDER => 'bg-tertiary-container/20 text-tertiary',
            self::TYPE_ORDER_CANCELLED => 'bg-error/10 text-error',
            default => 'bg-surface-container-high text-on-surface-variant',
        };
    }
}
