@extends('dashboard.layouts.app')

@section('title', 'إدارة شاشات الترحيب')

@section('breadcrumb', 'الصفحات الابتدائية')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="dashboard-page-title mb-2 text-on-surface">إدارة شاشات الترحيب</h1>
            <p class="dashboard-page-subtitle text-on-surface-variant">قم بإدارة المحتوى الترحيبي الذي يظهر للمستخدمين الجدد عند فتح التطبيق.</p>
        </div>
        <a class="flex items-center gap-2 rounded-lg bg-primary-container px-6 py-2.5 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" href="{{ route('admin.onboarding.create') }}">
            <span class="material-symbols-outlined text-[20px]">add</span>
            إضافة صفحة جديدة
        </a>
    </div>

    <div class="dashboard-card overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
        <div class="dashboard-table-head grid grid-cols-12 items-center gap-4 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
            <div class="col-span-2 text-center">الصورة</div>
            <div class="col-span-3">العنوان</div>
            <div class="col-span-4">الوصف</div>
            <div class="col-span-2 text-center">الترتيب</div>
            <div class="col-span-1 text-center">إجراءات</div>
        </div>

        <div class="divide-y divide-outline-variant/70">
            @forelse ($screens as $screen)
                <div @class([
                    'group grid grid-cols-12 items-center gap-4 p-6 text-right transition-colors hover:bg-surface-container-low/80',
                    'bg-surface-container-low/30' => $screen->status === 'draft',
                ])>
                    <div class="col-span-2 flex justify-center">
                        @if ($screen->image_url)
                            <div class="relative h-40 w-24 overflow-hidden rounded-lg border border-outline-variant shadow-sm">
                                <img alt="{{ $screen->title }}" class="h-full w-full object-cover" src="{{ $screen->image_url }}">
                            </div>
                        @else
                            <div class="relative flex h-40 w-24 items-center justify-center rounded-lg border border-dashed border-outline-variant bg-surface-container shadow-sm">
                                <span class="material-symbols-outlined text-4xl text-outline-variant">image</span>
                            </div>
                        @endif
                    </div>

                    <div class="col-span-3">
                        <h3 @class([
                            'mb-1 text-sm font-semibold leading-5',
                            'text-on-surface' => $screen->status === 'active',
                            'text-on-surface-variant' => $screen->status === 'draft',
                        ])>
                            <a class="hover:text-primary-container" href="{{ route('admin.onboarding.show', $screen) }}">{{ $screen->title }}</a>
                        </h3>
                        @if ($screen->status === 'active')
                            <span class="inline-block rounded-full bg-secondary-container px-2 py-0.5 text-xs font-medium text-on-secondary-container">مفعل</span>
                        @else
                            <span class="inline-block rounded-full bg-surface-variant px-2 py-0.5 text-xs font-medium text-on-surface-variant">غير مفعلة</span>
                        @endif
                    </div>

                    <div @class([
                        'dashboard-table-body col-span-4 text-on-surface-variant',
                        'opacity-70' => $screen->status === 'draft',
                    ])>{{ $screen->description }}</div>

                    <div class="col-span-2 flex items-center justify-center gap-2">
                        <div @class([
                            'flex h-8 w-8 items-center justify-center rounded bg-surface-container text-base font-semibold',
                            'opacity-50' => $screen->status === 'draft',
                        ])>{{ $screen->sort_order }}</div>
                        <div class="flex flex-col text-outline-variant opacity-0 transition-opacity group-hover:opacity-100">
                            <form action="{{ route('admin.onboarding.move-up', $screen) }}" method="POST">
                                @csrf
                                <button class="hover:text-primary-container" type="submit"><span class="material-symbols-outlined text-sm">keyboard_arrow_up</span></button>
                            </form>
                            <form action="{{ route('admin.onboarding.move-down', $screen) }}" method="POST">
                                @csrf
                                <button class="hover:text-primary-container" type="submit"><span class="material-symbols-outlined text-sm">keyboard_arrow_down</span></button>
                            </form>
                        </div>
                    </div>

                    <div class="col-span-1 flex justify-center gap-3">
                        <a class="text-on-surface-variant transition-colors hover:text-primary-container" href="{{ route('admin.onboarding.show', $screen) }}" title="تفاصيل">
                            <span class="material-symbols-outlined">visibility</span>
                        </a>
                        <a class="text-on-surface-variant transition-colors hover:text-primary-container" href="{{ route('admin.onboarding.edit', $screen) }}" title="تعديل">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                        <form action="{{ route('admin.onboarding.destroy', $screen) }}" data-confirm="هل أنت متأكد من حذف هذه الشاشة؟" data-confirm-title="تأكيد الحذف" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="text-error transition-colors hover:text-error/80" title="حذف" type="submit">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-sm text-on-surface-variant">
                    لا توجد شاشات ترحيب حتى الآن.
                </div>
            @endforelse
        </div>
    </div>
@endsection
