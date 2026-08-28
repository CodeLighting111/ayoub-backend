<?php

namespace Database\Seeders;

use App\Models\AdminNotification;
use App\Models\Client;
use App\Models\ClientNotification;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdminNotifications();
        $this->seedClientNotifications();

        $synced = app(\App\Services\AdminNotificationService::class)->syncLowStockNotifications();
        if ($synced > 0) {
            $this->command?->info("Synced {$synced} low stock notifications.");
        }
    }

    private function seedAdminNotifications(): void
    {
        $orders = Order::query()->latest()->limit(4)->get();
        $complaint = Complaint::query()->latest()->first();
        $product = Product::query()->where('status', 'active')->orderBy('name')->first();

        if ($orders->isEmpty()) {
            $this->command?->warn('No orders found. Run OrderSeeder first for order notifications.');
        }

        if ($complaint === null) {
            $this->command?->warn('No complaints found. Run ComplaintSeeder first for complaint notifications.');
        }

        if ($product === null) {
            $this->command?->warn('No products found. Add products first for low stock notifications.');
        }

        $demos = [];

        if ($orders->count() >= 1) {
            $order = $orders[0];
            $demos[] = [
                'keys' => ['order_id' => $order->id, 'type' => AdminNotification::TYPE_NEW_ORDER],
                'data' => [
                    'title' => 'طلب جديد #'.$order->order_number,
                    'message' => 'قام العميل «'.$order->client_name.'» بإنشاء طلب جديد بقيمة '.number_format((float) $order->total, 2).' ج.م.',
                    'read_at' => null,
                    'created_at' => now()->subMinutes(15),
                ],
            ];
        }

        if ($orders->count() >= 2) {
            $order = $orders[1];
            $demos[] = [
                'keys' => ['order_id' => $order->id, 'type' => AdminNotification::TYPE_NEW_ORDER],
                'data' => [
                    'title' => 'طلب جديد #'.$order->order_number,
                    'message' => 'قام العميل «'.$order->client_name.'» بإنشاء طلب جديد بقيمة '.number_format((float) $order->total, 2).' ج.م.',
                    'read_at' => now()->subHour(),
                    'created_at' => now()->subHours(3),
                ],
            ];
        }

        if ($orders->count() >= 3) {
            $order = $orders[2];
            $demos[] = [
                'keys' => ['order_id' => $order->id, 'type' => AdminNotification::TYPE_ORDER_CANCELLED],
                'data' => [
                    'title' => 'إلغاء طلب #'.$order->order_number,
                    'message' => 'قام العميل «'.$order->client_name.'» بإلغاء الطلب.',
                    'read_at' => null,
                    'created_at' => now()->subHours(6),
                ],
            ];
        }

        if ($complaint !== null) {
            $demos[] = [
                'keys' => ['complaint_id' => $complaint->id, 'type' => AdminNotification::TYPE_NEW_COMPLAINT],
                'data' => [
                    'order_id' => null,
                    'title' => 'شكوى جديدة: '.$complaint->subject,
                    'message' => 'قام العميل «'.$complaint->client_name.'» بإرسال شكوى جديدة.',
                    'read_at' => null,
                    'created_at' => now()->subHours(4),
                ],
            ];
        }

        if ($product !== null) {
            $demos[] = [
                'keys' => ['product_id' => $product->id, 'type' => AdminNotification::TYPE_LOW_STOCK],
                'data' => [
                    'order_id' => null,
                    'title' => 'مخزون منخفض: '.$product->name,
                    'message' => 'وصل مخزون المنتج «'.$product->name.'» إلى 8 قطعة. يرجى إعادة التعبئة.',
                    'read_at' => null,
                    'created_at' => now()->subHours(2),
                ],
            ];

            $secondProduct = Product::query()
                ->where('status', 'active')
                ->whereKeyNot($product->id)
                ->orderBy('name')
                ->first();

            if ($secondProduct !== null) {
                $demos[] = [
                    'keys' => ['product_id' => $secondProduct->id, 'type' => AdminNotification::TYPE_LOW_STOCK],
                    'data' => [
                        'order_id' => null,
                        'title' => 'مخزون منخفض: '.$secondProduct->name,
                        'message' => 'وصل مخزون المنتج «'.$secondProduct->name.'» إلى 5 قطع. يرجى إعادة التعبئة.',
                        'read_at' => now()->subDay(),
                        'created_at' => now()->subDay(),
                    ],
                ];
            }
        }

        if ($orders->count() >= 4) {
            $order = $orders[3];
            $demos[] = [
                'keys' => ['order_id' => $order->id, 'type' => AdminNotification::TYPE_ORDER_CANCELLED],
                'data' => [
                    'title' => 'إلغاء طلب #'.$order->order_number,
                    'message' => 'قام العميل «'.$order->client_name.'» بإلغاء الطلب.',
                    'read_at' => now()->subDays(2),
                    'created_at' => now()->subDays(2),
                ],
            ];
        }

        foreach ($demos as $demo) {
            AdminNotification::query()->updateOrCreate(
                $demo['keys'],
                array_merge($demo['data'], [
                    'updated_at' => $demo['data']['created_at'],
                ]),
            );
        }

        $this->command?->info('Seeded '.count($demos).' demo admin notifications.');
    }

    private function seedClientNotifications(): void
    {
        $clients = Client::query()->where('status', 'active')->orderBy('name')->limit(2)->get();
        $adminId = \App\Models\Admin::query()->value('id');

        if ($clients->isEmpty()) {
            $this->command?->warn('No active clients found for client notification demos.');

            return;
        }

        $demos = [
            [
                'client_index' => 0,
                'title' => 'عرض خاص على المشروبات',
                'message' => 'خصم 15% على جميع المشروبات الغازية حتى نهاية الأسبوع.',
                'read_at' => null,
                'created_at' => now()->subHours(1),
            ],
            [
                'client_index' => 0,
                'title' => 'تم قبول طلبك',
                'message' => 'تم قبول طلبك الأخير وجاري تجهيزه للتوصيل.',
                'read_at' => now()->subHours(5),
                'created_at' => now()->subHours(8),
            ],
            [
                'client_index' => 1,
                'title' => 'تحديث مواعيد التوصيل',
                'message' => 'تم تحديث مواعيد التوصيل في منطقتك. يمكنك الطلب الآن حتى 10 مساءً.',
                'read_at' => null,
                'created_at' => now()->subMinutes(30),
            ],
        ];

        foreach ($demos as $demo) {
            $client = $clients[$demo['client_index']] ?? $clients->first();

            ClientNotification::query()->updateOrCreate(
                [
                    'client_id' => $client->id,
                    'title' => $demo['title'],
                ],
                [
                    'admin_id' => $adminId,
                    'message' => $demo['message'],
                    'read_at' => $demo['read_at'],
                    'created_at' => $demo['created_at'],
                    'updated_at' => $demo['created_at'],
                ],
            );
        }

        $this->command?->info('Seeded '.count($demos).' demo client notifications.');
    }
}
