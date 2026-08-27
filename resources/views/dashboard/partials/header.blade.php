<header class="sticky top-0 z-10 flex items-center justify-between border-b border-outline-variant bg-surface-container-lowest/95 px-8 py-3 backdrop-blur-sm">
    <div class="text-sm font-medium text-on-surface-variant">
        {{ $breadcrumb ?? 'لوحة التحكم' }}
    </div>
    <div class="flex items-center gap-4">
        <a class="relative rounded-full p-2 text-on-surface-variant transition-colors hover:bg-surface-container hover:text-primary-container" href="{{ route('admin.notifications.index') }}">
            <span class="material-symbols-outlined">notifications</span>
            @if (($unreadNotificationsCount ?? 0) > 0)
                <span class="absolute left-1 top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-error px-1 text-[10px] font-bold text-white">
                    {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                </span>
            @endif
        </a>
        <a class="rounded-full p-2 text-on-surface-variant transition-colors hover:bg-surface-container hover:text-primary-container" href="{{ route('admin.profile.edit') }}">
            <span class="material-symbols-outlined">settings</span>
        </a>
        <div class="mr-2 flex items-center gap-3 border-r border-outline-variant pr-4">
            @if (auth('admin')->user()->avatar_url)
                <img alt="{{ auth('admin')->user()->name }}" class="h-10 w-10 rounded-full border border-outline-variant object-cover" src="{{ asset(ltrim(auth('admin')->user()->avatar_url, '/')) }}">
            @else
                @include('dashboard.partials.brand-logo', ['size' => 'header', 'showText' => false, 'framed' => false])
            @endif
            <div class="hidden text-right sm:block">
                <div class="text-sm font-semibold text-on-surface">{{ auth('admin')->user()->name }}</div>
                <div class="text-xs text-on-surface-variant">{{ auth('admin')->user()->email }}</div>
            </div>
        </div>
    </div>
</header>
