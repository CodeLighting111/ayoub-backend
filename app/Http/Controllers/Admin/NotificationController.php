<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Services\AdminNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(private readonly AdminNotificationService $notificationService) {}

    public function index(): View
    {
        $notifications = AdminNotification::query()
            ->with('order')
            ->latest()
            ->get();

        $stats = [
            'total' => $notifications->count(),
            'unread' => $notifications->whereNull('read_at')->count(),
            'new_orders' => $notifications->where('type', AdminNotification::TYPE_NEW_ORDER)->count(),
            'cancelled' => $notifications->where('type', AdminNotification::TYPE_ORDER_CANCELLED)->count(),
        ];

        return view('dashboard.notifications.index', [
            'notifications' => $notifications,
            'stats' => $stats,
            'activeMenu' => 'notifications',
        ]);
    }

    public function show(AdminNotification $notification): RedirectResponse
    {
        $this->notificationService->markAsRead($notification);

        return redirect()->route('admin.orders.show', $notification->order_id);
    }

    public function markAllRead(): RedirectResponse
    {
        $this->notificationService->markAllAsRead();

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'تم تحديد جميع الإشعارات كمقروءة.');
    }
}
