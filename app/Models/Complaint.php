<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    public const STATUSES = [
        'pending',
        'resolved',
        'rejected',
    ];

    protected $fillable = [
        'client_id',
        'order_id',
        'status',
        'subject',
        'message',
        'client_name',
        'client_phone',
        'admin_response',
        'resolved_at',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'قيد المراجعة',
            'resolved' => 'تم الحل',
            'rejected' => 'تم الرفض',
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
            'resolved' => 'bg-secondary-container/70 text-on-secondary-container',
            'rejected' => 'bg-error/10 text-error',
            default => 'bg-surface-container-high text-on-surface-variant',
        };
    }

    public static function statusAccentClass(?string $status): string
    {
        return match ($status) {
            'pending' => 'text-deal',
            'resolved' => 'text-on-secondary-container',
            'rejected' => 'text-error',
            default => 'text-on-surface',
        };
    }
}
