@php
    $menuItems = [
        ['key' => 'onboarding', 'label' => 'الصفحات الابتدائية', 'icon' => 'home', 'route' => 'admin.onboarding.index'],
        ['key' => 'customer-categories', 'label' => 'فئات العملاء', 'icon' => 'people', 'route' => 'admin.client-categories.index'],
        ['key' => 'governorates', 'label' => 'المحافظات', 'icon' => 'map', 'route' => null],
        ['key' => 'cities', 'label' => 'المدن', 'icon' => 'location_city', 'route' => null],
        ['key' => 'areas', 'label' => 'المناطق', 'icon' => 'location_on', 'route' => null],
        ['key' => 'clients', 'label' => 'العملاء', 'icon' => 'person', 'route' => null],
        ['key' => 'main-categories', 'label' => 'فئات المنتجات الرئيسية', 'icon' => 'category', 'route' => null],
        ['key' => 'sub-categories', 'label' => 'فئات المنتجات الفرعية', 'icon' => 'reorder', 'route' => null],
        ['key' => 'products', 'label' => 'المنتجات', 'icon' => 'inventory_2', 'route' => null],
        ['key' => 'orders', 'label' => 'الطلبات', 'icon' => 'shopping_cart', 'route' => null],
        ['key' => 'complaints', 'label' => 'الشكاوى', 'icon' => 'report_problem', 'route' => null],
        ['key' => 'about', 'label' => 'عنا', 'icon' => 'info', 'route' => null],
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
        <form action="{{ route('admin.logout') }}" method="POST">
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
