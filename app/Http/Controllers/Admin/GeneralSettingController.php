<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GeneralSettingRequest;
use App\Models\GeneralSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class GeneralSettingController extends Controller
{
    public function edit(): View
    {
        return view('dashboard.settings.edit', [
            'settings' => GeneralSetting::current(),
            'activeMenu' => 'settings',
        ]);
    }

    public function update(GeneralSettingRequest $request): RedirectResponse
    {
        $settings = GeneralSetting::current();
        $data = $request->safe()->except('logo');

        if ($request->hasFile('logo')) {
            $this->deleteLogo($settings->logo_url);
            $data['logo_url'] = $this->storeLogo($request->file('logo'));
        }

        $settings->update($data);

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'تم تحديث الإعدادات العامة بنجاح.');
    }

    private function storeLogo(UploadedFile $file): string
    {
        $directory = public_path('images/settings');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = uniqid('logo_', true).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return '/images/settings/'.$filename;
    }

    private function deleteLogo(?string $logoUrl): void
    {
        if (! $logoUrl || ! str_starts_with($logoUrl, '/images/settings/')) {
            return;
        }

        $path = public_path(ltrim($logoUrl, '/'));

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
