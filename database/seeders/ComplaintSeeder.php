<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Complaint;
use App\Models\Order;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::query()->first();

        if ($client === null) {
            $this->command?->warn('No clients found. Add a client first, then re-run ComplaintSeeder.');

            return;
        }

        $order = Order::query()->where('client_id', $client->id)->first();

        $demoComplaints = [
            [
                'status' => 'pending',
                'subject' => 'تأخر في التوصيل',
                'message' => 'أواجه مشكلة في استلام طلبي الأخير، حيث تأخر المندوب لأكثر من 3 ساعات ولم يتم الرد على اتصالاتي.',
                'created_at' => now()->subDay(),
            ],
            [
                'status' => 'resolved',
                'subject' => 'منتج ناقص من الطلب',
                'message' => 'استلمت الطلب ولكن كان ينقصه صنف واحد من المنتجات المطلوبة. تم التواصل معي وحل المشكلة.',
                'admin_response' => 'تم التواصل مع العميل وإرسال المنتج الناقص.',
                'resolved_at' => now()->subHours(12),
                'created_at' => now()->subDays(2),
            ],
            [
                'status' => 'rejected',
                'subject' => 'طلب استرجاع غير مطابق',
                'message' => 'أريد استرجاع منتج تم فتح عبوته واستخدام جزء منه.',
                'admin_response' => 'لا يمكن استرجاع المنتجات المفتوحة أو المستخدمة وفق سياسة المتجر.',
                'rejected_at' => now()->subHours(6),
                'created_at' => now()->subDays(3),
            ],
        ];

        foreach ($demoComplaints as $index => $demo) {
            Complaint::query()->updateOrCreate(
                [
                    'client_id' => $client->id,
                    'subject' => $demo['subject'],
                ],
                [
                    'order_id' => $index === 0 ? $order?->id : null,
                    'status' => $demo['status'],
                    'message' => $demo['message'],
                    'client_name' => $client->name,
                    'client_phone' => $client->phone,
                    'admin_response' => $demo['admin_response'] ?? null,
                    'resolved_at' => $demo['resolved_at'] ?? null,
                    'rejected_at' => $demo['rejected_at'] ?? null,
                    'created_at' => $demo['created_at'],
                    'updated_at' => $demo['created_at'],
                ],
            );
        }

        $this->command?->info('Created/updated '.count($demoComplaints).' demo complaints (one per status).');
    }
}
