<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderCancellationReasonRequest;
use App\Http\Requests\Admin\OrderStatusRequest;
use App\Http\Requests\Admin\OrderUpdateItemsRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $orders = Order::query()
            ->withCount('items')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('order_number', 'like', '%'.$search.'%')
                        ->orWhere('client_name', 'like', '%'.$search.'%')
                        ->orWhere('branch_name', 'like', '%'.$search.'%')
                        ->orWhere('client_phone', 'like', '%'.$search.'%');
                });
            })
            ->when(in_array($status, Order::STATUSES, true), fn ($query) => $query->where('status', $status))
            ->latest()
            ->get();

        $stats = [
            'total' => Order::query()->count(),
            'pending' => Order::query()->where('status', 'pending')->count(),
            'accepted' => Order::query()->where('status', 'accepted')->count(),
            'processing' => Order::query()->where('status', 'processing')->count(),
            'shipped' => Order::query()->where('status', 'shipped')->count(),
            'delivered' => Order::query()->where('status', 'delivered')->count(),
            'cancelled' => Order::query()->where('status', 'cancelled')->count(),
        ];

        return view('dashboard.orders.index', [
            'orders' => $orders,
            'search' => $search,
            'status' => $status,
            'stats' => $stats,
            'activeMenu' => 'orders',
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['items.product', 'client.governorate', 'client.city', 'client.area']);

        return view('dashboard.orders.show', [
            'order' => $order,
            'activeMenu' => 'orders',
        ]);
    }

    public function updateStatus(OrderStatusRequest $request, Order $order): RedirectResponse
    {
        $this->orderService->updateStatus(
            $order,
            $request->validated('status'),
            $request->validated('expected_delivery_at'),
            $request->validated('cancellation_reason'),
        );

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'تم تحديث الطلب بنجاح.');
    }

    public function updateCancellationReason(OrderCancellationReasonRequest $request, Order $order): RedirectResponse
    {
        $this->orderService->updateCancellationReason(
            $order,
            $request->validated('cancellation_reason'),
        );

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'تم حفظ سبب الإلغاء بنجاح.');
    }

    public function updateItems(OrderUpdateItemsRequest $request, Order $order): RedirectResponse
    {
        $this->orderService->updateItemQuantities(
            $order,
            $request->validated('quantities'),
        );

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'تم تحديث كميات المنتجات بنجاح.');
    }
}
