@extends('dashboard.layouts.app')

@section('title', 'البانرات')

@section('breadcrumb', 'البانرات')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="dashboard-page-title mb-2 text-on-surface">البانرات</h1>
            <p class="dashboard-page-subtitle text-on-surface-variant">إدارة البانرات في التطبيق مع الصورة والعنوان والوصف.</p>
        </div>
        <a class="flex items-center gap-2 rounded-lg bg-primary-container px-6 py-2.5 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" href="{{ route('admin.banners.create') }}">
            <span class="material-symbols-outlined text-[20px]">add</span>
            إضافة بانر
        </a>
    </div>

    <div class="dashboard-card overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
        <form action="{{ route('admin.banners.index') }}" class="flex flex-col items-center justify-between gap-4 border-b border-outline-variant bg-surface-bright p-6 sm:flex-row" method="GET">
            <div class="relative w-full sm:w-96">
                <span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input class="w-full rounded-lg border border-outline-variant bg-surface py-2.5 pl-4 pr-10 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" name="q" placeholder="البحث بالعنوان أو الوصف..." type="search" value="{{ $search }}">
            </div>
        </form>

        <div class="dashboard-table-head grid grid-cols-12 items-center gap-4 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
            <div class="col-span-1 text-center">#</div>
            <div class="col-span-2 text-center">الصورة</div>
            <div class="col-span-2">العنوان</div>
            <div class="col-span-4">الوصف</div>
            <div class="col-span-1 text-center">الترتيب</div>
            <div class="col-span-2 text-center">الإجراءات</div>
        </div>

        <div class="divide-y divide-outline-variant/70">
            @forelse ($banners as $index => $banner)
                <div class="grid grid-cols-12 items-center gap-4 p-6 text-right transition-colors hover:bg-surface-container-low/80">
                    <div class="col-span-1 text-center text-sm text-on-surface-variant">{{ $index + 1 }}</div>
                    <div class="col-span-2 flex justify-center">
                        @if ($banner->image_url)
                            <img alt="{{ $banner->title }}" class="h-14 w-24 rounded-lg border border-outline-variant object-cover" src="{{ asset(ltrim($banner->image_url, '/')) }}">
                        @else
                            <span class="text-sm text-on-surface-variant">—</span>
                        @endif
                    </div>
                    <div class="col-span-2 text-sm font-semibold text-on-surface">{{ $banner->title }}</div>
                    <div class="col-span-4 text-sm text-on-surface-variant">{{ Str::limit($banner->description, 80) ?: '—' }}</div>
                    <div class="col-span-1 text-center text-sm text-on-surface-variant">{{ $banner->sort_order }}</div>
                    <div class="col-span-2 flex justify-center gap-3">
                        <a class="text-on-surface-variant transition-colors hover:text-primary-container" href="{{ route('admin.banners.edit', $banner) }}" title="تعديل">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                        <form action="{{ route('admin.banners.destroy', $banner) }}" data-confirm="هل أنت متأكد من حذف بانر «{{ $banner->title }}»؟" data-confirm-title="تأكيد الحذف" method="POST">
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
                    {{ $search !== '' ? 'لا توجد نتائج مطابقة للبحث.' : 'لا توجد البانرات حتى الآن.' }}
                </div>
            @endforelse
        </div>
    </div>
@endsection
