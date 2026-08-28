<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Brand;
use App\Models\MainProductCategory;
use App\Models\Product;
use App\Models\SubProductCategory;
use App\Services\AdminNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly AdminNotificationService $notificationService) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $products = Product::query()
            ->with(['brand', 'subCategory.mainCategory'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhereHas('brand', fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
                        ->orWhereHas('subCategory', fn ($query) => $query->where('title', 'like', '%'.$search.'%'))
                        ->orWhereHas('subCategory.mainCategory', fn ($query) => $query->where('title', 'like', '%'.$search.'%'));
                });
            })
            ->when(in_array($status, ['active', 'inactive'], true), fn ($query) => $query->where('status', $status))
            ->orderBy('name')
            ->get();

        return view('dashboard.products.index', [
            'products' => $products,
            'search' => $search,
            'status' => $status,
            'activeMenu' => 'products',
        ]);
    }

    public function create(): View
    {
        return view('dashboard.products.create', array_merge(
            $this->formOptions(),
            [
                'activeMenu' => 'products',
                'product' => new Product(['status' => 'active', 'pieces' => 1, 'stock' => 0]),
            ]
        ));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'main_product_category_id']);
        $imageUrl = $this->storeImage($request->file('image'));

        try {
            $product = Product::query()->create(array_merge($data, ['image_url' => $imageUrl]));
        } catch (\Throwable $exception) {
            $this->deleteImage($imageUrl);

            throw $exception;
        }

        $this->notificationService->notifyLowStockIfNeeded($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'تم إضافة المنتج بنجاح.');
    }

    public function edit(Product $product): View
    {
        $product->load('subCategory');

        return view('dashboard.products.edit', array_merge(
            $this->formOptions(),
            [
                'activeMenu' => 'products',
                'product' => $product,
            ]
        ));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'main_product_category_id']);

        if ($request->hasFile('image')) {
            $this->deleteImage($product->image_url);
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        $previousStock = $product->stock;
        $product->update($data);
        $this->notificationService->maybeNotifyLowStock($product->fresh(), $previousStock);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteImage($product->image_url);
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'تم حذف المنتج بنجاح.');
    }

    private function formOptions(): array
    {
        return [
            'brands' => Brand::query()->orderBy('name')->get(),
            'mainCategories' => MainProductCategory::query()->orderBy('title')->get(),
            'subCategories' => SubProductCategory::query()->orderBy('title')->get(),
        ];
    }

    private function storeImage(UploadedFile $file): string
    {
        $directory = public_path('images/products');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = uniqid('product_', true).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return '/images/products/'.$filename;
    }

    private function deleteImage(?string $imageUrl): void
    {
        if (! $imageUrl || ! str_starts_with($imageUrl, '/images/products/')) {
            return;
        }

        $path = public_path(ltrim($imageUrl, '/'));

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
