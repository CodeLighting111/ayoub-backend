<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ClientCategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\MainProductCategoryController;
use App\Http\Controllers\Admin\OnboardingScreenController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubProductCategoryController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/login');

Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'store']);
});

Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('admin.onboarding.index'))->name('admin.dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('admin.logout');

    Route::get('/onboarding', [OnboardingScreenController::class, 'index'])->name('admin.onboarding.index');
    Route::get('/onboarding/create', [OnboardingScreenController::class, 'create'])->name('admin.onboarding.create');
    Route::post('/onboarding', [OnboardingScreenController::class, 'store'])->name('admin.onboarding.store');
    Route::get('/onboarding/{onboarding_screen}', [OnboardingScreenController::class, 'show'])->name('admin.onboarding.show');
    Route::get('/onboarding/{onboarding_screen}/edit', [OnboardingScreenController::class, 'edit'])->name('admin.onboarding.edit');
    Route::put('/onboarding/{onboarding_screen}', [OnboardingScreenController::class, 'update'])->name('admin.onboarding.update');
    Route::delete('/onboarding/{onboarding_screen}', [OnboardingScreenController::class, 'destroy'])->name('admin.onboarding.destroy');
    Route::post('/onboarding/{onboarding_screen}/move-up', [OnboardingScreenController::class, 'moveUp'])->name('admin.onboarding.move-up');
    Route::post('/onboarding/{onboarding_screen}/move-down', [OnboardingScreenController::class, 'moveDown'])->name('admin.onboarding.move-down');

    Route::get('/client-categories', [ClientCategoryController::class, 'index'])->name('admin.client-categories.index');
    Route::get('/client-categories/create', [ClientCategoryController::class, 'create'])->name('admin.client-categories.create');
    Route::post('/client-categories', [ClientCategoryController::class, 'store'])->name('admin.client-categories.store');
    Route::get('/client-categories/{client_category}/edit', [ClientCategoryController::class, 'edit'])->name('admin.client-categories.edit');
    Route::put('/client-categories/{client_category}', [ClientCategoryController::class, 'update'])->name('admin.client-categories.update');
    Route::delete('/client-categories/{client_category}', [ClientCategoryController::class, 'destroy'])->name('admin.client-categories.destroy');

    Route::get('/governorates', [GovernorateController::class, 'index'])->name('admin.governorates.index');
    Route::get('/governorates/create', [GovernorateController::class, 'create'])->name('admin.governorates.create');
    Route::post('/governorates', [GovernorateController::class, 'store'])->name('admin.governorates.store');
    Route::get('/governorates/{governorate}/edit', [GovernorateController::class, 'edit'])->name('admin.governorates.edit');
    Route::put('/governorates/{governorate}', [GovernorateController::class, 'update'])->name('admin.governorates.update');
    Route::delete('/governorates/{governorate}', [GovernorateController::class, 'destroy'])->name('admin.governorates.destroy');

    Route::get('/cities', [CityController::class, 'index'])->name('admin.cities.index');
    Route::get('/cities/create', [CityController::class, 'create'])->name('admin.cities.create');
    Route::post('/cities', [CityController::class, 'store'])->name('admin.cities.store');
    Route::get('/cities/{city}/edit', [CityController::class, 'edit'])->name('admin.cities.edit');
    Route::put('/cities/{city}', [CityController::class, 'update'])->name('admin.cities.update');
    Route::delete('/cities/{city}', [CityController::class, 'destroy'])->name('admin.cities.destroy');

    Route::get('/areas', [AreaController::class, 'index'])->name('admin.areas.index');
    Route::get('/areas/create', [AreaController::class, 'create'])->name('admin.areas.create');
    Route::post('/areas', [AreaController::class, 'store'])->name('admin.areas.store');
    Route::get('/areas/{area}/edit', [AreaController::class, 'edit'])->name('admin.areas.edit');
    Route::put('/areas/{area}', [AreaController::class, 'update'])->name('admin.areas.update');
    Route::delete('/areas/{area}', [AreaController::class, 'destroy'])->name('admin.areas.destroy');

    Route::get('/clients', [ClientController::class, 'index'])->name('admin.clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('admin.clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('admin.clients.store');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('admin.clients.show');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('admin.clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('admin.clients.update');
    Route::patch('/clients/{client}/status', [ClientController::class, 'updateStatus'])->name('admin.clients.update-status');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('admin.clients.destroy');

    Route::get('/main-product-categories', [MainProductCategoryController::class, 'index'])->name('admin.main-product-categories.index');
    Route::get('/main-product-categories/create', [MainProductCategoryController::class, 'create'])->name('admin.main-product-categories.create');
    Route::post('/main-product-categories', [MainProductCategoryController::class, 'store'])->name('admin.main-product-categories.store');
    Route::get('/main-product-categories/{main_product_category}/edit', [MainProductCategoryController::class, 'edit'])->name('admin.main-product-categories.edit');
    Route::put('/main-product-categories/{main_product_category}', [MainProductCategoryController::class, 'update'])->name('admin.main-product-categories.update');
    Route::delete('/main-product-categories/{main_product_category}', [MainProductCategoryController::class, 'destroy'])->name('admin.main-product-categories.destroy');

    Route::get('/brands', [BrandController::class, 'index'])->name('admin.brands.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('admin.brands.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('admin.brands.store');
    Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('admin.brands.edit');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('admin.brands.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');

    Route::get('/sub-product-categories', [SubProductCategoryController::class, 'index'])->name('admin.sub-product-categories.index');
    Route::get('/sub-product-categories/create', [SubProductCategoryController::class, 'create'])->name('admin.sub-product-categories.create');
    Route::post('/sub-product-categories', [SubProductCategoryController::class, 'store'])->name('admin.sub-product-categories.store');
    Route::get('/sub-product-categories/{sub_product_category}/edit', [SubProductCategoryController::class, 'edit'])->name('admin.sub-product-categories.edit');
    Route::put('/sub-product-categories/{sub_product_category}', [SubProductCategoryController::class, 'update'])->name('admin.sub-product-categories.update');
    Route::delete('/sub-product-categories/{sub_product_category}', [SubProductCategoryController::class, 'destroy'])->name('admin.sub-product-categories.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');

    Route::get('/complaints', [ComplaintController::class, 'index'])->name('admin.complaints.index');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('admin.complaints.show');
    Route::patch('/complaints/{complaint}/status', [ComplaintController::class, 'updateStatus'])->name('admin.complaints.update-status');

    Route::get('/about', [AboutController::class, 'edit'])->name('admin.about.edit');
    Route::put('/about', [AboutController::class, 'update'])->name('admin.about.update');

    Route::get('/settings', [GeneralSettingController::class, 'edit'])->name('admin.settings.edit');
    Route::put('/settings', [GeneralSettingController::class, 'update'])->name('admin.settings.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('admin.notifications.show');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('admin.notifications.mark-all-read');

    Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');

    Route::get('/admins/create', [AdminUserController::class, 'create'])->name('admin.admins.create');
    Route::post('/admins', [AdminUserController::class, 'store'])->name('admin.admins.store');
});
