<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function create(): View
    {
        return view('dashboard.admins.create', [
            'activeMenu' => 'admins',
            'admin' => new Admin,
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function store(AdminUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $role = Role::query()->findOrFail($data['role_id']);

        Admin::query()->create([
            ...$data,
            'role' => $role->slug,
        ]);

        return redirect()
            ->route('admin.admins.create')
            ->with('success', 'تم إضافة المشرف بنجاح.');
    }
}
