<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_guest_cannot_access_roles_page(): void
    {
        $this->get(route('admin.roles.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_manage_roles(): void
    {
        $admin = $this->createSuperAdmin();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.roles.index'))
            ->assertOk()
            ->assertSee('مدير النظام');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.roles.store'), [
                'name' => 'مشرف مبيعات',
                'permissions' => Permission::query()->where('slug', 'orders.view')->pluck('id')->all(),
            ])
            ->assertRedirect(route('admin.roles.index'));

        $this->assertDatabaseHas('roles', [
            'name' => 'مشرف مبيعات',
        ]);
    }

    public function test_admin_can_assign_permissions_to_role(): void
    {
        $admin = $this->createSuperAdmin();
        $role = Role::query()->where('slug', 'orders_manager')->firstOrFail();
        $permissionIds = Permission::query()->whereIn('slug', ['orders.view', 'orders.manage'])->pluck('id');

        $this->actingAs($admin, 'admin')
            ->put(route('admin.roles.update', $role), [
                'name' => $role->name,
                'permissions' => $permissionIds->all(),
            ])
            ->assertRedirect(route('admin.roles.index'));

        $this->assertCount(2, $role->fresh()->permissions);
    }

    public function test_admin_can_create_supervisor_account(): void
    {
        $admin = $this->createSuperAdmin();
        $role = Role::query()->where('slug', 'orders_manager')->firstOrFail();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.admins.store'), [
                'name' => 'مشرف تجريبي',
                'email' => 'supervisor@example.com',
                'phone' => '01099998888',
                'role_id' => $role->id,
                'password' => 'password123',
            ])
            ->assertRedirect(route('admin.admins.create'));

        $this->assertDatabaseHas('admins', [
            'email' => 'supervisor@example.com',
            'role_id' => $role->id,
            'role' => 'orders_manager',
        ]);
    }

    private function createSuperAdmin(): Admin
    {
        $role = Role::query()->where('slug', 'superadmin')->firstOrFail();

        return Admin::query()->create([
            'name' => 'Ayoub',
            'email' => 'ayoub@gmail.com',
            'password' => 'admin123',
            'role' => 'superadmin',
            'role_id' => $role->id,
        ]);
    }
}
