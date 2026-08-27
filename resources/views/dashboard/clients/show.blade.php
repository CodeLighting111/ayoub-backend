@extends('dashboard.layouts.app')

@section('title', 'تفاصيل العميل')

@section('breadcrumb', 'العملاء / تفاصيل')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 border-b border-outline-variant pb-6 md:flex-row md:items-center">
        <div>
            <div class="mb-2 flex flex-wrap items-center gap-3">
                <h1 class="dashboard-page-title text-on-surface">{{ $client->name }}</h1>
                @if ($client->status === 'active')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-secondary-container px-3 py-1 text-xs font-medium text-on-secondary-container">نشط</span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-surface-variant px-3 py-1 text-xs font-medium text-on-surface-variant">غير نشط</span>
                @endif
            </div>
            <p class="dashboard-page-subtitle text-on-surface-variant">{{ $client->branch_name }} — {{ $client->category?->title }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a class="flex items-center gap-2 rounded-lg border border-outline px-4 py-2.5 text-sm font-semibold text-on-surface transition-colors hover:bg-surface-container" href="{{ route('admin.clients.edit', $client) }}">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                تعديل
            </a>
            <form action="{{ route('admin.clients.destroy', $client) }}" data-confirm="هل أنت متأكد من حذف هذا العميل؟" data-confirm-title="تأكيد الحذف" method="POST">
                @csrf
                @method('DELETE')
                <button class="flex items-center gap-2 rounded-lg bg-error-container px-4 py-2.5 text-sm font-semibold text-on-error-container transition-colors hover:bg-error hover:text-on-error" type="submit">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                    حذف
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-1">
            <div class="dashboard-card rounded-xl border border-outline-variant/40 bg-surface-container-lowest p-6">
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-primary-container">
                    <span class="material-symbols-outlined">contact_phone</span>
                    معلومات التواصل
                </h2>
                <div class="space-y-4 text-sm">
                    <div class="flex items-center justify-between border-b border-outline-variant py-2">
                        <span class="text-on-surface-variant">رقم الهاتف</span>
                        <span class="font-medium text-on-surface" dir="ltr">{{ $client->phone }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-on-surface-variant">الشخص المسؤول</span>
                        <span class="font-medium text-on-surface">{{ $client->responsible_person ?: '—' }}</span>
                    </div>
                </div>
            </div>

            <div class="dashboard-card rounded-xl border border-outline-variant/40 bg-surface-container-lowest p-6">
                <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-primary-container">
                    <span class="material-symbols-outlined">location_city</span>
                    التفاصيل الجغرافية
                </h2>
                <div class="space-y-4">
                    <div class="flex items-center gap-3 rounded-lg bg-surface-container p-3">
                        <span class="material-symbols-outlined text-primary-container">map</span>
                        <div>
                            <p class="text-xs text-on-surface-variant">المحافظة</p>
                            <p class="font-medium text-on-surface">{{ $client->governorate?->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 rounded-lg bg-surface-container p-3">
                        <span class="material-symbols-outlined text-primary-container">location_on</span>
                        <div>
                            <p class="text-xs text-on-surface-variant">المدينة</p>
                            <p class="font-medium text-on-surface">{{ $client->city?->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 rounded-lg bg-surface-container p-3">
                        <span class="material-symbols-outlined text-primary-container">home_pin</span>
                        <div>
                            <p class="text-xs text-on-surface-variant">المنطقة</p>
                            <p class="font-medium text-on-surface">{{ $client->area?->name }}</p>
                        </div>
                    </div>
                    @if ($client->address)
                        <div class="rounded-lg border border-outline-variant/50 bg-surface-bright p-4">
                            <p class="mb-1 text-xs text-on-surface-variant">العنوان بالتفصيل</p>
                            <p class="text-sm leading-relaxed text-on-surface">{{ $client->address }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="dashboard-card flex h-full flex-col rounded-xl border border-outline-variant/40 bg-surface-container-lowest p-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="flex items-center gap-2 text-lg font-semibold text-primary-container">
                        <span class="material-symbols-outlined">pin_drop</span>
                        الموقع على الخريطة
                    </h2>
                    @if ($client->latitude && $client->longitude)
                        <a class="flex items-center gap-1 text-sm font-medium text-primary-container transition-colors hover:text-primary" href="https://www.google.com/maps?q={{ $client->latitude }},{{ $client->longitude }}" rel="noopener noreferrer" target="_blank">
                            فتح في خرائط جوجل
                            <span class="material-symbols-outlined text-sm">open_in_new</span>
                        </a>
                    @endif
                </div>

                @if ($client->latitude && $client->longitude)
                    <div class="relative min-h-[400px] flex-1 overflow-hidden rounded-lg border border-outline-variant bg-surface-container">
                        <iframe
                            allowfullscreen
                            class="h-full min-h-[400px] w-full"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://maps.google.com/maps?q={{ $client->latitude }},{{ $client->longitude }}&z=15&output=embed"
                            title="موقع العميل"
                        ></iframe>
                    </div>
                    <p class="mt-4 flex items-start gap-2 rounded-lg border border-outline-variant/50 bg-surface-bright p-3 text-sm text-on-surface-variant">
                        <span class="material-symbols-outlined text-outline">info</span>
                        يرجى التأكد من تطابق العنوان الجغرافي مع موقع الاستلام الفعلي لتجنب أي تأخير في تسليم الطلبات.
                    </p>
                @else
                    <div class="flex min-h-[400px] flex-1 items-center justify-center rounded-lg border border-dashed border-outline-variant bg-surface-container text-sm text-on-surface-variant">
                        لم يتم تحديد موقع على الخريطة بعد.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
