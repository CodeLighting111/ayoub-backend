<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $roles = Role::query()
            ->withCount(['admins', 'permissions'])
            ->when($search !== '', fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
            ->orderBy('name')
            ->get();

        return view('dashboard.roles.index', [
            'roles' => $roles,
            'search' => $search,
            'activeMenu' => 'roles',
        ]);
    }

    public function create(): View
    {
        return view('dashboard.roles.create', $this->formData(new Role));
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $role = Role::query()->create([
            'name' => $request->validated('name'),
            'slug' => $this->generateSlug($request->validated('name')),
        ]);

        $role->permissions()->sync($request->validated('permissions') ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'تم إضافة الدور بنجاح.');
    }

    public function edit(Role $role): View
    {
        return view('dashboard.roles.edit', $this->formData($role));
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $role->update(['name' => $request->validated('name')]);

        if ($role->slug !== 'superadmin') {
            $role->permissions()->sync($request->validated('permissions') ?? []);
        }

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'تم تحديث الدور بنجاح.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->slug === 'superadmin') {
            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'لا يمكن حذف دور مدير النظام.');
        }

        if ($role->admins()->exists()) {
            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'لا يمكن حذف دور مرتبط بمشرفين.');
        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'تم حذف الدور بنجاح.');
    }

    /** @return array<string, mixed> */
    private function formData(Role $role): array
    {
        $assignedPermissionIds = old(
            'permissions',
            $role->exists ? $role->permissions()->pluck('permissions.id')->all() : [],
        );

        return [
            'activeMenu' => 'roles',
            'role' => $role,
            'permissions' => Permission::query()->orderBy('group')->orderBy('name')->get()->groupBy('group'),
            'assignedPermissionIds' => array_map('intval', (array) $assignedPermissionIds),
        ];
    }

    private function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'role';
        $slug = $base;
        $counter = 1;

        while (
            Role::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
