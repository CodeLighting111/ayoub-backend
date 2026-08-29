@extends('dashboard.layouts.app')

@section('title', 'الإحصائيات')

@section('breadcrumb', 'الإحصائيات')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="dashboard-page-title mb-2 text-on-surface">الإحصائيات</h1>
            <p class="dashboard-page-subtitle text-on-surface-variant">ملخص أداء المتجر للفترة المحددة.</p>
        </div>
        <div class="dashboard-card rounded-xl border border-primary-container/30 bg-primary-container/5 px-5 py-3">
            <p class="text-xs text-on-surface-variant">الفترة المعروضة</p>
            <p class="text-lg font-bold text-primary-container">{{ $periodLabel }}</p>
        </div>
    </div>

    <div class="dashboard-card mb-8 overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
        <form action="{{ route('admin.statistics.index') }}" class="border-b border-outline-variant bg-surface-bright p-6" method="GET">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 lg:items-end">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-on-surface" for="month_from">من شهر</label>
                    <select class="dashboard-select-plain w-full rounded-lg border border-outline-variant bg-surface py-2.5 px-4 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" id="month_from" name="month_from" required>
                        @foreach ($arabicMonths as $monthNumber => $monthLabel)
                            <option @selected($monthFrom === $monthNumber) value="{{ $monthNumber }}">{{ $monthLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-on-surface" for="month_to">إلى شهر</label>
                    <select class="dashboard-select-plain w-full rounded-lg border border-outline-variant bg-surface py-2.5 px-4 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" id="month_to" name="month_to" required>
                        @foreach ($arabicMonths as $monthNumber => $monthLabel)
                            <option @selected($monthTo === $monthNumber) value="{{ $monthNumber }}">{{ $monthLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-on-surface" for="year">السنة</label>
                    <select class="dashboard-select-plain w-full rounded-lg border border-outline-variant bg-surface py-2.5 px-4 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" id="year" name="year" required>
                        @for ($yearOption = now()->year; $yearOption >= now()->year - 5; $yearOption--)
                            <option @selected($year === $yearOption) value="{{ $yearOption }}">{{ $yearOption }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex gap-2 sm:col-span-2 lg:col-span-2">
                    <button class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-sm font-semibold text-on-primary transition-colors hover:bg-primary" type="submit">
                        <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                        عرض الإحصائيات
                    </button>
                    <a class="flex items-center justify-center rounded-lg border border-outline-variant px-4 py-2.5 text-sm font-semibold text-on-surface-variant transition-colors hover:bg-surface-container" href="{{ route('admin.statistics.index') }}" title="الشهر الحالي">
                        <span class="material-symbols-outlined text-[18px]">today</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
            <div class="mb-3 flex items-center justify-between">
                <span class="text-sm text-on-surface-variant">إجمالي الطلبات</span>
                <span class="material-symbols-outlined text-primary-container">shopping_cart</span>
            </div>
            <p class="text-3xl font-bold text-on-surface">{{ $stats['orders_total'] }}</p>
        </div>
        <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
            <div class="mb-3 flex items-center justify-between">
                <span class="text-sm text-on-surface-variant">إجمالي المبيعات</span>
                <span class="material-symbols-outlined text-primary-container">payments</span>
            </div>
            <p class="text-3xl font-bold text-primary-container">@include('dashboard.partials.money', ['amount' => $stats['revenue']])</p>
        </div>
        <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
            <div class="mb-3 flex items-center justify-between">
                <span class="text-sm text-on-surface-variant">متوسط قيمة الطلب</span>
                <span class="material-symbols-outlined text-primary-container">avg_pace</span>
            </div>
            <p class="text-3xl font-bold text-on-surface">@include('dashboard.partials.money', ['amount' => $stats['average_order_value']])</p>
        </div>
        <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
            <div class="mb-3 flex items-center justify-between">
                <span class="text-sm text-on-surface-variant">عملاء جدد</span>
                <span class="material-symbols-outlined text-primary-container">person_add</span>
            </div>
            <p class="text-3xl font-bold text-on-surface">{{ $stats['new_clients_count'] }}</p>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-2 gap-4 md:grid-cols-4 xl:grid-cols-7">
        @foreach ([
            ['key' => 'pending', 'label' => 'قيد الانتظار'],
            ['key' => 'accepted', 'label' => 'مقبول'],
            ['key' => 'processing', 'label' => 'قيد التجهيز'],
            ['key' => 'shipped', 'label' => 'تم الشحن'],
            ['key' => 'delivered', 'label' => 'تم التوصيل'],
            ['key' => 'cancelled', 'label' => 'ملغى'],
        ] as $statusItem)
            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-4">
                <p class="text-xs text-on-surface-variant">{{ $statusItem['label'] }}</p>
                <p @class(['mt-1 text-2xl font-bold', \App\Models\Order::statusAccentClass($statusItem['key'])])>
                    {{ $stats['orders_by_status'][$statusItem['key']] ?? 0 }}
                </p>
            </div>
        @endforeach
        <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-4">
            <p class="text-xs text-on-surface-variant">الشكاوى</p>
            <p class="mt-1 text-2xl font-bold text-deal">{{ $stats['complaints_total'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="dashboard-card overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
            <div class="border-b border-outline-variant bg-surface-bright p-6">
                <h2 class="flex items-center gap-2 text-lg font-semibold text-primary-container">
                    <span class="material-symbols-outlined">leaderboard</span>
                    أفضل العملاء
                </h2>
            </div>
            <div class="dashboard-table-head grid grid-cols-12 gap-4 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
                <div class="col-span-5">العميل</div>
                <div class="col-span-3 text-center">عدد الطلبات</div>
                <div class="col-span-4 text-center">إجمالي المبيعات</div>
            </div>
            <div class="divide-y divide-outline-variant/70">
                @forelse ($stats['top_clients'] as $clientRow)
                    <div class="grid grid-cols-12 items-center gap-4 p-6 text-right">
                        <div class="col-span-5 text-sm font-semibold text-on-surface">{{ $clientRow->client_name }}</div>
                        <div class="col-span-3 text-center text-sm text-on-surface-variant">{{ $clientRow->orders_count }}</div>
                        <div class="col-span-4 text-center text-sm font-semibold text-on-surface">@include('dashboard.partials.money', ['amount' => $clientRow->revenue])</div>
                    </div>
                @empty
                    <div class="p-10 text-center text-sm text-on-surface-variant">لا توجد طلبات في هذه الفترة.</div>
                @endforelse
            </div>
        </div>

        <div class="dashboard-card overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
            <div class="border-b border-outline-variant bg-surface-bright p-6">
                <h2 class="flex items-center gap-2 text-lg font-semibold text-primary-container">
                    <span class="material-symbols-outlined">inventory_2</span>
                    أكثر المنتجات مبيعاً
                </h2>
            </div>
            <div class="dashboard-table-head grid grid-cols-12 gap-4 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
                <div class="col-span-5">المنتج</div>
                <div class="col-span-3 text-center">الكمية</div>
                <div class="col-span-4 text-center">إجمالي المبيعات</div>
            </div>
            <div class="divide-y divide-outline-variant/70">
                @forelse ($stats['top_products'] as $productRow)
                    <div class="grid grid-cols-12 items-center gap-4 p-6 text-right">
                        <div class="col-span-5 text-sm font-semibold text-on-surface">{{ $productRow->product_name }}</div>
                        <div class="col-span-3 text-center text-sm text-on-surface-variant">{{ $productRow->total_quantity }}</div>
                        <div class="col-span-4 text-center text-sm font-semibold text-on-surface">@include('dashboard.partials.money', ['amount' => $productRow->total_revenue])</div>
                    </div>
                @empty
                    <div class="p-10 text-center text-sm text-on-surface-variant">لا توجد مبيعات منتجات في هذه الفترة.</div>
                @endforelse
            </div>
        </div>
    </div>

    @if (count($stats['payment_methods']) > 0)
        <div class="dashboard-card mt-6 overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
            <div class="border-b border-outline-variant bg-surface-bright p-6">
                <h2 class="flex items-center gap-2 text-lg font-semibold text-primary-container">
                    <span class="material-symbols-outlined">credit_card</span>
                    طرق الدفع
                </h2>
            </div>
            <div class="dashboard-table-head grid grid-cols-12 gap-4 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
                <div class="col-span-4">طريقة الدفع</div>
                <div class="col-span-4 text-center">عدد الطلبات</div>
                <div class="col-span-4 text-center">إجمالي المبيعات</div>
            </div>
            <div class="divide-y divide-outline-variant/70">
                @foreach ($stats['payment_methods'] as $paymentRow)
                    <div class="grid grid-cols-12 items-center gap-4 p-6 text-right">
                        <div class="col-span-4 text-sm font-semibold text-on-surface">{{ $paymentRow['label'] }}</div>
                        <div class="col-span-4 text-center text-sm text-on-surface-variant">{{ $paymentRow['orders_count'] }}</div>
                        <div class="col-span-4 text-center text-sm font-semibold text-on-surface">@include('dashboard.partials.money', ['amount' => $paymentRow['revenue']])</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
