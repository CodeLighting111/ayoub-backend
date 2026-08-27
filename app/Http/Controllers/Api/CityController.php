<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CityController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $governorateId = $request->query('governorate_id');

        $cities = City::query()
            ->with('governorate')
            ->when($governorateId, fn ($query) => $query->where('governorate_id', $governorateId))
            ->orderBy('name')
            ->get();

        return CityResource::collection($cities);
    }

    public function show(City $city): CityResource
    {
        $city->load('governorate');

        return new CityResource($city);
    }
}
