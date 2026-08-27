<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class GeneralSetting extends Model
{
    protected $fillable = [
        'app_title',
        'app_description',
        'logo_url',
        'delivery_fee',
        'min_order_amount',
    ];

    protected function casts(): array
    {
        return [
            'delivery_fee' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'app_title' => config('app.name', 'سوقنا'),
            'app_description' => 'منصة متكاملة لتجارة الجملة وتوريد المواد الغذائية بجودة عالية وأسعار تنافسية.',
            'delivery_fee' => 30,
            'min_order_amount' => 0,
        ]);
    }

    public static function platformName(): string
    {
        $settings = static::resolved();

        return filled($settings?->app_title)
            ? (string) $settings->app_title
            : (string) config('app.name', 'سوقنا');
    }

    public static function platformLogoUrl(): string
    {
        $settings = static::resolved();

        if (filled($settings?->logo_url)) {
            return asset(ltrim($settings->logo_url, '/'));
        }

        return asset('images/brand/logo.png');
    }

    private static function resolved(): ?self
    {
        try {
            if (! Schema::hasTable('general_settings')) {
                return null;
            }

            return static::query()->first();
        } catch (\Throwable) {
            return null;
        }
    }
}
