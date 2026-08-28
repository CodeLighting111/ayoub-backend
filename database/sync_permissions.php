<?php

/** Standalone permission sync (no Laravel bootstrap). Run: php database/sync_permissions.php */

$pdo = new PDO('mysql:host=127.0.0.1;dbname=Ayoub;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$permissionGroups = [
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

$upsert = $pdo->prepare('
    INSERT INTO permissions (name, slug, `group`, created_at, updated_at)
    VALUES (:name, :slug, :group, NOW(), NOW())
    ON DUPLICATE KEY UPDATE name = VALUES(name), `group` = VALUES(`group`), updated_at = NOW()
');

$permissionIds = [];

foreach ($permissionGroups as $group => $items) {
    foreach ($items as $item) {
        $upsert->execute([
            'name' => $item['name'],
            'slug' => $item['slug'],
            'group' => $group,
        ]);

        $find = $pdo->prepare('SELECT id FROM permissions WHERE slug = ? LIMIT 1');
        $find->execute([$item['slug']]);
        $permissionIds[$item['slug']] = (int) $find->fetchColumn();
    }
}

$validSlugs = array_keys($permissionIds);
$placeholders = implode(',', array_fill(0, count($validSlugs), '?'));

$obsolete = $pdo->prepare("SELECT id FROM permissions WHERE slug NOT IN ($placeholders)");
$obsolete->execute($validSlugs);
$obsoleteIds = $obsolete->fetchAll(PDO::FETCH_COLUMN);

if ($obsoleteIds !== []) {
    $idPlaceholders = implode(',', array_fill(0, count($obsoleteIds), '?'));
    $pdo->prepare("DELETE FROM permission_role WHERE permission_id IN ($idPlaceholders)")->execute($obsoleteIds);
    $pdo->prepare("DELETE FROM permissions WHERE id IN ($idPlaceholders)")->execute($obsoleteIds);
}

$roleStmt = $pdo->prepare('SELECT id FROM roles WHERE slug = ? LIMIT 1');
$roleStmt->execute(['superadmin']);
$superadminId = (int) $roleStmt->fetchColumn();

$roleStmt->execute(['orders_manager']);
$ordersManagerId = (int) $roleStmt->fetchColumn();

$syncRole = function (int $roleId, array $slugs) use ($pdo, $permissionIds): void {
    $pdo->prepare('DELETE FROM permission_role WHERE role_id = ?')->execute([$roleId]);

    $insert = $pdo->prepare('INSERT INTO permission_role (role_id, permission_id) VALUES (?, ?)');

    foreach ($slugs as $slug) {
        if (! isset($permissionIds[$slug])) {
            continue;
        }

        $insert->execute([$roleId, $permissionIds[$slug]]);
    }
};

if ($superadminId > 0) {
    $syncRole($superadminId, array_keys($permissionIds));
}

if ($ordersManagerId > 0) {
    $syncRole($ordersManagerId, [
        'orders.view',
        'orders.manage',
        'notifications.view',
        'complaints.view',
    ]);
}

$count = (int) $pdo->query('SELECT COUNT(*) FROM permissions')->fetchColumn();
echo "Permissions synced: {$count}\n";
