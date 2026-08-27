<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AreaController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $cityId = $request->query('city_id');

        $areas = Area::query()
            ->with(['city.governorate'])
            ->when($cityId, fn ($query) => $query->where('city_id', $cityId))
            ->orderBy('name')
            ->get();

        return AreaResource::collection($areas);
    }

    public function show(Area $area): AreaResource
    {
        $area->load(['city.governorate']);

        return new AreaResource($area);
    }
}
