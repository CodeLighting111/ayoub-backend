<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $clients = Client::query()
            ->with(['category', 'governorate', 'city', 'area'])
            ->when($request->query('client_category_id'), fn ($query, $id) => $query->where('client_category_id', $id))
            ->when($request->query('city_id'), fn ($query, $id) => $query->where('city_id', $id))
            ->when($request->query('area_id'), fn ($query, $id) => $query->where('area_id', $id))
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderBy('name')
            ->get();

        return ClientResource::collection($clients);
    }

    public function show(Client $client): ClientResource
    {
        $client->load(['category', 'governorate', 'city', 'area']);

        return new ClientResource($client);
    }
}
