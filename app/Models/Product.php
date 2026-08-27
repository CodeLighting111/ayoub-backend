<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'brand_id',
        'sub_product_category_id',
        'name',
        'description',
        'image_url',
        'price',
        'discount_price',
        'pieces',
        'stock',
        'status',
        'unit_label',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'pieces' => 'integer',
            'stock' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if ($product->stock <= 0) {
                $product->status = 'inactive';
            }
        });
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubProductCategory::class, 'sub_product_category_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
