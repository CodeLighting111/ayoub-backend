<header class="sticky top-0 z-10 flex items-center justify-between border-b border-outline-variant bg-surface-container-lowest/95 px-8 py-3 backdrop-blur-sm">
    <div class="text-sm font-medium text-on-surface-variant">
        {{ $breadcrumb ?? 'لوحة التحكم' }}
    </div>
    <div class="flex items-center gap-4">
        <button class="rounded-full p-2 text-on-surface-variant transition-colors hover:bg-surface-container hover:text-primary-container" type="button">
            <span class="material-symbols-outlined">notifications</span>
        </button>
        <button class="rounded-full p-2 text-on-surface-variant transition-colors hover:bg-surface-container hover:text-primary-container" type="button">
            <span class="material-symbols-outlined">settings</span>
        </button>
        <div class="mr-2 flex items-center gap-3 border-r border-outline-variant pr-4">
            @include('dashboard.partials.brand-logo', ['size' => 'header', 'showText' => false, 'framed' => false])
            <div class="hidden text-right sm:block">
                <div class="text-sm font-semibold text-on-surface">{{ auth('admin')->user()->name }}</div>
                <div class="text-xs text-on-surface-variant">{{ auth('admin')->user()->email }}</div>
            </div>
        </div>
    </div>
</header>
