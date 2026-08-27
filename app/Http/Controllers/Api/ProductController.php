<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()
            ->with(['brand', 'subCategory.mainCategory'])
            ->when($request->query('brand_id'), fn ($query, $id) => $query->where('brand_id', $id))
            ->when($request->query('sub_product_category_id'), fn ($query, $id) => $query->where('sub_product_category_id', $id))
            ->when($request->query('main_product_category_id'), function ($query, $id) {
                $query->whereHas('subCategory', fn ($query) => $query->where('main_product_category_id', $id));
            })
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->when(trim((string) $request->query('q', '')) !== '', function ($query) use ($request) {
                $search = trim((string) $request->query('q'));

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhereHas('brand', fn ($query) => $query->where('name', 'like', '%'.$search.'%'));
                });
            })
            ->orderBy('name')
            ->get();

        return ProductResource::collection($products);
    }

    public function show(Product $product): ProductResource
    {
        $product->load(['brand', 'subCategory.mainCategory']);

        return new ProductResource($product);
    }
}
