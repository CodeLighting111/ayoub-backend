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
            [
                'order_number' => 'ORD-2026-DEMO-007',
                'status' => 'delivered',
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'notes' => '[تجريبي] طلب يوليو — تم التوصيل',
                'created_at' => now()->setDate(2026, 7, 5)->setTime(11, 30),
                'delivered_at' => now()->setDate(2026, 7, 6)->setTime(14, 0),
                'quantities' => [2, 1],
            ],
            [
                'order_number' => 'ORD-2026-DEMO-008',
                'status' => 'delivered',
                'payment_method' => 'wallet',
                'payment_status' => 'paid',
                'notes' => '[تجريبي] طلب يوليو — تم التوصيل',
                'created_at' => now()->setDate(2026, 7, 18)->setTime(9, 15),
                'delivered_at' => now()->setDate(2026, 7, 19)->setTime(16, 30),
                'quantities' => [3, 2],
            ],
            [
                'order_number' => 'ORD-2026-DEMO-009',
                'status' => 'cancelled',
                'payment_method' => 'cash',
                'payment_status' => 'unpaid',
                'notes' => '[تجريبي] طلب يوليو — ملغى',
                'created_at' => now()->setDate(2026, 7, 25)->setTime(18, 45),
                'cancelled_at' => now()->setDate(2026, 7, 26)->setTime(10, 0),
                'quantities' => [1, 1],
            ],
            [
                'order_number' => 'ORD-2026-DEMO-010',
                'status' => 'delivered',
                'payment_method' => 'bank_transfer',
                'payment_status' => 'paid',
                'notes' => '[تجريبي] طلب يونيو — تم التوصيل',
                'created_at' => now()->setDate(2026, 6, 10)->setTime(13, 20),
                'delivered_at' => now()->setDate(2026, 6, 11)->setTime(11, 0),
                'quantities' => [4, 1],
            ],
            [
                'order_number' => 'ORD-2026-DEMO-011',
                'status' => 'delivered',
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'notes' => '[تجريبي] طلب يونيو — تم التوصيل',
                'created_at' => now()->setDate(2026, 6, 22)->setTime(8, 0),
                'delivered_at' => now()->setDate(2026, 6, 23)->setTime(15, 45),
                'quantities' => [2, 3],
            ],
            [
                'order_number' => 'ORD-2026-DEMO-012',
                'status' => 'delivered',
                'payment_method' => 'wallet',
                'payment_status' => 'paid',
                'notes' => '[تجريبي] طلب مايو — تم التوصيل',
                'created_at' => now()->setDate(2026, 5, 14)->setTime(16, 10),
                'delivered_at' => now()->setDate(2026, 5, 15)->setTime(12, 30),
                'quantities' => [5, 2],
            ],
            [
                'order_number' => 'ORD-2026-DEMO-013',
                'status' => 'delivered',
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'notes' => '[تجريبي] طلب أغسطس إضافي — تم التوصيل',
                'created_at' => now()->setDate(2026, 8, 10)->setTime(10, 0),
                'delivered_at' => now()->setDate(2026, 8, 11)->setTime(13, 0),
                'quantities' => [2, 2],
            ],
            [
                'order_number' => 'ORD-2026-DEMO-014',
                'status' => 'shipped',
                'payment_method' => 'cash',
                'payment_status' => 'unpaid',
                'notes' => '[تجريبي] طلب أغسطس — تم الشحن',
                'created_at' => now()->setDate(2026, 8, 15)->setTime(14, 30),
                'quantities' => [1, 2],
            ],
        ];

        foreach ($demoOrders as $index => $demo) {
            $quantities = $demo['quantities'] ?? [($index % 2) + 1, 1];
            $lineProducts = collect([$products[0], $products[1] ?? $products[0]]);

            $subtotal = 0.0;
            $lineItems = [];

            foreach ($lineProducts as $position => $product) {
                $quantity = $quantities[$position] ?? ($position + 1);
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

        $this->command?->info('Created/updated '.count($demoOrders).' demo orders for client «'.$client->name.'».');
    }
}
