<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutRequest;
use App\Models\About;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function edit(): View
    {
        return view('dashboard.about.edit', [
            'about' => About::current(),
            'activeMenu' => 'about',
        ]);
    }

    public function update(AboutRequest $request): RedirectResponse
    {
        $about = About::current();
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $this->deleteImage($about->image_url);
            $data['image_url'] = $this->storeImage($request->file('image'));
        }

        $about->update($data);

        return redirect()
            ->route('admin.about.edit')
            ->with('success', 'تم تحديث صفحة عنا بنجاح.');
    }

    private function storeImage(UploadedFile $file): string
    {
        $directory = public_path('images/about');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = uniqid('about_', true).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return '/images/about/'.$filename;
    }

    private function deleteImage(?string $imageUrl): void
    {
        if (! $imageUrl || ! str_starts_with($imageUrl, '/images/about/')) {
            return;
        }

        $path = public_path(ltrim($imageUrl, '/'));

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
