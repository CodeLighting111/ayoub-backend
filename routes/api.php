<?php

use App\Http\Controllers\Api\ClientCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => config('app.name'),
        'time' => now()->toIso8601String(),
    ]);
});

Route::get('/client-categories', [ClientCategoryController::class, 'index']);
Route::get('/client-categories/{client_category}', [ClientCategoryController::class, 'show']);
