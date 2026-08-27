@php
    $menuItems = [
        ['key' => 'onboarding', 'label' => 'الصفحات الابتدائية', 'icon' => 'home', 'route' => 'admin.onboarding.index'],
        ['key' => 'customer-categories', 'label' => 'فئات العملاء', 'icon' => 'people', 'route' => 'admin.client-categories.index'],
        ['key' => 'governorates', 'label' => 'المحافظات', 'icon' => 'map', 'route' => 'admin.governorates.index'],
        ['key' => 'cities', 'label' => 'المدن', 'icon' => 'location_city', 'route' => 'admin.cities.index'],
        ['key' => 'areas', 'label' => 'المناطق', 'icon' => 'location_on', 'route' => 'admin.areas.index'],
        ['key' => 'clients', 'label' => 'العملاء', 'icon' => 'person', 'route' => 'admin.clients.index'],
        ['key' => 'brands', 'label' => 'العلامات التجارية', 'icon' => 'sell', 'route' => 'admin.brands.index'],
        ['key' => 'main-categories', 'label' => 'فئات المنتجات الرئيسية', 'icon' => 'category', 'route' => 'admin.main-product-categories.index'],
        ['key' => 'sub-categories', 'label' => 'فئات المنتجات الفرعية', 'icon' => 'reorder', 'route' => 'admin.sub-product-categories.index'],
        ['key' => 'products', 'label' => 'المنتجات', 'icon' => 'inventory_2', 'route' => 'admin.products.index'],
        ['key' => 'orders', 'label' => 'الطلبات', 'icon' => 'shopping_cart', 'route' => 'admin.orders.index'],
        ['key' => 'notifications', 'label' => 'الإشعارات', 'icon' => 'notifications', 'route' => 'admin.notifications.index'],
        ['key' => 'complaints', 'label' => 'الشكاوى', 'icon' => 'report_problem', 'route' => 'admin.complaints.index'],
        ['key' => 'about', 'label' => 'عنا', 'icon' => 'info', 'route' => 'admin.about.edit'],
        ['key' => 'settings', 'label' => 'الإعدادات العامة', 'icon' => 'settings', 'route' => 'admin.settings.edit'],
        ['key' => 'roles', 'label' => 'الأدوار', 'icon' => 'badge', 'route' => 'admin.roles.index'],
        ['key' => 'admins', 'label' => 'إضافة مشرف', 'icon' => 'person_add', 'route' => 'admin.admins.create'],
    ];
@endphp

<aside class="dashboard-sidebar fixed right-0 top-0 z-20 flex h-screen w-64 flex-col overflow-hidden border-l border-outline-variant bg-white shadow-[0_0_24px_rgba(27,94,32,0.06)]">
    <div class="border-b border-outline-variant px-5 py-5">
        @include('dashboard.partials.brand-logo', [
            'stacked' => true,
            'size' => 'sidebar',
            'framed' => false,
            'subtitle' => 'لوحة التحكم الرئيسية',
        ])
    </div>

    <nav class="flex flex-1 flex-col gap-1 overflow-y-auto bg-surface-container-low px-3 py-4">
        @foreach ($menuItems as $item)
            @php
                $isActive = ($activeMenu ?? '') === $item['key'];
                $href = $item['route'] ? route($item['route']) : '#';
            @endphp
            <a
                @class([
                    'dashboard-nav-link flex cursor-pointer flex-row items-center gap-3 rounded-lg px-4 py-3 transition-all duration-200',
                    'bg-primary-container text-on-primary shadow-sm' => $isActive,
                    'text-on-surface-variant hover:bg-white hover:text-on-surface' => ! $isActive,
                ])
                href="{{ $href }}"
            >
                <span class="material-symbols-outlined text-[22px]">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="border-t border-outline-variant bg-surface-container-low p-4">
        <form action="{{ route('admin.logout') }}" data-confirm="هل أنت متأكد من تسجيل الخروج؟" data-confirm-action="تسجيل الخروج" data-confirm-title="تأكيد تسجيل الخروج" data-confirm-tone="primary" method="POST">
            @csrf
            <button
                class="flex w-full items-center justify-center gap-2 rounded-lg bg-error px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#9f1515]"
                type="submit"
            >
                <span class="material-symbols-outlined text-[20px]">logout</span>
                تسجيل الخروج
            </button>
        </form>
    </div>
</aside>
