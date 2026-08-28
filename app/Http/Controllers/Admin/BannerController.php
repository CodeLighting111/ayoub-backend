<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $banners = Banner::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('dashboard.banners.index', [
            'banners' => $banners,
            'search' => $search,
            'activeMenu' => 'banners',
        ]);
    }

    public function create(): View
    {
        $nextSortOrder = (int) Banner::query()->max('sort_order') + 1;

        return view('dashboard.banners.create', [
            'activeMenu' => 'banners',
            'banner' => new Banner(['sort_order' => max(1, $nextSortOrder)]),
        ]);
    }

    public function store(BannerRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $imageUrl = $this->storeImage($request->file('image'));

        try {
            Banner::query()->create(array_merge($data, [
                'image_url' => $imageUrl,
                'sort_order' => $data['sort_order'] ?? 1,
            ]));
        } catch (\Throwable $exception) {
            $this->deleteImage($imageUrl);

            throw $exception;
        }

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'تم إضافة البانر بنجاح.');
    }

    public function edit(Banner $banner): View
    {
        return view('dashboard.banners.edit', [
            'activeMenu' => 'banners',
            'banner' => $banner,
        ]);
    }

    public function update(BannerRequest $request, Banner $banner): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $this->deleteImage($banner->image_url);
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        $banner->update($data);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'تم تحديث البانر بنجاح.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->deleteImage($banner->image_url);
        $banner->delete();

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'تم حذف البانر بنجاح.');
    }

    private function storeImage(UploadedFile $file): string
    {
        $directory = public_path('images/banners');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = uniqid('banner_', true).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return '/images/banners/'.$filename;
    }

    private function deleteImage(?string $imageUrl): void
    {
        if (! $imageUrl || ! str_starts_with($imageUrl, '/images/banners/')) {
            return;
        }

        $path = public_path(ltrim($imageUrl, '/'));

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
