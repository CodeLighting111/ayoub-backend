<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AreaRequest;
use App\Models\Area;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AreaController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $areas = Area::query()
            ->with(['city.governorate'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhereHas('city', function ($query) use ($search) {
                            $query->where('name', 'like', '%'.$search.'%')
                                ->orWhereHas('governorate', fn ($query) => $query->where('name', 'like', '%'.$search.'%'));
                        });
                });
            })
            ->orderBy('name')
            ->get();

        return view('dashboard.areas.index', [
            'areas' => $areas,
            'search' => $search,
            'activeMenu' => 'areas',
        ]);
    }

    public function create(): View
    {
        return view('dashboard.areas.create', [
            'activeMenu' => 'areas',
            'area' => new Area,
            'cities' => City::query()->with('governorate')->orderBy('name')->get(),
        ]);
    }

    public function store(AreaRequest $request): RedirectResponse
    {
        Area::query()->create($request->validated());

        return redirect()
            ->route('admin.areas.index')
            ->with('success', 'تم إضافة المنطقة بنجاح.');
    }

    public function edit(Area $area): View
    {
        return view('dashboard.areas.edit', [
            'activeMenu' => 'areas',
            'area' => $area,
            'cities' => City::query()->with('governorate')->orderBy('name')->get(),
        ]);
    }

    public function update(AreaRequest $request, Area $area): RedirectResponse
    {
        $area->update($request->validated());

        return redirect()
            ->route('admin.areas.index')
            ->with('success', 'تم تحديث المنطقة بنجاح.');
    }

    public function destroy(Area $area): RedirectResponse
    {
        $area->delete();

        return redirect()
            ->route('admin.areas.index')
            ->with('success', 'تم حذف المنطقة بنجاح.');
    }
}
