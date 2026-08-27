<?php

namespace App\Services;

use App\Models\Client;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public const DELIVERY_FEE = 30.0;

    public function __construct(private readonly AdminNotificationService $notificationService) {}

    public function create(Client $client, array $items, string $paymentMethod, ?string $notes = null, ?string $preferredDeliveryAt = null): Order
    {
        return DB::transaction(function () use ($client, $items, $paymentMethod, $notes, $preferredDeliveryAt) {
            $client->load(['governorate', 'city', 'area']);

            $lineItems = [];
            $subtotal = 0.0;

            foreach ($items as $item) {
                $product = Product::query()->lockForUpdate()->findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];

                if ($quantity < 1) {
                    throw ValidationException::withMessages([
                        'items' => 'كمية المنتج يجب أن تكون 1 على الأقل.',
                    ]);
                }

                if ($product->status !== 'active' || $product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => 'المنتج «'.$product->name.'» غير متوفر بالكمية المطلوبة.',
                    ]);
                }

                $unitPrice = (float) ($product->discount_price ?? $product->price);
                $lineTotal = round($unitPrice * $quantity, 2);
                $subtotal += $lineTotal;

                $lineItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $deliveryFee = (float) GeneralSetting::current()->delivery_fee;
            $total = round($subtotal + $deliveryFee, 2);

            $order = Order::query()->create([
                'order_number' => $this->generateOrderNumber(),
                'client_id' => $client->id,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'payment_status' => 'unpaid',
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'notes' => $notes,
                'client_name' => $client->name,
                'client_phone' => $client->phone,
                'branch_name' => $client->branch_name,
                'delivery_address' => $client->address,
                'governorate_name' => $client->governorate?->name,
                'city_name' => $client->city?->name,
                'area_name' => $client->area?->name,
                'preferred_delivery_at' => $preferredDeliveryAt,
                'expected_delivery_at' => now()->addDay(),
            ]);

            foreach ($lineItems as $lineItem) {
                /** @var Product $product */
                $product = $lineItem['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_label' => $product->unit_label,
                    'image_url' => $product->image_url,
                    'unit_price' => $lineItem['unit_price'],
                    'quantity' => $lineItem['quantity'],
                    'line_total' => $lineItem['line_total'],
                ]);

                $product->decrement('stock', $lineItem['quantity']);
            }

            $this->notificationService->notifyNewOrder($order);

            return $order->load('items.product');
        });
    }

    public function updateStatus(Order $order, string $status, ?string $expectedDeliveryAt = null): Order
    {
        if (! in_array($status, Order::STATUSES, true)) {
            throw ValidationException::withMessages([
                'status' => 'حالة الطلب غير صالحة.',
            ]);
        }

        if ($order->status === 'delivered') {
            throw ValidationException::withMessages([
                'status' => 'لا يمكن تعديل طلب تم توصيله.',
            ]);
        }

        if ($order->status === 'cancelled') {
            throw ValidationException::withMessages([
                'status' => 'لا يمكن تعديل طلب ملغى.',
            ]);
        }

        return DB::transaction(function () use ($order, $status, $expectedDeliveryAt) {
            if ($status === 'cancelled' && $order->status !== 'cancelled') {
                $this->restoreStock($order);
                $order->cancelled_at = now();
            }

            if ($status === 'delivered') {
                $order->delivered_at = now();
                $order->payment_status = 'paid';
            }

            if ($expectedDeliveryAt !== null) {
                $order->expected_delivery_at = $expectedDeliveryAt;
            }

            $order->status = $status;
            $order->save();

            return $order->fresh(['items.product', 'client']);
        });
    }

    public function cancelByClient(Order $order): Order
    {
        if (! in_array($order->status, ['pending', 'accepted'], true)) {
            throw ValidationException::withMessages([
                'status' => 'لا يمكن إلغاء هذا الطلب في حالته الحالية.',
            ]);
        }

        $order = $this->updateStatus($order, 'cancelled');
        $this->notificationService->notifyOrderCancelledByClient($order);

        return $order;
    }

    private function restoreStock(Order $order): void
    {
        $order->load('items');

        foreach ($order->items as $item) {
            Product::query()
                ->whereKey($item->product_id)
                ->increment('stock', $item->quantity);
        }
    }

    private function generateOrderNumber(): string
    {
        $latestId = (int) Order::query()->max('id');

        return 'ORD-'.now()->format('Y').'-'.str_pad((string) ($latestId + 1), 4, '0', STR_PAD_LEFT);
    }
}
