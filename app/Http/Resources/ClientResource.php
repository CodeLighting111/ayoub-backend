<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'branch_name' => $this->branch_name,
            'responsible_person' => $this->responsible_person,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'status' => $this->status,
            'client_category_id' => $this->client_category_id,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'title' => $this->category->title,
            ]),
            'governorate_id' => $this->governorate_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'governorate' => $this->whenLoaded('governorate', fn () => [
                'id' => $this->governorate->id,
                'name' => $this->governorate->name,
            ]),
            'city' => $this->whenLoaded('city', fn () => [
                'id' => $this->city->id,
                'name' => $this->city->name,
            ]),
            'area' => $this->whenLoaded('area', fn () => [
                'id' => $this->area->id,
                'name' => $this->area->name,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
