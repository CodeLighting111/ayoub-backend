<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubProductCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'main_product_category_id' => $this->main_product_category_id,
            'main_category' => $this->whenLoaded('mainCategory', fn () => [
                'id' => $this->mainCategory->id,
                'title' => $this->mainCategory->title,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
