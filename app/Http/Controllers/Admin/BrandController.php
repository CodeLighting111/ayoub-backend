<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $brands = Brand::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->orderBy('name')
            ->get();

        return view('dashboard.brands.index', [
            'brands' => $brands,
            'search' => $search,
            'activeMenu' => 'brands',
        ]);
    }

    public function create(): View
    {
        return view('dashboard.brands.create', [
            'activeMenu' => 'brands',
            'brand' => new Brand,
        ]);
    }

    public function store(BrandRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $imageUrl = $this->storeImage($request->file('image'));

        try {
            Brand::query()->create(array_merge($data, ['image_url' => $imageUrl]));
        } catch (\Throwable $exception) {
            $this->deleteImage($imageUrl);

            throw $exception;
        }

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'تم إضافة العلامة التجارية بنجاح.');
    }

    public function edit(Brand $brand): View
    {
        return view('dashboard.brands.edit', [
            'activeMenu' => 'brands',
            'brand' => $brand,
        ]);
    }

    public function update(BrandRequest $request, Brand $brand): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $this->deleteImage($brand->image_url);
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        $brand->update($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'تم تحديث العلامة التجارية بنجاح.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $this->deleteImage($brand->image_url);
        $brand->delete();

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'تم حذف العلامة التجارية بنجاح.');
    }

    private function storeImage(UploadedFile $file): string
    {
        $directory = public_path('images/brands');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = uniqid('brand_', true).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return '/images/brands/'.$filename;
    }

    private function deleteImage(?string $imageUrl): void
    {
        if (! $imageUrl || ! str_starts_with($imageUrl, '/images/brands/')) {
            return;
        }

        $path = public_path(ltrim($imageUrl, '/'));

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
