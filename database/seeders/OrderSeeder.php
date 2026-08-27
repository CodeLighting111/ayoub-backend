<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::query()->with(['governorate', 'city', 'area'])->first();

        if ($client === null) {
            $this->command?->warn('No clients found. Add a client first, then re-run OrderSeeder.');

            return;
        }

        $products = Product::query()->where('status', 'active')->limit(3)->get();

        if ($products->isEmpty()) {
            $this->command?->warn('No active products found. Add products first, then re-run OrderSeeder.');

            return;
        }

        $demoOrders = [
            [
                'order_number' => 'ORD-2026-DEMO-001',
                'status' => 'pending',
                'payment_method' => 'cash',
                'payment_status' => 'unpaid',
                'notes' => 'طلب تجريبي — قيد الانتظار',
                'created_at' => now()->subDays(1),
            ],
            [
                'order_number' => 'ORD-2026-DEMO-002',
                'status' => 'accepted',
                'payment_method' => 'wallet',
                'payment_status' => 'unpaid',
                'notes' => 'طلب تجريبي — مقبول',
                'created_at' => now()->subDays(2),
            ],
            [
                'order_number' => 'ORD-2026-DEMO-003',
                'status' => 'processing',
                'payment_method' => 'cash',
                'payment_status' => 'unpaid',
                'notes' => 'طلب تجريبي — قيد التجهيز',
                'created_at' => now()->subDays(3),
            ],
            [
                'order_number' => 'ORD-2026-DEMO-004',
                'status' => 'shipped',
                'payment_method' => 'bank_transfer',
                'payment_status' => 'unpaid',
                'notes' => 'طلب تجريبي — تم الشحن',
                'created_at' => now()->subDays(4),
            ],
            [
                'order_number' => 'ORD-2026-DEMO-005',
                'status' => 'delivered',
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'notes' => 'طلب تجريبي — تم التوصيل',
                'created_at' => now()->subDays(5),
                'delivered_at' => now()->subDays(4),
            ],
            [
                'order_number' => 'ORD-2026-DEMO-006',
                'status' => 'cancelled',
                'payment_method' => 'wallet',
                'payment_status' => 'unpaid',
                'notes' => 'طلب تجريبي — ملغى',
                'created_at' => now()->subDays(6),
                'cancelled_at' => now()->subDays(5),
            ],
        ];

        foreach ($demoOrders as $index => $demo) {
            $lineProducts = $products->slice($index % max(1, $products->count() - 1), 2)->values();
            if ($lineProducts->isEmpty()) {
                $lineProducts = $products->take(1);
            }

            $subtotal = 0.0;
            $lineItems = [];

            foreach ($lineProducts as $position => $product) {
                $quantity = $position + 1;
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

            $deliveryFee = OrderService::DELIVERY_FEE;
            $total = round($subtotal + $deliveryFee, 2);

            $order = Order::query()->updateOrCreate(
                ['order_number' => $demo['order_number']],
                [
                    'client_id' => $client->id,
                    'status' => $demo['status'],
                    'payment_method' => $demo['payment_method'],
                    'payment_status' => $demo['payment_status'],
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'total' => $total,
                    'notes' => $demo['notes'],
                    'client_name' => $client->name,
                    'client_phone' => $client->phone,
                    'branch_name' => $client->branch_name,
                    'delivery_address' => $client->address,
                    'governorate_name' => $client->governorate?->name,
                    'city_name' => $client->city?->name,
                    'area_name' => $client->area?->name,
                    'preferred_delivery_at' => $demo['created_at']->copy()->addDays(2)->setTime(10, 0),
                    'expected_delivery_at' => $demo['created_at']->copy()->addDay(),
                    'delivered_at' => $demo['delivered_at'] ?? null,
                    'cancelled_at' => $demo['cancelled_at'] ?? null,
                    'created_at' => $demo['created_at'],
                    'updated_at' => $demo['created_at'],
                ],
            );

            $order->items()->delete();

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
            }
        }

        $this->command?->info('Created/updated '.count($demoOrders).' demo orders (one per status).');
    }
}
