<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientNotification;
use Illuminate\Support\Collection;

class ClientNotificationService
{
    public function sendToClient(Client $client, string $title, string $message, ?int $adminId = null): ClientNotification
    {
        return ClientNotification::query()->create([
            'client_id' => $client->id,
            'admin_id' => $adminId,
            'title' => $title,
            'message' => $message,
        ]);
    }

    /**
     * @return Collection<int, ClientNotification>
     */
    public function sendToAllActiveClients(string $title, string $message, ?int $adminId = null): Collection
    {
        $clients = Client::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id']);

        $now = now();
        $rows = $clients->map(fn (Client $client) => [
            'client_id' => $client->id,
            'admin_id' => $adminId,
            'title' => $title,
            'message' => $message,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        if ($rows !== []) {
            ClientNotification::query()->insert($rows);
        }

        return ClientNotification::query()
            ->where('title', $title)
            ->where('message', $message)
            ->where('created_at', $now)
            ->get();
    }

    public function markAsRead(ClientNotification $notification): void
    {
        if ($notification->read_at === null) {
            $notification->update(['read_at' => now()]);
        }
    }
}
