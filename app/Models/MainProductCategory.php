<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MainProductCategory extends Model
{
    protected $fillable = [
        'title',
        'image_url',
    ];

    public function subCategories(): HasMany
    {
        return $this->hasMany(SubProductCategory::class);
    }
}
