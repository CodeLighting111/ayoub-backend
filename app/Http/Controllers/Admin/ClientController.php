<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientRequest;
use App\Models\Area;
use App\Models\City;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\Governorate;
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

    public function show(Client $client): View
    {
        $client->load(['category', 'governorate', 'city', 'area']);

        return view('dashboard.clients.show', [
            'activeMenu' => 'clients',
            'client' => $client,
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
