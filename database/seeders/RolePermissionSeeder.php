<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /** @var array<string, list<array{name: string, slug: string}>> */
    private array $permissionGroups = [
        'الصفحات الابتدائية' => [
            ['name' => 'عرض الصفحات الابتدائية', 'slug' => 'onboarding.view'],
            ['name' => 'إدارة الصفحات الابتدائية', 'slug' => 'onboarding.manage'],
        ],
        'العملاء والمواقع' => [
            ['name' => 'عرض العملاء', 'slug' => 'clients.view'],
            ['name' => 'إدارة العملاء', 'slug' => 'clients.manage'],
            ['name' => 'إدارة المواقع', 'slug' => 'locations.manage'],
        ],
        'المنتجات' => [
            ['name' => 'عرض المنتجات', 'slug' => 'products.view'],
            ['name' => 'إدارة المنتجات', 'slug' => 'products.manage'],
        ],
        'الطلبات' => [
            ['name' => 'عرض الطلبات', 'slug' => 'orders.view'],
            ['name' => 'إدارة الطلبات', 'slug' => 'orders.manage'],
        ],
        'الشكاوى والإشعارات' => [
            ['name' => 'عرض الشكاوى', 'slug' => 'complaints.view'],
            ['name' => 'إدارة الشكاوى', 'slug' => 'complaints.manage'],
            ['name' => 'عرض الإشعارات', 'slug' => 'notifications.view'],
        ],
        'الإعدادات' => [
            ['name' => 'إدارة صفحة عنا', 'slug' => 'about.manage'],
            ['name' => 'إدارة الإعدادات العامة', 'slug' => 'settings.manage'],
        ],
        'إدارة النظام' => [
            ['name' => 'إدارة الأدوار', 'slug' => 'roles.manage'],
            ['name' => 'إدارة الأذونات', 'slug' => 'permissions.manage'],
            ['name' => 'إدارة المشرفين', 'slug' => 'admins.manage'],
        ],
    ];

    public function run(): void
    {
        $permissions = collect();

        foreach ($this->permissionGroups as $group => $items) {
            foreach ($items as $item) {
                $permissions->push(
                    Permission::query()->updateOrCreate(
                        ['slug' => $item['slug']],
                        ['name' => $item['name'], 'group' => $group],
                    ),
                );
            }
        }

        $superadmin = Role::query()->updateOrCreate(
            ['slug' => 'superadmin'],
            ['name' => 'مدير النظام', 'description' => 'صلاحيات كاملة على جميع أقسام لوحة التحكم.'],
        );

        $ordersManager = Role::query()->updateOrCreate(
            ['slug' => 'orders_manager'],
            ['name' => 'مسؤول الطلبات', 'description' => 'متابعة الطلبات والإشعارات فقط.'],
        );

        Role::query()->where('slug', 'admin')->whereDoesntHave('admins')->delete();

        $superadmin->permissions()->sync($permissions->pluck('id'));

        $ordersManager->permissions()->sync(
            $permissions->whereIn('slug', [
                'orders.view',
                'orders.manage',
                'notifications.view',
                'complaints.view',
            ])->pluck('id'),
        );

        Admin::query()->where('email', 'ayoub@gmail.com')->update([
            'role_id' => $superadmin->id,
            'role' => 'superadmin',
        ]);

        $this->command?->info('Roles and permissions seeded.');
    }
}
