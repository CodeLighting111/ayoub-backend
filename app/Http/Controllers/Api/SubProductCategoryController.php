<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubProductCategoryResource;
use App\Models\SubProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubProductCategoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $mainCategoryId = $request->query('main_product_category_id');

        $categories = SubProductCategory::query()
            ->with('mainCategory')
            ->when($mainCategoryId, fn ($query) => $query->where('main_product_category_id', $mainCategoryId))
            ->orderBy('title')
            ->get();

        return SubProductCategoryResource::collection($categories);
    }

    public function show(SubProductCategory $sub_product_category): SubProductCategoryResource
    {
        $sub_product_category->load('mainCategory');

        return new SubProductCategoryResource($sub_product_category);
    }
}
