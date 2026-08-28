<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SocialMediaAccountRequest;
use App\Models\SocialMediaAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SocialMediaAccountController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $accounts = SocialMediaAccount::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('url', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('dashboard.social-media-accounts.index', [
            'accounts' => $accounts,
            'search' => $search,
            'activeMenu' => 'social-media-accounts',
        ]);
    }

    public function create(): View
    {
        $nextSortOrder = (int) SocialMediaAccount::query()->max('sort_order') + 1;

        return view('dashboard.social-media-accounts.create', [
            'activeMenu' => 'social-media-accounts',
            'account' => new SocialMediaAccount(['sort_order' => max(1, $nextSortOrder)]),
        ]);
    }

    public function store(SocialMediaAccountRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $imageUrl = $this->storeImage($request->file('image'));

        try {
            SocialMediaAccount::query()->create(array_merge($data, [
                'image_url' => $imageUrl,
                'sort_order' => $data['sort_order'] ?? 1,
            ]));
        } catch (\Throwable $exception) {
            $this->deleteImage($imageUrl);

            throw $exception;
        }

        return redirect()
            ->route('admin.social-media-accounts.index')
            ->with('success', 'تم إضافة حساب السوشيال ميديا بنجاح.');
    }

    public function edit(SocialMediaAccount $socialMediaAccount): View
    {
        return view('dashboard.social-media-accounts.edit', [
            'activeMenu' => 'social-media-accounts',
            'account' => $socialMediaAccount,
        ]);
    }

    public function update(SocialMediaAccountRequest $request, SocialMediaAccount $socialMediaAccount): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $this->deleteImage($socialMediaAccount->image_url);
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        $socialMediaAccount->update($data);

        return redirect()
            ->route('admin.social-media-accounts.index')
            ->with('success', 'تم تحديث حساب السوشيال ميديا بنجاح.');
    }

    public function destroy(SocialMediaAccount $socialMediaAccount): RedirectResponse
    {
        $this->deleteImage($socialMediaAccount->image_url);
        $socialMediaAccount->delete();

        return redirect()
            ->route('admin.social-media-accounts.index')
            ->with('success', 'تم حذف حساب السوشيال ميديا بنجاح.');
    }

    private function storeImage(UploadedFile $file): string
    {
        $directory = public_path('images/social-media-accounts');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = uniqid('social_', true).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return '/images/social-media-accounts/'.$filename;
    }

    private function deleteImage(?string $imageUrl): void
    {
        if (! $imageUrl || ! str_starts_with($imageUrl, '/images/social-media-accounts/')) {
            return;
        }

        $path = public_path(ltrim($imageUrl, '/'));

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
