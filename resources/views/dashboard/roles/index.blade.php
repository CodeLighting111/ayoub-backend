@extends('dashboard.layouts.app')

@section('title', 'الأدوار')

@section('breadcrumb', 'الأدوار')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="dashboard-page-title mb-2 text-on-surface">الأدوار</h1>
            <p class="dashboard-page-subtitle text-on-surface-variant">إدارة أدوار المشرفين وصلاحياتهم في النظام.</p>
        </div>
        <a class="flex items-center gap-2 rounded-lg bg-primary-container px-6 py-2.5 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" href="{{ route('admin.roles.create') }}">
            <span class="material-symbols-outlined text-[20px]">add</span>
            إضافة دور جديد
        </a>
    </div>

    <div class="dashboard-card overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
        <form action="{{ route('admin.roles.index') }}" class="flex flex-col items-center justify-between gap-4 border-b border-outline-variant bg-surface-bright p-6 sm:flex-row" method="GET">
            <div class="relative w-full sm:w-96">
                <span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input class="w-full rounded-lg border border-outline-variant bg-surface py-2.5 pl-4 pr-10 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" name="q" placeholder="البحث عن دور..." type="search" value="{{ $search }}">
            </div>
        </form>

        <div class="dashboard-table-head grid grid-cols-12 items-center gap-4 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
            <div class="col-span-1 text-center">#</div>
            <div class="col-span-5">اسم الدور</div>
            <div class="col-span-2">عدد المشرفين</div>
            <div class="col-span-2">عدد الأذونات</div>
            <div class="col-span-2 text-center">الإجراءات</div>
        </div>

        <div class="divide-y divide-outline-variant/70">
            @forelse ($roles as $index => $role)
                <div class="grid grid-cols-12 items-center gap-4 p-6 text-right transition-colors hover:bg-surface-container-low/80">
                    <div class="col-span-1 text-center text-sm text-on-surface-variant">{{ $index + 1 }}</div>
                    <div class="col-span-5 text-sm font-semibold text-on-surface">{{ $role->name }}</div>
                    <div class="col-span-2 text-sm text-on-surface">{{ $role->admins_count }}</div>
                    <div class="col-span-2 text-sm text-on-surface">{{ $role->permissions_count }}</div>
                    <div class="col-span-2 flex justify-center gap-3">
                        <a class="text-on-surface-variant transition-colors hover:text-primary-container" href="{{ route('admin.roles.edit', $role) }}" title="تعديل">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                        @unless ($role->slug === 'superadmin')
                            <form action="{{ route('admin.roles.destroy', $role) }}" data-confirm="هل أنت متأكد من حذف دور «{{ $role->name }}»؟" data-confirm-title="تأكيد الحذف" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-error transition-colors hover:text-error/80" title="حذف" type="submit">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        @endunless
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-sm text-on-surface-variant">
                    {{ $search !== '' ? 'لا توجد نتائج مطابقة.' : 'لا توجد أدوار حتى الآن.' }}
                </div>
            @endforelse
        </div>
    </div>
@endsection
