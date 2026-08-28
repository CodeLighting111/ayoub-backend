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
            @include('dashboard.partials.back-button')
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

    @php
        $orderFilterParams = fn (array $extra = []) => array_filter(array_merge([
            'status' => $status ?: null,
            'month' => $month,
            'year' => $year,
        ], $extra));
        $clientShowRoute = fn (array $params = []) => route('admin.clients.show', array_merge(['client' => $client], $params));
        $arabicMonths = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
            5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر',
        ];
    @endphp

    <div class="mt-8">
        <div class="mb-6 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h2 class="dashboard-page-title mb-1 text-lg text-on-surface">طلبات العميل</h2>
                <p class="text-sm text-on-surface-variant">جميع الطلبات المرتبطة بهذا العميل مع إمكانية التصفية.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest px-5 py-3">
                    <p class="text-xs text-on-surface-variant">إجمالي الطلبات</p>
                    <p class="text-2xl font-bold text-primary-container">{{ $stats['total'] }}</p>
                </div>
                @if ($month && $year)
                    <div class="dashboard-card rounded-xl border border-primary-container/30 bg-primary-container/5 px-5 py-3">
                        <p class="text-xs text-on-surface-variant">إجمالي {{ $arabicMonths[$month] }} {{ $year }}</p>
                        <p class="text-2xl font-bold text-primary-container" dir="ltr">{{ number_format((float) $monthlyTotal, 2) }} ج.م</p>
                        <p class="mt-1 text-xs text-on-surface-variant">{{ $monthlyOrdersCount }} طلب</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-3 md:grid-cols-4 xl:grid-cols-7">
            @foreach ([
                ['key' => '', 'label' => 'الكل', 'value' => $stats['total']],
                ['key' => 'pending', 'label' => 'قيد الانتظار', 'value' => $stats['pending']],
                ['key' => 'accepted', 'label' => 'مقبول', 'value' => $stats['accepted']],
                ['key' => 'processing', 'label' => 'قيد التجهيز', 'value' => $stats['processing']],
                ['key' => 'shipped', 'label' => 'تم الشحن', 'value' => $stats['shipped']],
                ['key' => 'delivered', 'label' => 'تم التوصيل', 'value' => $stats['delivered']],
                ['key' => 'cancelled', 'label' => 'ملغى', 'value' => $stats['cancelled']],
            ] as $stat)
                <a
                    @class([
                        'dashboard-card rounded-xl border p-4 transition-colors',
                        'border-primary-container bg-primary-container/5' => $status === $stat['key'],
                        'border-outline-variant bg-surface-container-lowest hover:bg-surface-container-low' => $status !== $stat['key'],
                    ])
                    href="{{ $clientShowRoute($orderFilterParams(['status' => $stat['key'] ?: null])) }}"
                >
                    <p class="text-xs text-on-surface-variant">{{ $stat['label'] }}</p>
                    <p @class(['mt-1 text-2xl font-bold', \App\Models\Order::statusAccentClass($stat['key'] ?: null)])>{{ $stat['value'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="dashboard-card mb-6 overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
            <form action="{{ route('admin.clients.show', $client) }}" class="flex flex-col items-stretch justify-between gap-4 border-b border-outline-variant bg-surface-bright p-6 lg:flex-row lg:items-end" method="GET">
                @if ($status !== '')
                    <input name="status" type="hidden" value="{{ $status }}">
                @endif
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-on-surface" for="month">الشهر</label>
                        <select class="dashboard-select-plain w-full rounded-lg border border-outline-variant bg-surface py-2.5 px-4 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" id="month" name="month">
                            <option value="">— اختر الشهر —</option>
                            @foreach ($arabicMonths as $monthNumber => $monthLabel)
                                <option @selected($month === $monthNumber) value="{{ $monthNumber }}">{{ $monthLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-on-surface" for="year">السنة</label>
                        <select class="dashboard-select-plain w-full rounded-lg border border-outline-variant bg-surface py-2.5 px-4 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" id="year" name="year">
                            <option value="">— اختر السنة —</option>
                            @for ($yearOption = now()->year; $yearOption >= now()->year - 5; $yearOption--)
                                <option @selected($year === $yearOption) value="{{ $yearOption }}">{{ $yearOption }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-sm font-semibold text-on-primary transition-colors hover:bg-primary" type="submit">
                            <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                            تصفية
                        </button>
                        @if ($month || $year)
                            <a class="flex items-center justify-center rounded-lg border border-outline-variant px-4 py-2.5 text-sm font-semibold text-on-surface-variant transition-colors hover:bg-surface-container" href="{{ $clientShowRoute($orderFilterParams(['month' => null, 'year' => null])) }}" title="إلغاء تصفية الشهر">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="dashboard-table-head grid grid-cols-12 items-center gap-4 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
                <div class="col-span-2">رقم الطلب</div>
                <div class="col-span-2">تاريخ الطلب</div>
                <div class="col-span-2">طريقة الدفع</div>
                <div class="col-span-2">الإجمالي</div>
                <div class="col-span-2">الحالة</div>
                <div class="col-span-2 text-center">الإجراءات</div>
            </div>

            <div class="divide-y divide-outline-variant/70">
                @forelse ($orders as $order)
                    <div class="grid grid-cols-12 items-center gap-4 p-6 text-right transition-colors hover:bg-surface-container-low/80">
                        <div class="col-span-2 text-sm font-semibold text-on-surface" dir="ltr">{{ $order->order_number }}</div>
                        <div class="col-span-2 text-sm text-on-surface-variant" dir="ltr">{{ optional($order->created_at)->format('Y-m-d H:i') }}</div>
                        <div class="col-span-2 text-sm text-on-surface">{{ $order->paymentMethodLabel() }}</div>
                        <div class="col-span-2 text-sm font-semibold text-on-surface" dir="ltr">{{ number_format($order->total, 2) }} ج.م</div>
                        <div class="col-span-2">
                            @include('dashboard.orders.partials.status-badge', ['order' => $order])
                        </div>
                        <div class="col-span-2 flex justify-center">
                            <a class="text-on-surface-variant transition-colors hover:text-primary-container" href="{{ route('admin.orders.show', $order) }}" title="عرض">
                                <span class="material-symbols-outlined">visibility</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-sm text-on-surface-variant">
                        @if ($status !== '' || $month || $year)
                            لا توجد طلبات مطابقة للتصفية المحددة.
                        @else
                            لا توجد طلبات لهذا العميل حتى الآن.
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
