<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientRequest;
use App\Models\Area;
use App\Models\City;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\Governorate;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $clients = Client::query()
            ->with(['category', 'governorate', 'city', 'area'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%')
                        ->orWhere('branch_name', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('name')
            ->get();

        return view('dashboard.clients.index', [
            'clients' => $clients,
            'search' => $search,
            'activeMenu' => 'clients',
        ]);
    }

    public function create(): View
    {
        return view('dashboard.clients.create', array_merge(
            $this->formOptions(),
            [
                'activeMenu' => 'clients',
                'client' => new Client(['status' => 'active']),
            ]
        ));
    }

    public function store(ClientRequest $request): RedirectResponse
    {
        Client::query()->create($request->validated());

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'تم إضافة العميل بنجاح.');
    }

    public function show(Request $request, Client $client): View
    {
        $client->load(['category', 'governorate', 'city', 'area']);

        $status = (string) $request->query('status', '');
        $month = $request->query('month');
        $year = $request->query('year');

        $stats = [
            'total' => $client->orders()->count(),
            'pending' => $client->orders()->where('status', 'pending')->count(),
            'accepted' => $client->orders()->where('status', 'accepted')->count(),
            'processing' => $client->orders()->where('status', 'processing')->count(),
            'shipped' => $client->orders()->where('status', 'shipped')->count(),
            'delivered' => $client->orders()->where('status', 'delivered')->count(),
            'cancelled' => $client->orders()->where('status', 'cancelled')->count(),
        ];

        $monthInt = is_numeric($month) ? (int) $month : null;
        $yearInt = is_numeric($year) ? (int) $year : null;
        $hasMonthFilter = $monthInt >= 1 && $monthInt <= 12 && $yearInt >= 2000;

        $monthlyTotal = null;
        $monthlyOrdersCount = null;

        if ($hasMonthFilter) {
            $monthlyOrdersCount = $client->orders()
                ->whereYear('created_at', $yearInt)
                ->whereMonth('created_at', $monthInt)
                ->count();

            $monthlyTotal = $client->orders()
                ->whereYear('created_at', $yearInt)
                ->whereMonth('created_at', $monthInt)
                ->where('status', '!=', 'cancelled')
                ->sum('total');
        }

        $orders = $client->orders()
            ->when(in_array($status, Order::STATUSES, true), fn ($query) => $query->where('status', $status))
            ->when($hasMonthFilter, fn ($query) => $query
                ->whereYear('created_at', $yearInt)
                ->whereMonth('created_at', $monthInt))
            ->latest()
            ->get();

        return view('dashboard.clients.show', [
            'activeMenu' => 'clients',
            'client' => $client,
            'orders' => $orders,
            'stats' => $stats,
            'status' => $status,
            'month' => $hasMonthFilter ? $monthInt : null,
            'year' => $hasMonthFilter ? $yearInt : null,
            'monthlyTotal' => $monthlyTotal,
            'monthlyOrdersCount' => $monthlyOrdersCount,
        ]);
    }

    public function edit(Client $client): View
    {
        return view('dashboard.clients.edit', array_merge(
            $this->formOptions(),
            [
                'activeMenu' => 'clients',
                'client' => $client,
            ]
        ));
    }

    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $client->update($data);

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'تم تحديث بيانات العميل بنجاح.');
    }

    public function updateStatus(Request $request, Client $client): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:active,suspended'],
        ], [
            'status.required' => 'حالة العميل مطلوبة.',
            'status.in' => 'حالة العميل غير صالحة.',
        ]);

        $client->update(['status' => $request->input('status')]);

        $message = $client->status === 'active'
            ? 'تم تفعيل العميل بنجاح.'
            : 'تم تعطيل العميل بنجاح.';

        return redirect()
            ->route('admin.clients.index')
            ->with('success', $message);
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'تم حذف العميل بنجاح.');
    }

    private function formOptions(): array
    {
        return [
            'categories' => ClientCategory::query()->orderBy('title')->get(),
            'governorates' => Governorate::query()->orderBy('name')->get(),
            'cities' => City::query()->orderBy('name')->get(['id', 'name', 'governorate_id']),
            'areas' => Area::query()->orderBy('name')->get(['id', 'name', 'city_id']),
        ];
    }
}
