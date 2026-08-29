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

                $previousStock = $product->stock;
                $product->decrement('stock', $lineItem['quantity']);
                $product->refresh();
                $this->notificationService->maybeNotifyLowStock($product, $previousStock);
            }

            $this->notificationService->notifyNewOrder($order);

            return $order->load('items.product');
        });
    }

    public function updateStatus(Order $order, string $status, ?string $expectedDeliveryAt = null, ?string $cancellationReason = null): Order
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

        return DB::transaction(function () use ($order, $status, $expectedDeliveryAt, $cancellationReason) {
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

            if ($status === 'cancelled' && filled($cancellationReason)) {
                $order->cancellation_reason = $cancellationReason;
            }

            $order->status = $status;
            $order->save();

            return $order->fresh(['items.product', 'client']);
        });
    }

    public function updateCancellationReason(Order $order, ?string $cancellationReason): Order
    {
        if ($order->status !== 'cancelled') {
            throw ValidationException::withMessages([
                'cancellation_reason' => 'يمكن إضافة سبب الإلغاء للطلبات الملغاة فقط.',
            ]);
        }

        $order->update([
            'cancellation_reason' => $cancellationReason,
        ]);

        return $order->fresh(['items.product', 'client']);
    }

    /** @param array<int|string, int> $quantities */
    public function updateItemQuantities(Order $order, array $quantities): Order
    {
        if (in_array($order->status, ['delivered', 'cancelled'], true)) {
            throw ValidationException::withMessages([
                'quantities' => 'لا يمكن تعديل كميات منتجات طلب منتهٍ أو ملغى.',
            ]);
        }

        return DB::transaction(function () use ($order, $quantities) {
            $order->load('items');

            if ($order->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'quantities' => 'لا توجد منتجات في هذا الطلب.',
                ]);
            }

            if (count($quantities) !== $order->items->count()) {
                throw ValidationException::withMessages([
                    'quantities' => 'يجب تحديد كمية لكل منتج في الطلب.',
                ]);
            }

            $subtotal = 0.0;

            foreach ($order->items as $item) {
                if (! array_key_exists($item->id, $quantities) && ! array_key_exists((string) $item->id, $quantities)) {
                    throw ValidationException::withMessages([
                        'quantities' => 'بيانات كميات المنتجات غير مكتملة.',
                    ]);
                }

                $newQuantity = (int) ($quantities[$item->id] ?? $quantities[(string) $item->id]);
                $oldQuantity = (int) $item->quantity;

                if ($newQuantity === $oldQuantity) {
                    $subtotal += (float) $item->line_total;

                    continue;
                }

                $product = Product::query()->lockForUpdate()->find($item->product_id);

                if ($product === null) {
                    throw ValidationException::withMessages([
                        'quantities' => 'المنتج «'.$item->product_name.'» لم يعد متوفراً.',
                    ]);
                }

                $quantityDiff = $newQuantity - $oldQuantity;

                if ($quantityDiff > 0 && $product->stock < $quantityDiff) {
                    throw ValidationException::withMessages([
                        'quantities' => 'المنتج «'.$item->product_name.'» غير متوفر بالكمية المطلوبة. المتوفر: '.$product->stock.'.',
                    ]);
                }

                if ($quantityDiff > 0) {
                    $previousStock = $product->stock;
                    $product->decrement('stock', $quantityDiff);
                    $product->refresh();
                    $this->notificationService->maybeNotifyLowStock($product, $previousStock);
                } elseif ($quantityDiff < 0) {
                    $product->increment('stock', abs($quantityDiff));
                }

                $lineTotal = round((float) $item->unit_price * $newQuantity, 2);

                $item->update([
                    'quantity' => $newQuantity,
                    'line_total' => $lineTotal,
                ]);

                $subtotal += $lineTotal;
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => round($subtotal + (float) $order->delivery_fee, 2),
            ]);

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
