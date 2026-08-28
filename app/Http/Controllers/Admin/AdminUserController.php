<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $admins = Admin::query()
            ->with('roleModel')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('name')
            ->get();

        return view('dashboard.admins.index', [
            'admins' => $admins,
            'search' => $search,
            'activeMenu' => 'admins',
        ]);
    }

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
            ->route('admin.admins.index')
            ->with('success', 'تم إضافة الحساب بنجاح.');
    }

    public function show(Admin $admin): View
    {
        $admin->load('roleModel');

        return view('dashboard.admins.show', [
            'activeMenu' => 'admins',
            'admin' => $admin,
        ]);
    }

    public function updateStatus(Request $request, Admin $admin): RedirectResponse
    {
        if (! Admin::hasStatusColumn()) {
            return redirect()
                ->route('admin.admins.index')
                ->with('success', 'ميزة الحالة غير متاحة بعد. يرجى تشغيل التحديثات.');
        }

        $request->validate([
            'status' => ['required', 'in:active,suspended'],
        ], [
            'status.required' => 'الحالة مطلوبة.',
            'status.in' => 'الحالة غير صالحة.',
        ]);

        if ($admin->isPrimarySuperAdmin()) {
            return redirect()
                ->route('admin.admins.index')
                ->with('success', 'لا يمكن تغيير حالة مدير النظام الأساسي.');
        }

        if ($admin->id === auth('admin')->id() && $request->input('status') === 'suspended') {
            return redirect()
                ->route('admin.admins.index')
                ->with('success', 'لا يمكنك تعطيل حسابك الحالي.');
        }

        if (
            $request->input('status') === 'suspended'
            && ($admin->role === 'superadmin' || $admin->roleModel?->slug === 'superadmin')
        ) {
            $activeSuperadminCount = Admin::query()
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->where('role', 'superadmin')
                        ->orWhereHas('roleModel', fn ($query) => $query->where('slug', 'superadmin'));
                })
                ->count();

            if ($activeSuperadminCount <= 1) {
                return redirect()
                    ->route('admin.admins.index')
                    ->with('success', 'لا يمكن تعطيل آخر مدير نظام نشط.');
            }
        }

        $admin->update(['status' => $request->input('status')]);

        $message = $admin->status === 'active'
            ? 'تم تفعيل الحساب بنجاح.'
            : 'تم تعطيل الحساب بنجاح.';

        return redirect()
            ->route('admin.admins.index')
            ->with('success', $message);
    }

    public function destroy(Admin $admin): RedirectResponse
    {
        if ($admin->isPrimarySuperAdmin()) {
            return redirect()
                ->route('admin.admins.index')
                ->with('success', 'لا يمكن حذف مدير النظام الأساسي.');
        }

        if ($admin->id === auth('admin')->id()) {
            return redirect()
                ->route('admin.admins.index')
                ->with('success', 'لا يمكنك حذف حسابك الحالي.');
        }

        if ($admin->role === 'superadmin' || $admin->roleModel?->slug === 'superadmin') {
            $superadminCount = Admin::query()
                ->where('role', 'superadmin')
                ->orWhereHas('roleModel', fn ($query) => $query->where('slug', 'superadmin'))
                ->count();

            if ($superadminCount <= 1) {
                return redirect()
                    ->route('admin.admins.index')
                    ->with('success', 'لا يمكن حذف آخر مدير نظام.');
            }
        }

        $admin->delete();

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'تم حذف الحساب بنجاح.');
    }
}
