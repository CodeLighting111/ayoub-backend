<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientNotificationResource;
use App\Models\Client;
use App\Models\ClientNotification;
use App\Services\ClientNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientNotificationController extends Controller
{
    public function __construct(private readonly ClientNotificationService $notificationService) {}

    public function index(Request $request, Client $client): AnonymousResourceCollection
    {
        $notifications = ClientNotification::query()
            ->where('client_id', $client->id)
            ->latest()
            ->get();

        return ClientNotificationResource::collection($notifications);
    }

    public function markAsRead(Client $client, ClientNotification $notification): JsonResponse
    {
        abort_unless($notification->client_id === $client->id, 404);

        $this->notificationService->markAsRead($notification);

        return response()->json([
            'message' => 'تم تحديد الإشعار كمقروء.',
            'data' => ClientNotificationResource::make($notification->fresh()),
        ]);
    }

    public function markAllAsRead(Client $client): JsonResponse
    {
        ClientNotification::query()
            ->where('client_id', $client->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'message' => 'تم تحديد جميع الإشعارات كمقروءة.',
        ]);
    }
}
