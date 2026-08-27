<?php

use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ClientCategoryController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\GovernorateController;
use App\Http\Controllers\Api\MainProductCategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SubProductCategoryController;
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

Route::get('/governorates', [GovernorateController::class, 'index']);
Route::get('/governorates/{governorate}', [GovernorateController::class, 'show']);

Route::get('/cities', [CityController::class, 'index']);
Route::get('/cities/{city}', [CityController::class, 'show']);

Route::get('/areas', [AreaController::class, 'index']);
Route::get('/areas/{area}', [AreaController::class, 'show']);

Route::get('/clients', [ClientController::class, 'index']);
Route::get('/clients/{client}', [ClientController::class, 'show']);

Route::get('/main-product-categories', [MainProductCategoryController::class, 'index']);
Route::get('/main-product-categories/{main_product_category}', [MainProductCategoryController::class, 'show']);

Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{brand}', [BrandController::class, 'show']);

Route::get('/sub-product-categories', [SubProductCategoryController::class, 'index']);
Route::get('/sub-product-categories/{sub_product_category}', [SubProductCategoryController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{order}', [OrderController::class, 'show']);
Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);

Route::get('/complaints', [ComplaintController::class, 'index']);
Route::post('/complaints', [ComplaintController::class, 'store']);
Route::get('/complaints/{complaint}', [ComplaintController::class, 'show']);

Route::get('/about', [AboutController::class, 'show']);
