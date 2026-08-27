<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Models\Client;
use App\Models\Complaint;
use App\Services\ComplaintService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ComplaintController extends Controller
{
    public function __construct(private readonly ComplaintService $complaintService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $complaints = Complaint::query()
            ->with('order')
            ->when($request->query('client_id'), fn ($query, $id) => $query->where('client_id', $id))
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->get();

        return ComplaintResource::collection($complaints);
    }

    public function store(StoreComplaintRequest $request): JsonResponse
    {
        $client = Client::query()->findOrFail($request->validated('client_id'));

        $complaint = $this->complaintService->create(
            $client,
            $request->validated('subject'),
            $request->validated('message'),
            $request->validated('order_id'),
        );

        return (new ComplaintResource($complaint->load('order')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Complaint $complaint): ComplaintResource
    {
        $complaint->load(['order']);

        return new ComplaintResource($complaint);
    }
}
