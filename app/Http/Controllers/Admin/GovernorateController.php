<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GovernorateRequest;
use App\Models\Governorate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GovernorateController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $governorates = Governorate::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->orderBy('name')
            ->get();

        return view('dashboard.governorates.index', [
            'governorates' => $governorates,
            'search' => $search,
            'activeMenu' => 'governorates',
        ]);
    }

    public function create(): View
    {
        return view('dashboard.governorates.create', [
            'activeMenu' => 'governorates',
            'governorate' => new Governorate,
        ]);
    }

    public function store(GovernorateRequest $request): RedirectResponse
    {
        Governorate::query()->create($request->validated());

        return redirect()
            ->route('admin.governorates.index')
            ->with('success', 'تم إضافة المحافظة بنجاح.');
    }

    public function edit(Governorate $governorate): View
    {
        return view('dashboard.governorates.edit', [
            'activeMenu' => 'governorates',
            'governorate' => $governorate,
        ]);
    }

    public function update(GovernorateRequest $request, Governorate $governorate): RedirectResponse
    {
        $governorate->update($request->validated());

        return redirect()
            ->route('admin.governorates.index')
            ->with('success', 'تم تحديث المحافظة بنجاح.');
    }

    public function destroy(Governorate $governorate): RedirectResponse
    {
        $governorate->delete();

        return redirect()
            ->route('admin.governorates.index')
            ->with('success', 'تم حذف المحافظة بنجاح.');
    }
}
