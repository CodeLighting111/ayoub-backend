<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientCategoryRequest;
use App\Models\ClientCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $categories = ClientCategory::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
            })
            ->latest()
            ->get();

        return view('dashboard.client-categories.index', [
            'categories' => $categories,
            'search' => $search,
            'activeMenu' => 'customer-categories',
        ]);
    }

    public function create(): View
    {
        return view('dashboard.client-categories.create', [
            'activeMenu' => 'customer-categories',
            'category' => new ClientCategory,
        ]);
    }

    public function store(ClientCategoryRequest $request): RedirectResponse
    {
        ClientCategory::query()->create($request->validated());

        return redirect()
            ->route('admin.client-categories.index')
            ->with('success', 'تم إضافة فئة العملاء بنجاح.');
    }

    public function edit(ClientCategory $client_category): View
    {
        return view('dashboard.client-categories.edit', [
            'activeMenu' => 'customer-categories',
            'category' => $client_category,
        ]);
    }

    public function update(ClientCategoryRequest $request, ClientCategory $client_category): RedirectResponse
    {
        $client_category->update($request->validated());

        return redirect()
            ->route('admin.client-categories.index')
            ->with('success', 'تم تحديث فئة العملاء بنجاح.');
    }

    public function destroy(ClientCategory $client_category): RedirectResponse
    {
        $client_category->delete();

        return redirect()
            ->route('admin.client-categories.index')
            ->with('success', 'تم حذف فئة العملاء بنجاح.');
    }
}
