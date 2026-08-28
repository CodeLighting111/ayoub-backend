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
            ['name' => 'إضافة صفحة ابتدائية', 'slug' => 'onboarding.create'],
            ['name' => 'تعديل الصفحات الابتدائية', 'slug' => 'onboarding.edit'],
            ['name' => 'حذف الصفحات الابتدائية', 'slug' => 'onboarding.delete'],
        ],
        'فئات العملاء' => [
            ['name' => 'عرض فئات العملاء', 'slug' => 'client_categories.view'],
            ['name' => 'إضافة فئة عملاء', 'slug' => 'client_categories.create'],
            ['name' => 'تعديل فئات العملاء', 'slug' => 'client_categories.edit'],
            ['name' => 'حذف فئات العملاء', 'slug' => 'client_categories.delete'],
        ],
        'المحافظات' => [
            ['name' => 'عرض المحافظات', 'slug' => 'governorates.view'],
            ['name' => 'إضافة محافظة', 'slug' => 'governorates.create'],
            ['name' => 'تعديل المحافظات', 'slug' => 'governorates.edit'],
            ['name' => 'حذف المحافظات', 'slug' => 'governorates.delete'],
        ],
        'المدن' => [
            ['name' => 'عرض المدن', 'slug' => 'cities.view'],
            ['name' => 'إضافة مدينة', 'slug' => 'cities.create'],
            ['name' => 'تعديل المدن', 'slug' => 'cities.edit'],
            ['name' => 'حذف المدن', 'slug' => 'cities.delete'],
        ],
        'المناطق' => [
            ['name' => 'عرض المناطق', 'slug' => 'areas.view'],
            ['name' => 'إضافة منطقة', 'slug' => 'areas.create'],
            ['name' => 'تعديل المناطق', 'slug' => 'areas.edit'],
            ['name' => 'حذف المناطق', 'slug' => 'areas.delete'],
        ],
        'العملاء' => [
            ['name' => 'عرض العملاء', 'slug' => 'clients.view'],
            ['name' => 'إضافة عميل', 'slug' => 'clients.create'],
            ['name' => 'تعديل العملاء', 'slug' => 'clients.edit'],
            ['name' => 'حذف العملاء', 'slug' => 'clients.delete'],
        ],
        'العلامات التجارية' => [
            ['name' => 'عرض العلامات التجارية', 'slug' => 'brands.view'],
            ['name' => 'إضافة علامة تجارية', 'slug' => 'brands.create'],
            ['name' => 'تعديل العلامات التجارية', 'slug' => 'brands.edit'],
            ['name' => 'حذف العلامات التجارية', 'slug' => 'brands.delete'],
        ],
        'فئات المنتجات الرئيسية' => [
            ['name' => 'عرض فئات المنتجات الرئيسية', 'slug' => 'main_product_categories.view'],
            ['name' => 'إضافة فئة منتجات رئيسية', 'slug' => 'main_product_categories.create'],
            ['name' => 'تعديل فئات المنتجات الرئيسية', 'slug' => 'main_product_categories.edit'],
            ['name' => 'حذف فئات المنتجات الرئيسية', 'slug' => 'main_product_categories.delete'],
        ],
        'فئات المنتجات الفرعية' => [
            ['name' => 'عرض فئات المنتجات الفرعية', 'slug' => 'sub_product_categories.view'],
            ['name' => 'إضافة فئة منتجات فرعية', 'slug' => 'sub_product_categories.create'],
            ['name' => 'تعديل فئات المنتجات الفرعية', 'slug' => 'sub_product_categories.edit'],
            ['name' => 'حذف فئات المنتجات الفرعية', 'slug' => 'sub_product_categories.delete'],
        ],
        'المنتجات' => [
            ['name' => 'عرض المنتجات', 'slug' => 'products.view'],
            ['name' => 'إضافة منتج', 'slug' => 'products.create'],
            ['name' => 'تعديل المنتجات', 'slug' => 'products.edit'],
            ['name' => 'حذف المنتجات', 'slug' => 'products.delete'],
        ],
        'الطلبات' => [
            ['name' => 'عرض الطلبات', 'slug' => 'orders.view'],
            ['name' => 'إدارة الطلبات', 'slug' => 'orders.manage'],
        ],
        'الإحصائيات' => [
            ['name' => 'عرض الإحصائيات', 'slug' => 'statistics.view'],
        ],
        'الإشعارات' => [
            ['name' => 'عرض الإشعارات', 'slug' => 'notifications.view'],
            ['name' => 'إرسال إشعار', 'slug' => 'notifications.send'],
        ],
        'الشكاوى' => [
            ['name' => 'عرض الشكاوى', 'slug' => 'complaints.view'],
            ['name' => 'إدارة الشكاوى', 'slug' => 'complaints.manage'],
        ],
        'عنا' => [
            ['name' => 'عرض صفحة عنا', 'slug' => 'about.view'],
            ['name' => 'تعديل صفحة عنا', 'slug' => 'about.manage'],
        ],
        'السوشيال ميديا' => [
            ['name' => 'عرض حسابات السوشيال ميديا', 'slug' => 'social_media_accounts.view'],
            ['name' => 'إضافة حساب سوشيال ميديا', 'slug' => 'social_media_accounts.create'],
            ['name' => 'تعديل حسابات السوشيال ميديا', 'slug' => 'social_media_accounts.edit'],
            ['name' => 'حذف حسابات السوشيال ميديا', 'slug' => 'social_media_accounts.delete'],
        ],
        'الإعدادات العامة' => [
            ['name' => 'عرض الإعدادات العامة', 'slug' => 'settings.view'],
            ['name' => 'تعديل الإعدادات العامة', 'slug' => 'settings.manage'],
        ],
        'الأدوار' => [
            ['name' => 'عرض الأدوار', 'slug' => 'roles.view'],
            ['name' => 'إضافة دور', 'slug' => 'roles.create'],
            ['name' => 'تعديل الأدوار', 'slug' => 'roles.edit'],
            ['name' => 'حذف الأدوار', 'slug' => 'roles.delete'],
        ],
        'المشرفين' => [
            ['name' => 'عرض المشرفين', 'slug' => 'admins.view'],
            ['name' => 'إضافة مشرف', 'slug' => 'admins.create'],
            ['name' => 'إدارة المشرفين', 'slug' => 'admins.manage'],
            ['name' => 'حذف المشرفين', 'slug' => 'admins.delete'],
        ],
    ];

    public function run(): void
    {
        $permissions = collect();
        $validSlugs = collect($this->permissionGroups)
            ->flatten(1)
            ->pluck('slug')
            ->all();

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

        Permission::query()
            ->whereNotIn('slug', $validSlugs)
            ->each(function (Permission $permission): void {
                $permission->roles()->detach();
                $permission->delete();
            });

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
