<?php

namespace App\Services;

use App\Models\AdminNotification;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\Product;

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

    public function notifyNewComplaint(Complaint $complaint): AdminNotification
    {
        return AdminNotification::query()->create([
            'complaint_id' => $complaint->id,
            'type' => AdminNotification::TYPE_NEW_COMPLAINT,
            'title' => 'شكوى جديدة: '.$complaint->subject,
            'message' => 'قام العميل «'.$complaint->client_name.'» بإرسال شكوى جديدة.',
        ]);
    }

    public function notifyLowStock(Product $product): AdminNotification
    {
        return AdminNotification::query()->create([
            'product_id' => $product->id,
            'type' => AdminNotification::TYPE_LOW_STOCK,
            'title' => 'مخزون منخفض: '.$product->name,
            'message' => 'وصل مخزون المنتج «'.$product->name.'» إلى '.$product->stock.' قطعة. يرجى إعادة التعبئة.',
        ]);
    }

    public function notifyLowStockIfNeeded(Product $product): ?AdminNotification
    {
        if ($product->stock > AdminNotification::LOW_STOCK_THRESHOLD) {
            return null;
        }

        $hasUnread = AdminNotification::query()
            ->where('type', AdminNotification::TYPE_LOW_STOCK)
            ->where('product_id', $product->id)
            ->whereNull('read_at')
            ->exists();

        if ($hasUnread) {
            return null;
        }

        return $this->notifyLowStock($product);
    }

    public function maybeNotifyLowStock(Product $product, int $previousStock): ?AdminNotification
    {
        if ($product->stock > AdminNotification::LOW_STOCK_THRESHOLD) {
            return null;
        }

        if ($previousStock <= AdminNotification::LOW_STOCK_THRESHOLD) {
            return $this->notifyLowStockIfNeeded($product);
        }

        return $this->notifyLowStock($product);
    }

    public function syncLowStockNotifications(): int
    {
        $created = 0;

        Product::query()
            ->where('stock', '<=', AdminNotification::LOW_STOCK_THRESHOLD)
            ->orderBy('name')
            ->each(function (Product $product) use (&$created) {
                if ($this->notifyLowStockIfNeeded($product) !== null) {
                    $created++;
                }
            });

        return $created;
    }

    public function markAsRead(AdminNotification $notification): AdminNotification
    {
        if ($notification->read_at === null) {
            $notification->update(['read_at' => now()]);
        }

        return $notification->fresh(['order', 'complaint', 'product']);
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

    public function unreadNewOrdersCount(): int
    {
        return AdminNotification::query()
            ->where('type', AdminNotification::TYPE_NEW_ORDER)
            ->whereNull('read_at')
            ->count();
    }

    public function unreadLowStockProductsCount(): int
    {
        return AdminNotification::query()
            ->where('type', AdminNotification::TYPE_LOW_STOCK)
            ->whereNull('read_at')
            ->whereNotNull('product_id')
            ->distinct()
            ->count('product_id');
    }

    public function unreadComplaintsCount(): int
    {
        return AdminNotification::query()
            ->where('type', AdminNotification::TYPE_NEW_COMPLAINT)
            ->whereNull('read_at')
            ->count();
    }

    /** @return array{orders: int, products: int, notifications: int, complaints: int} */
    public function sidebarBadgeCounts(): array
    {
        return [
            'orders' => $this->unreadNewOrdersCount(),
            'products' => $this->unreadLowStockProductsCount(),
            'notifications' => $this->unreadCount(),
            'complaints' => $this->unreadComplaintsCount(),
        ];
    }
}
