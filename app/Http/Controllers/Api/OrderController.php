<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Client;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = Order::query()
            ->withCount('items')
            ->with('items')
            ->when($request->query('client_id'), fn ($query, $id) => $query->where('client_id', $id))
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->get();

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $client = Client::query()->findOrFail($request->validated('client_id'));

        $order = $this->orderService->create(
            $client,
            $request->validated('items'),
            $request->validated('payment_method'),
            $request->validated('notes'),
            $request->validated('preferred_delivery_at'),
        );

        return (new OrderResource($order->load(['items'])->loadCount('items')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Order $order): OrderResource
    {
        $order->load(['items.product'])->loadCount('items');

        return new OrderResource($order);
    }

    public function cancel(Order $order): OrderResource
    {
        $order = $this->orderService->cancelByClient($order);

        return new OrderResource($order->load(['items'])->loadCount('items'));
    }
}
