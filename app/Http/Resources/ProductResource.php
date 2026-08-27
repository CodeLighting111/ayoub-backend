<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'pieces' => $this->pieces,
            'stock' => $this->stock,
            'status' => $this->status,
            'unit_label' => $this->unit_label,
            'is_available' => $this->stock > 0 && $this->status === 'active',
            'brand_id' => $this->brand_id,
            'sub_product_category_id' => $this->sub_product_category_id,
            'brand' => $this->whenLoaded('brand', fn () => [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
                'image_url' => $this->brand->image_url,
            ]),
            'sub_category' => $this->whenLoaded('subCategory', fn () => [
                'id' => $this->subCategory->id,
                'title' => $this->subCategory->title,
                'main_product_category_id' => $this->subCategory->main_product_category_id,
            ]),
            'main_category' => $this->whenLoaded('subCategory', function () {
                if (! $this->subCategory?->relationLoaded('mainCategory') || ! $this->subCategory->mainCategory) {
                    return null;
                }

                return [
                    'id' => $this->subCategory->mainCategory->id,
                    'title' => $this->subCategory->mainCategory->title,
                    'image_url' => $this->subCategory->mainCategory->image_url,
                ];
            }),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
