<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\ClientNotification;
use App\Services\AdminNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(private readonly AdminNotificationService $notificationService) {}

    public function index(): View
    {
        $notifications = AdminNotification::query()
            ->with(['order', 'complaint', 'product'])
            ->latest()
            ->get();

        $stats = [
            'total' => $notifications->count(),
            'unread' => $notifications->whereNull('read_at')->count(),
            'new_orders' => $notifications->where('type', AdminNotification::TYPE_NEW_ORDER)->count(),
            'cancelled' => $notifications->where('type', AdminNotification::TYPE_ORDER_CANCELLED)->count(),
            'complaints' => $notifications->where('type', AdminNotification::TYPE_NEW_COMPLAINT)->count(),
            'low_stock' => $notifications->where('type', AdminNotification::TYPE_LOW_STOCK)->count(),
        ];

        $sentNotifications = ClientNotification::query()
            ->with(['client:id,name,phone', 'admin:id,name'])
            ->latest()
            ->limit(20)
            ->get();

        return view('dashboard.notifications.index', [
            'notifications' => $notifications,
            'sentNotifications' => $sentNotifications,
            'stats' => $stats,
            'activeMenu' => 'notifications',
        ]);
    }

    public function show(AdminNotification $notification): RedirectResponse
    {
        $this->notificationService->markAsRead($notification);

        $target = $notification->redirectTarget();

        if ($target === null) {
            return redirect()->route('admin.notifications.index');
        }

        return redirect()->route($target[0], $target[1]);
    }

    public function markAllRead(): RedirectResponse
    {
        $this->notificationService->markAllAsRead();

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'تم تحديد جميع الإشعارات كمقروءة.');
    }
}
