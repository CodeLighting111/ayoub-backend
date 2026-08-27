<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CityRequest;
use App\Models\City;
use App\Models\Governorate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $cities = City::query()
            ->with('governorate')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhereHas('governorate', fn ($query) => $query->where('name', 'like', '%'.$search.'%'));
                });
            })
            ->orderBy('name')
            ->get();

        return view('dashboard.cities.index', [
            'cities' => $cities,
            'search' => $search,
            'activeMenu' => 'cities',
        ]);
    }

    public function create(): View
    {
        return view('dashboard.cities.create', [
            'activeMenu' => 'cities',
            'city' => new City,
            'governorates' => Governorate::query()->orderBy('name')->get(),
        ]);
    }

    public function store(CityRequest $request): RedirectResponse
    {
        City::query()->create($request->validated());

        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'تم إضافة المدينة بنجاح.');
    }

    public function edit(City $city): View
    {
        return view('dashboard.cities.edit', [
            'activeMenu' => 'cities',
            'city' => $city,
            'governorates' => Governorate::query()->orderBy('name')->get(),
        ]);
    }

    public function update(CityRequest $request, City $city): RedirectResponse
    {
        $city->update($request->validated());

        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'تم تحديث المدينة بنجاح.');
    }

    public function destroy(City $city): RedirectResponse
    {
        $city->delete();

        return redirect()
            ->route('admin.cities.index')
            ->with('success', 'تم حذف المدينة بنجاح.');
    }
}
