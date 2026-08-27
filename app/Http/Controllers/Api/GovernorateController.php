<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GovernorateResource;
use App\Models\Governorate;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GovernorateController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $governorates = Governorate::query()
            ->orderBy('name')
            ->get();

        return GovernorateResource::collection($governorates);
    }

    public function show(Governorate $governorate): GovernorateResource
    {
        return new GovernorateResource($governorate);
    }
}
