<?php

namespace App\Services;

use App\Models\AdminNotification;
use App\Models\Order;

class AdminNotificationService
{
    public function notifyNewOrder(Order $order): AdminNotification
    {
        return AdminNotification::query()->create([
            'order_id' => $order->id,
            'type' => AdminNotification::TYPE_NEW_ORDER,
            'title' => 'طلب جديد #'.$order->order_number,
            'message' => 'قام العميل «'.$order->client_name.'» بإنشاء طلب جديد بقيمة '.number_format((float) $order->total, 2).' ج.م.',
        ]);
    }

    public function notifyOrderCancelledByClient(Order $order): AdminNotification
    {
        return AdminNotification::query()->create([
            'order_id' => $order->id,
            'type' => AdminNotification::TYPE_ORDER_CANCELLED,
            'title' => 'إلغاء طلب #'.$order->order_number,
            'message' => 'قام العميل «'.$order->client_name.'» بإلغاء الطلب.',
        ]);
    }

    public function markAsRead(AdminNotification $notification): AdminNotification
    {
        if ($notification->read_at === null) {
            $notification->update(['read_at' => now()]);
        }

        return $notification->fresh(['order']);
    }

    public function markAllAsRead(): int
    {
        return AdminNotification::query()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function unreadCount(): int
    {
        return AdminNotification::query()->whereNull('read_at')->count();
    }
}
