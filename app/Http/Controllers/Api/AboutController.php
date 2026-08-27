<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Models\About;
use Illuminate\Http\JsonResponse;

class AboutController extends Controller
{
    public function show(): JsonResponse|AboutResource
    {
        $about = About::query()->first();

        if ($about === null) {
            return response()->json([
                'message' => 'About page content not found.',
            ], 404);
        }

        return new AboutResource($about);
    }
}
