<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubProductCategoryRequest;
use App\Models\MainProductCategory;
use App\Models\SubProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubProductCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $categories = SubProductCategory::query()
            ->with('mainCategory')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhereHas('mainCategory', fn ($query) => $query->where('title', 'like', '%'.$search.'%'));
                });
            })
            ->orderBy('title')
            ->get();

        return view('dashboard.sub-product-categories.index', [
            'categories' => $categories,
            'search' => $search,
            'activeMenu' => 'sub-categories',
        ]);
    }

    public function create(): View
    {
        return view('dashboard.sub-product-categories.create', [
            'activeMenu' => 'sub-categories',
            'category' => new SubProductCategory,
            'mainCategories' => MainProductCategory::query()->orderBy('title')->get(),
        ]);
    }

    public function store(SubProductCategoryRequest $request): RedirectResponse
    {
        SubProductCategory::query()->create($request->validated());

        return redirect()
            ->route('admin.sub-product-categories.index')
            ->with('success', 'تم إضافة الفئة الفرعية بنجاح.');
    }

    public function edit(SubProductCategory $sub_product_category): View
    {
        return view('dashboard.sub-product-categories.edit', [
            'activeMenu' => 'sub-categories',
            'category' => $sub_product_category,
            'mainCategories' => MainProductCategory::query()->orderBy('title')->get(),
        ]);
    }

    public function update(SubProductCategoryRequest $request, SubProductCategory $sub_product_category): RedirectResponse
    {
        $sub_product_category->update($request->validated());

        return redirect()
            ->route('admin.sub-product-categories.index')
            ->with('success', 'تم تحديث الفئة الفرعية بنجاح.');
    }

    public function destroy(SubProductCategory $sub_product_category): RedirectResponse
    {
        $sub_product_category->delete();

        return redirect()
            ->route('admin.sub-product-categories.index')
            ->with('success', 'تم حذف الفئة الفرعية بنجاح.');
    }
}
