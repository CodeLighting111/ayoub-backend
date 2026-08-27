<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Complaint;
use Illuminate\Validation\ValidationException;

class ComplaintService
{
    public function create(Client $client, string $subject, string $message, ?int $orderId = null): Complaint
    {
        if ($orderId !== null && ! $client->orders()->whereKey($orderId)->exists()) {
            throw ValidationException::withMessages([
                'order_id' => 'الطلب غير مرتبط بهذا العميل.',
            ]);
        }

        return Complaint::query()->create([
            'client_id' => $client->id,
            'order_id' => $orderId,
            'status' => 'pending',
            'subject' => $subject,
            'message' => $message,
            'client_name' => $client->name,
            'client_phone' => $client->phone,
        ]);
    }

    public function updateStatus(Complaint $complaint, string $status, ?string $adminResponse = null): Complaint
    {
        if (! in_array($status, Complaint::STATUSES, true)) {
            throw ValidationException::withMessages([
                'status' => 'حالة الشكوى غير صالحة.',
            ]);
        }

        if ($complaint->status !== 'pending' && $status !== $complaint->status) {
            throw ValidationException::withMessages([
                'status' => 'لا يمكن تعديل شكوى تم حلها أو رفضها.',
            ]);
        }

        $complaint->status = $status;
        $complaint->admin_response = $adminResponse;

        if ($status === 'resolved') {
            $complaint->resolved_at = now();
            $complaint->rejected_at = null;
        }

        if ($status === 'rejected') {
            $complaint->rejected_at = now();
            $complaint->resolved_at = null;
        }

        if ($status === 'pending') {
            $complaint->resolved_at = null;
            $complaint->rejected_at = null;
        }

        $complaint->save();

        return $complaint->fresh(['client', 'order']);
    }
}
