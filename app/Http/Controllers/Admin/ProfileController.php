<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('dashboard.profile.edit', [
            'admin' => auth('admin')->user(),
        ]);
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $admin = auth('admin')->user();
        $data = $request->safe()->except(['avatar', 'current_password', 'password', 'password_confirmation']);

        if ($request->hasFile('avatar')) {
            $this->deleteAvatar($admin->avatar_url);
            $data['avatar_url'] = $this->storeAvatar($request->file('avatar'));
        }

        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        }

        $admin->update($data);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }

    private function storeAvatar(UploadedFile $file): string
    {
        $directory = public_path('images/admins');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = uniqid('admin_', true).'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return '/images/admins/'.$filename;
    }

    private function deleteAvatar(?string $avatarUrl): void
    {
        if (! $avatarUrl || ! str_starts_with($avatarUrl, '/images/admins/')) {
            return;
        }

        $path = public_path(ltrim($avatarUrl, '/'));

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
