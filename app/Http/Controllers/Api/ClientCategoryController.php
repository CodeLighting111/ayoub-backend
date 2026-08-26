<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientCategoryResource;
use App\Models\ClientCategory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientCategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $categories = ClientCategory::query()
            ->orderBy('title')
            ->get();

        return ClientCategoryResource::collection($categories);
    }

    public function show(ClientCategory $client_category): ClientCategoryResource
    {
        return new ClientCategoryResource($client_category);
    }
}
