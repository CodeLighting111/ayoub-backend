@extends('dashboard.layouts.app')

@section('title', 'تفاصيل شاشة الترحيب')

@section('breadcrumb', 'الصفحات الابتدائية / تفاصيل')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 border-b border-outline-variant pb-6 md:flex-row md:items-center">
        <div>
            <div class="mb-2 flex flex-wrap items-center gap-3">
                <h1 class="dashboard-page-title text-on-surface">{{ $screen->title }}</h1>
                @if ($screen->status === 'active')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-secondary-container px-3 py-1 text-xs font-medium text-on-secondary-container">
                        <span class="h-2 w-2 rounded-full bg-surface-tint"></span>
                        مفعل
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-surface-variant px-3 py-1 text-xs font-medium text-on-surface-variant">
                        غير مفعلة
                    </span>
                @endif
            </div>
            <p class="dashboard-page-subtitle text-on-surface-variant">عرض تفاصيل شاشة الترحيب كما تظهر للمستخدمين الجدد في التطبيق.</p>
        </div>
        <div class="flex items-center gap-3">
            @include('dashboard.partials.back-button')
            <a class="flex items-center gap-2 rounded-lg border border-outline px-4 py-2.5 text-sm font-semibold text-on-surface transition-colors hover:bg-surface-container" href="{{ route('admin.onboarding.edit', $screen) }}">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                تعديل
            </a>
            <form action="{{ route('admin.onboarding.destroy', $screen) }}" data-confirm="هل أنت متأكد من حذف هذه الشاشة؟" data-confirm-title="تأكيد الحذف" method="POST">
                @csrf
                @method('DELETE')
                <button class="flex items-center gap-2 rounded-lg bg-error-container px-4 py-2.5 text-sm font-semibold text-on-error-container transition-colors hover:bg-error hover:text-on-error" type="submit">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                    حذف
                </button>
            </form>
        </div>
    </div>

    <div class="dashboard-card w-full rounded-xl border border-outline-variant/40 bg-surface-container-lowest p-6">
        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-5">
            <div class="space-y-4 lg:col-span-3">
                <div>
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-primary-container">
                        <span class="material-symbols-outlined">info</span>
                        بيانات الشاشة
                    </h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-lg bg-surface-container p-4">
                            <p class="mb-1 text-xs font-medium text-on-surface-variant">العنوان الرئيسي</p>
                            <p class="text-base font-medium text-on-surface">{{ $screen->title }}</p>
                        </div>
                        <div class="rounded-lg bg-surface-container p-4">
                            <p class="mb-1 text-xs font-medium text-on-surface-variant">ترتيب الظهور</p>
                            <p class="text-base font-medium text-on-surface">الشاشة رقم ({{ $screen->sort_order }})</p>
                        </div>
                        <div class="rounded-lg bg-surface-container p-4">
                            <p class="mb-1 text-xs font-medium text-on-surface-variant">الحالة</p>
                            <p class="text-base font-medium text-on-surface">
                                {{ $screen->status === 'active' ? 'مفعلة وتظهر للمستخدمين' : 'غير مفعلة ولا تظهر في التطبيق' }}
                            </p>
                        </div>
                        <div class="rounded-lg bg-surface-container p-4">
                            <p class="mb-1 text-xs font-medium text-on-surface-variant">تاريخ الإنشاء</p>
                            <p class="text-base font-medium text-on-surface" dir="ltr">{{ optional($screen->created_at)->format('Y-m-d') }}</p>
                        </div>
                        <div class="rounded-lg bg-surface-container p-4 md:col-span-2">
                            <p class="mb-1 text-xs font-medium text-on-surface-variant">آخر تحديث</p>
                            <p class="text-base font-medium text-on-surface" dir="ltr">{{ optional($screen->updated_at)->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-primary-container">
                        <span class="material-symbols-outlined">description</span>
                        الوصف الإيضاحي
                    </h2>
                    <div class="rounded-lg border border-outline-variant/50 bg-surface-bright p-5">
                        <p class="text-base leading-relaxed text-on-surface">{{ $screen->description }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                @include('dashboard.onboarding._preview', [
                    'embedded' => true,
                    'screen' => $screen,
                ])
            </div>
        </div>
    </div>
@endsection
