<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OnboardingScreenRequest;
use App\Models\OnboardingScreen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class OnboardingScreenController extends Controller
{
    public function index(): View
    {
        $screens = OnboardingScreen::query()
            ->orderBy('sort_order')
            ->get();

        return view('dashboard.onboarding.index', [
            'screens' => $screens,
            'activeMenu' => 'onboarding',
        ]);
    }

    public function create(): View
    {
        return view('dashboard.onboarding.create', [
            'activeMenu' => 'onboarding',
            'screen' => new OnboardingScreen([
                'sort_order' => (int) OnboardingScreen::query()->max('sort_order') + 1,
                'status' => 'active',
            ]),
            'sortOptions' => $this->sortOptions(),
        ]);
    }

    public function store(OnboardingScreenRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');
        $data['image_url'] = $this->storeImage($request->file('image'));

        $screen = OnboardingScreen::query()->create($data);

        return redirect()
            ->route('admin.onboarding.show', $screen)
            ->with('success', 'تم إضافة شاشة الترحيب بنجاح.');
    }

    public function show(OnboardingScreen $onboarding_screen): View
    {
        return view('dashboard.onboarding.show', [
            'activeMenu' => 'onboarding',
            'screen' => $onboarding_screen,
        ]);
    }

    public function edit(OnboardingScreen $onboarding_screen): View
    {
        return view('dashboard.onboarding.edit', [
            'activeMenu' => 'onboarding',
            'screen' => $onboarding_screen,
            'sortOptions' => $this->sortOptions(),
        ]);
    }

    public function update(OnboardingScreenRequest $request, OnboardingScreen $onboarding_screen): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $this->deleteImage($onboarding_screen->image_url);
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        $onboarding_screen->update($data);

        return redirect()
            ->route('admin.onboarding.show', $onboarding_screen)
            ->with('success', 'تم تحديث شاشة الترحيب بنجاح.');
    }

    public function destroy(OnboardingScreen $onboarding_screen): RedirectResponse
    {
        $this->deleteImage($onboarding_screen->image_url);
        $onboarding_screen->delete();

        return redirect()
            ->route('admin.onboarding.index')
            ->with('success', 'تم حذف شاشة الترحيب بنجاح.');
    }

    public function moveUp(OnboardingScreen $onboarding_screen): RedirectResponse
    {
        $previous = OnboardingScreen::query()
            ->where('sort_order', '<', $onboarding_screen->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($previous) {
            $this->swapSortOrder($onboarding_screen, $previous);
        }

        return redirect()->route('admin.onboarding.index');
    }

    public function moveDown(OnboardingScreen $onboarding_screen): RedirectResponse
    {
        $next = OnboardingScreen::query()
            ->where('sort_order', '>', $onboarding_screen->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($next) {
            $this->swapSortOrder($onboarding_screen, $next);
        }

        return redirect()->route('admin.onboarding.index');
    }

    private function sortOptions(): array
    {
        $count = max((int) OnboardingScreen::query()->count() + 1, 4);

        return range(1, $count);
    }

    private function storeImage(UploadedFile $file): string
    {
        $directory = public_path('images/onboarding');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = uniqid('screen_', true).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return '/images/onboarding/'.$filename;
    }

    private function deleteImage(?string $imageUrl): void
    {
        if (! $imageUrl || ! str_starts_with($imageUrl, '/images/onboarding/')) {
            return;
        }

        $path = public_path(ltrim($imageUrl, '/'));

        if (File::exists($path)) {
            File::delete($path);
        }
    }

    private function swapSortOrder(OnboardingScreen $first, OnboardingScreen $second): void
    {
        $firstOrder = $first->sort_order;
        $first->update(['sort_order' => $second->sort_order]);
        $second->update(['sort_order' => $firstOrder]);
    }
}
