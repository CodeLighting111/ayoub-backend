<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MainProductCategoryRequest;
use App\Models\MainProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class MainProductCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $categories = MainProductCategory::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
            })
            ->orderBy('title')
            ->get();

        return view('dashboard.main-product-categories.index', [
            'categories' => $categories,
            'search' => $search,
            'activeMenu' => 'main-categories',
        ]);
    }

    public function create(): View
    {
        return view('dashboard.main-product-categories.create', [
            'activeMenu' => 'main-categories',
            'category' => new MainProductCategory,
        ]);
    }

    public function store(MainProductCategoryRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $imageUrl = $this->storeImage($request->file('image'));

        try {
            MainProductCategory::query()->create(array_merge($data, ['image_url' => $imageUrl]));
        } catch (\Throwable $exception) {
            $this->deleteImage($imageUrl);

            throw $exception;
        }

        return redirect()
            ->route('admin.main-product-categories.index')
            ->with('success', 'تم إضافة فئة المنتجات الرئيسية بنجاح.');
    }

    public function edit(MainProductCategory $main_product_category): View
    {
        return view('dashboard.main-product-categories.edit', [
            'activeMenu' => 'main-categories',
            'category' => $main_product_category,
        ]);
    }

    public function update(MainProductCategoryRequest $request, MainProductCategory $main_product_category): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $this->deleteImage($main_product_category->image_url);
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        $main_product_category->update($data);

        return redirect()
            ->route('admin.main-product-categories.index')
            ->with('success', 'تم تحديث فئة المنتجات الرئيسية بنجاح.');
    }

    public function destroy(MainProductCategory $main_product_category): RedirectResponse
    {
        $this->deleteImage($main_product_category->image_url);
        $main_product_category->delete();

        return redirect()
            ->route('admin.main-product-categories.index')
            ->with('success', 'تم حذف فئة المنتجات الرئيسية بنجاح.');
    }

    private function storeImage(UploadedFile $file): string
    {
        $directory = public_path('images/main-product-categories');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = uniqid('main_category_', true).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return '/images/main-product-categories/'.$filename;
    }

    private function deleteImage(?string $imageUrl): void
    {
        if (! $imageUrl || ! str_starts_with($imageUrl, '/images/main-product-categories/')) {
            return;
        }

        $path = public_path(ltrim($imageUrl, '/'));

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
