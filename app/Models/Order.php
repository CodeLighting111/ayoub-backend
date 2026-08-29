<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUSES = [
        'pending',
        'accepted',
        'processing',
        'shipped',
        'delivered',
        'cancelled',
    ];

    protected $fillable = [
        'order_number',
        'client_id',
        'status',
        'payment_method',
        'payment_status',
        'subtotal',
        'delivery_fee',
        'total',
        'notes',
        'client_name',
        'client_phone',
        'branch_name',
        'delivery_address',
        'governorate_name',
        'city_name',
        'area_name',
        'preferred_delivery_at',
        'expected_delivery_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'total' => 'decimal:2',
            'expected_delivery_at' => 'datetime',
            'preferred_delivery_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'قيد الانتظار',
            'accepted' => 'مقبول',
            'processing' => 'قيد التجهيز',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغى',
            default => $this->status,
        };
    }

    public function statusBadgeClasses(): string
    {
        return self::statusBadgeClassesFor($this->status);
    }

    public static function statusBadgeClassesFor(?string $status): string
    {
        return match ($status) {
            'pending' => 'bg-deal/15 text-deal',
            'accepted' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-primary-container/15 text-primary-container',
            'shipped' => 'bg-indigo-100 text-indigo-800',
            'delivered' => 'bg-secondary-container/70 text-on-secondary-container',
            'cancelled' => 'bg-error/10 text-error',
            default => 'bg-surface-container-high text-on-surface-variant',
        };
    }

    public static function statusAccentClass(?string $status): string
    {
        return match ($status) {
            'pending' => 'text-deal',
            'accepted' => 'text-blue-700',
            'processing' => 'text-primary-container',
            'shipped' => 'text-indigo-700',
            'delivered' => 'text-on-secondary-container',
            'cancelled' => 'text-error',
            default => 'text-on-surface',
        };
    }

    public function paymentMethodLabel(): string
    {
        return match ($this->payment_method) {
            'cash' => 'كاش',
            'wallet' => 'محفظة إلكترونية',
            'bank_transfer' => 'تحويل بنكي',
            default => $this->payment_method,
        };
    }

    public function preferredDeliveryLabel(): ?string
    {
        if ($this->preferred_delivery_at === null) {
            return null;
        }

        $days = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];

        return $days[$this->preferred_delivery_at->dayOfWeek].' - '.$this->preferred_delivery_at->format('Y-m-d');
    }
}
