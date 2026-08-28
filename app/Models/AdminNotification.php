<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends Model
{
    public const TYPE_NEW_ORDER = 'new_order';

    public const TYPE_ORDER_CANCELLED = 'order_cancelled';

    public const TYPE_NEW_COMPLAINT = 'new_complaint';

    public const TYPE_LOW_STOCK = 'low_stock';

    public const LOW_STOCK_THRESHOLD = 10;

    protected $fillable = [
        'order_id',
        'complaint_id',
        'product_id',
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

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
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
            self::TYPE_NEW_COMPLAINT => 'شكوى جديدة',
            self::TYPE_LOW_STOCK => 'مخزون منخفض',
            default => $this->type,
        };
    }

    public function typeBadgeClasses(): string
    {
        return match ($this->type) {
            self::TYPE_NEW_ORDER => 'bg-tertiary-container/20 text-tertiary',
            self::TYPE_ORDER_CANCELLED => 'bg-error/10 text-error',
            self::TYPE_NEW_COMPLAINT => 'bg-deal/15 text-deal',
            self::TYPE_LOW_STOCK => 'bg-primary-container/10 text-primary-container',
            default => 'bg-surface-container-high text-on-surface-variant',
        };
    }

    public function referenceLabel(): string
    {
        return match ($this->type) {
            self::TYPE_NEW_ORDER, self::TYPE_ORDER_CANCELLED => '#'.($this->order?->order_number ?? '—'),
            self::TYPE_NEW_COMPLAINT => $this->complaint?->subject ?? '—',
            self::TYPE_LOW_STOCK => $this->product?->name ?? '—',
            default => '—',
        };
    }

    public function referenceHint(): string
    {
        return match ($this->type) {
            self::TYPE_NEW_ORDER, self::TYPE_ORDER_CANCELLED => 'رقم الطلب',
            self::TYPE_NEW_COMPLAINT => 'موضوع الشكوى',
            self::TYPE_LOW_STOCK => 'المنتج',
            default => 'المرجع',
        };
    }

    public function relatedName(): ?string
    {
        return match ($this->type) {
            self::TYPE_NEW_ORDER, self::TYPE_ORDER_CANCELLED => $this->order?->client_name,
            self::TYPE_NEW_COMPLAINT => $this->complaint?->client_name,
            self::TYPE_LOW_STOCK => 'المخزون: '.($this->product?->stock ?? '—'),
            default => null,
        };
    }

    public function detailLabel(): ?string
    {
        return match ($this->type) {
            self::TYPE_NEW_ORDER, self::TYPE_ORDER_CANCELLED => $this->order?->statusLabel(),
            self::TYPE_NEW_COMPLAINT => $this->complaint?->statusLabel(),
            self::TYPE_LOW_STOCK => ($this->product?->stock ?? 0).' قطعة',
            default => null,
        };
    }

    public function detailBadgeClasses(): string
    {
        return match ($this->type) {
            self::TYPE_NEW_ORDER, self::TYPE_ORDER_CANCELLED => $this->order?->statusBadgeClasses() ?? 'bg-surface-container-high text-on-surface-variant',
            self::TYPE_NEW_COMPLAINT => $this->complaint?->statusBadgeClasses() ?? 'bg-surface-container-high text-on-surface-variant',
            self::TYPE_LOW_STOCK => 'bg-error/10 text-error',
            default => 'bg-surface-container-high text-on-surface-variant',
        };
    }

    /** @return array{0: string, 1: int}|null */
    public function redirectTarget(): ?array
    {
        return match ($this->type) {
            self::TYPE_NEW_ORDER, self::TYPE_ORDER_CANCELLED => $this->order_id ? ['admin.orders.show', $this->order_id] : null,
            self::TYPE_NEW_COMPLAINT => $this->complaint_id ? ['admin.complaints.show', $this->complaint_id] : null,
            self::TYPE_LOW_STOCK => $this->product_id ? ['admin.products.edit', $this->product_id] : null,
            default => null,
        };
    }
}
