<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MainProductCategoryResource;
use App\Models\MainProductCategory;

class MainProductCategoryController extends Controller
{
    public function index()
    {
        $categories = MainProductCategory::query()
            ->orderBy('title')
            ->get();

        return MainProductCategoryResource::collection($categories);
    }

    public function show(MainProductCategory $main_product_category): MainProductCategoryResource
    {
        return new MainProductCategoryResource($main_product_category);
    }
}
