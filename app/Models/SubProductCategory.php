<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubProductCategory extends Model
{
    protected $fillable = [
        'main_product_category_id',
        'title',
    ];

    public function mainCategory(): BelongsTo
    {
        return $this->belongsTo(MainProductCategory::class, 'main_product_category_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
