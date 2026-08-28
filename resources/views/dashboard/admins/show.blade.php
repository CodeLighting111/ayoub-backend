@extends('dashboard.layouts.app')

@section('title', 'تفاصيل المشرف')

@section('breadcrumb', 'المشرفين / تفاصيل')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 border-b border-outline-variant pb-6 md:flex-row md:items-center">
        <div>
            <div class="mb-2 flex flex-wrap items-center gap-3">
                <h1 class="dashboard-page-title text-on-surface">{{ $admin->name }}</h1>
                <span class="inline-flex items-center rounded-full bg-primary-container/10 px-3 py-1 text-xs font-medium text-primary-container">
                    {{ $admin->roleLabel() }}
                </span>
                @if ($admin->id === auth('admin')->id())
                    <span class="inline-flex items-center rounded-full bg-secondary-container px-3 py-1 text-xs font-medium text-on-secondary-container">حسابك</span>
                @endif
            </div>
            <p class="dashboard-page-subtitle text-on-surface-variant">تفاصيل حساب المشرف في النظام.</p>
        </div>
        <div class="flex items-center gap-3">
            <a class="flex items-center gap-2 rounded-lg border border-outline px-4 py-2.5 text-sm font-semibold text-on-surface transition-colors hover:bg-surface-container" href="{{ route('admin.admins.index') }}">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                رجوع
            </a>
            @unless ($admin->isPrimarySuperAdmin() || $admin->id === auth('admin')->id())
                <form action="{{ route('admin.admins.destroy', $admin) }}" data-confirm="هل أنت متأكد من حذف مشرف «{{ $admin->name }}»؟" data-confirm-title="تأكيد الحذف" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="flex items-center gap-2 rounded-lg bg-error-container px-4 py-2.5 text-sm font-semibold text-on-error-container transition-colors hover:bg-error hover:text-on-error" type="submit">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                        حذف
                    </button>
                </form>
            @endunless
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6 lg:col-span-2">
            <h2 class="mb-6 flex items-center gap-2 border-b border-outline-variant pb-4 text-lg font-semibold text-on-surface">
                <span class="material-symbols-outlined text-primary-container">person</span>
                البيانات الأساسية
            </h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="rounded-lg border border-outline-variant/60 bg-surface-container-low px-4 py-3">
                    <p class="text-xs text-on-surface-variant">اسم المشرف</p>
                    <p class="mt-1 text-sm font-semibold text-on-surface">{{ $admin->name }}</p>
                </div>
                <div class="rounded-lg border border-outline-variant/60 bg-surface-container-low px-4 py-3">
                    <p class="text-xs text-on-surface-variant">البريد الإلكتروني</p>
                    <p class="mt-1 text-sm font-semibold text-on-surface">{{ $admin->email }}</p>
                </div>
                <div class="rounded-lg border border-outline-variant/60 bg-surface-container-low px-4 py-3">
                    <p class="text-xs text-on-surface-variant">رقم الهاتف</p>
                    <p class="mt-1 text-sm font-semibold text-on-surface">{{ $admin->phone ?: '—' }}</p>
                </div>
                <div class="rounded-lg border border-outline-variant/60 bg-surface-container-low px-4 py-3">
                    <p class="text-xs text-on-surface-variant">الدور</p>
                    <p class="mt-1 text-sm font-semibold text-on-surface">{{ $admin->roleLabel() }}</p>
                </div>
                <div class="rounded-lg border border-outline-variant/60 bg-surface-container-low px-4 py-3">
                    <p class="text-xs text-on-surface-variant">تاريخ الإنشاء</p>
                    <p class="mt-1 text-sm font-semibold text-on-surface">{{ optional($admin->created_at)->format('Y-m-d H:i') ?: '—' }}</p>
                </div>
                <div class="rounded-lg border border-outline-variant/60 bg-surface-container-low px-4 py-3">
                    <p class="text-xs text-on-surface-variant">آخر تحديث</p>
                    <p class="mt-1 text-sm font-semibold text-on-surface">{{ optional($admin->updated_at)->format('Y-m-d H:i') ?: '—' }}</p>
                </div>
            </div>
        </div>

        <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
            <h2 class="mb-4 text-lg font-semibold text-on-surface">الصورة</h2>
            <div class="flex flex-col items-center gap-4">
                @if ($admin->avatar_url)
                    <img alt="{{ $admin->name }}" class="h-28 w-28 rounded-full border border-outline-variant object-cover" src="{{ asset(ltrim($admin->avatar_url, '/')) }}">
                @else
                    <div class="flex h-28 w-28 items-center justify-center rounded-full border border-outline-variant bg-surface-container-high">
                        <span class="material-symbols-outlined text-5xl text-primary-container">person</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
