<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendClientNotificationRequest;
use App\Models\Client;
use App\Services\ClientNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientNotificationController extends Controller
{
    public function __construct(private readonly ClientNotificationService $notificationService) {}

    public function create(): View
    {
        $selectedClientId = request()->integer('client_id') ?: null;

        return view('dashboard.notifications.send', [
            'activeMenu' => 'notifications',
            'selectedClientId' => $selectedClientId,
            'clients' => Client::query()
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'phone', 'branch_name']),
        ]);
    }

    public function store(SendClientNotificationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $adminId = auth('admin')->id();

        if ($data['target'] === 'all') {
            $sent = $this->notificationService->sendToAllActiveClients(
                $data['title'],
                $data['message'],
                $adminId,
            );

            $message = $sent->isEmpty()
                ? 'لا يوجد عملاء نشطون لإرسال الإشعار إليهم.'
                : 'تم إرسال الإشعار إلى '.$sent->count().' عميل نشط.';
        } else {
            $client = Client::query()->findOrFail($data['client_id']);

            $this->notificationService->sendToClient(
                $client,
                $data['title'],
                $data['message'],
                $adminId,
            );

            $message = 'تم إرسال الإشعار إلى العميل «'.$client->name.'» بنجاح.';
        }

        $redirectRoute = ($data['return_to'] ?? null) === 'clients'
            ? route('admin.clients.index')
            : route('admin.notifications.index');

        return redirect()
            ->to($redirectRoute)
            ->with('success', $message);
    }
}
