<?php

namespace Database\Seeders;

use App\Models\AdminNotification;
use App\Models\Order;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::query()->latest()->limit(6)->get();

        if ($orders->isEmpty()) {
            $this->command?->warn('No orders found. Run OrderSeeder first, then re-run NotificationSeeder.');

            return;
        }

        $demoNotifications = [
            [
                'order_index' => 0,
                'type' => AdminNotification::TYPE_NEW_ORDER,
                'title' => null,
                'message' => null,
                'read_at' => null,
                'created_at' => now()->subMinutes(10),
            ],
            [
                'order_index' => 1,
                'type' => AdminNotification::TYPE_NEW_ORDER,
                'title' => null,
                'message' => null,
                'read_at' => null,
                'created_at' => now()->subHours(2),
            ],
            [
                'order_index' => 2,
                'type' => AdminNotification::TYPE_ORDER_CANCELLED,
                'title' => null,
                'message' => null,
                'read_at' => null,
                'created_at' => now()->subHours(5),
            ],
            [
                'order_index' => 3,
                'type' => AdminNotification::TYPE_NEW_ORDER,
                'title' => null,
                'message' => null,
                'read_at' => now()->subDay(),
                'created_at' => now()->subDay(),
            ],
            [
                'order_index' => 4,
                'type' => AdminNotification::TYPE_ORDER_CANCELLED,
                'title' => null,
                'message' => null,
                'read_at' => now()->subDays(2),
                'created_at' => now()->subDays(2),
            ],
            [
                'order_index' => 5,
                'type' => AdminNotification::TYPE_NEW_ORDER,
                'title' => null,
                'message' => null,
                'read_at' => now()->subDays(3),
                'created_at' => now()->subDays(3),
            ],
        ];

        foreach ($demoNotifications as $demo) {
            $order = $orders[$demo['order_index']] ?? $orders->first();

            AdminNotification::query()->updateOrCreate(
                [
                    'order_id' => $order->id,
                    'type' => $demo['type'],
                ],
                [
                    'title' => $demo['title'] ?? $this->defaultTitle($demo['type'], $order),
                    'message' => $demo['message'] ?? $this->defaultMessage($demo['type'], $order),
                    'read_at' => $demo['read_at'],
                    'created_at' => $demo['created_at'],
                    'updated_at' => $demo['created_at'],
                ],
            );
        }

        $this->command?->info('Demo notifications seeded.');
    }

    private function defaultTitle(string $type, Order $order): string
    {
        return match ($type) {
            AdminNotification::TYPE_NEW_ORDER => 'طلب جديد #'.$order->order_number,
            AdminNotification::TYPE_ORDER_CANCELLED => 'إلغاء طلب #'.$order->order_number,
            default => 'إشعار طلب #'.$order->order_number,
        };
    }

    private function defaultMessage(string $type, Order $order): string
    {
        return match ($type) {
            AdminNotification::TYPE_NEW_ORDER => 'قام العميل «'.$order->client_name.'» بإنشاء طلب جديد بقيمة '.number_format((float) $order->total, 2).' ج.م.',
            AdminNotification::TYPE_ORDER_CANCELLED => 'قام العميل «'.$order->client_name.'» بإلغاء الطلب.',
            default => '',
        };
    }
}
