<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::query()
            ->orderBy('name')
            ->get();

        return BrandResource::collection($brands);
    }

    public function show(Brand $brand): BrandResource
    {
        return new BrandResource($brand);
    }
}
