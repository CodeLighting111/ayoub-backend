<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'client_id' => $this->client_id,
            'status' => $this->status,
            'status_label' => $this->statusLabel(),
            'payment_method' => $this->payment_method,
            'payment_method_label' => $this->paymentMethodLabel(),
            'payment_status' => $this->payment_status,
            'subtotal' => $this->subtotal,
            'delivery_fee' => $this->delivery_fee,
            'total' => $this->total,
            'notes' => $this->notes,
            'client_name' => $this->client_name,
            'client_phone' => $this->client_phone,
            'branch_name' => $this->branch_name,
            'delivery_address' => $this->delivery_address,
            'governorate_name' => $this->governorate_name,
            'city_name' => $this->city_name,
            'area_name' => $this->area_name,
            'preferred_delivery_at' => $this->preferred_delivery_at?->toIso8601String(),
            'preferred_delivery_label' => $this->preferredDeliveryLabel(),
            'items_count' => $this->whenCounted('items', fn () => $this->items_count, fn () => $this->items->count()),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'expected_delivery_at' => $this->expected_delivery_at?->toIso8601String(),
            'delivered_at' => $this->delivered_at?->toIso8601String(),
            'cancelled_at' => $this->cancelled_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
