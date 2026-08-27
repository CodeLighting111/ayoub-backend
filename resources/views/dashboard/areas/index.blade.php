@extends('dashboard.layouts.app')

@section('title', 'المناطق')

@section('breadcrumb', 'المناطق')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="dashboard-page-title mb-2 text-on-surface">المناطق</h1>
            <p class="dashboard-page-subtitle text-on-surface-variant">إدارة المناطق المرتبطة بكل مدينة.</p>
        </div>
        <a class="flex items-center gap-2 rounded-lg bg-primary-container px-6 py-2.5 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" href="{{ route('admin.areas.create') }}">
            <span class="material-symbols-outlined text-[20px]">add</span>
            إضافة منطقة جديدة
        </a>
    </div>

    <div class="dashboard-card overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
        <form action="{{ route('admin.areas.index') }}" class="flex flex-col items-center justify-between gap-4 border-b border-outline-variant bg-surface-bright p-6 sm:flex-row" method="GET">
            <div class="relative w-full sm:w-96">
                <span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input class="w-full rounded-lg border border-outline-variant bg-surface py-2.5 pl-4 pr-10 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" name="q" placeholder="البحث عن منطقة أو مدينة..." type="search" value="{{ $search }}">
            </div>
        </form>

        <div class="dashboard-table-head grid grid-cols-12 items-center gap-4 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
            <div class="col-span-1 text-center">#</div>
            <div class="col-span-3">اسم المحافظة</div>
            <div class="col-span-3">اسم المدينة</div>
            <div class="col-span-3">اسم المنطقة</div>
            <div class="col-span-2 text-center">الإجراءات</div>
        </div>

        <div class="divide-y divide-outline-variant/70">
            @forelse ($areas as $index => $area)
                <div class="grid grid-cols-12 items-center gap-4 p-6 text-right transition-colors hover:bg-surface-container-low/80">
                    <div class="col-span-1 text-center text-sm text-on-surface-variant">{{ $index + 1 }}</div>
                    <div class="col-span-3 text-sm text-on-surface-variant">{{ $area->city?->governorate?->name }}</div>
                    <div class="col-span-3 text-sm text-on-surface-variant">{{ $area->city?->name }}</div>
                    <div class="col-span-3 text-sm font-semibold text-on-surface">{{ $area->name }}</div>
                    <div class="col-span-2 flex justify-center gap-3">
                        <a class="text-on-surface-variant transition-colors hover:text-primary-container" href="{{ route('admin.areas.edit', $area) }}" title="تعديل">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                        <form action="{{ route('admin.areas.destroy', $area) }}" data-confirm="هل أنت متأكد من حذف منطقة «{{ $area->name }}»؟" data-confirm-title="تأكيد الحذف" method="POST">
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
                    {{ $search !== '' ? 'لا توجد نتائج مطابقة للبحث.' : 'لا توجد مناطق حتى الآن.' }}
                </div>
            @endforelse
        </div>
    </div>
@endsection
