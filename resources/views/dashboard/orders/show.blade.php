@extends('dashboard.layouts.app')

@section('title', 'تفاصيل الطلب')

@section('breadcrumb', 'الطلبات / تفاصيل')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="dashboard-page-title mb-2 text-on-surface">{{ $order->order_number }}</h1>
            <p class="dashboard-page-subtitle text-on-surface-variant">تاريخ الطلب: {{ optional($order->created_at)->format('Y-m-d H:i') }}</p>
            <p class="dashboard-page-subtitle text-on-surface-variant">
                تاريخ التوصيل المتوقع:
                {{ $order->expected_delivery_at ? $order->expected_delivery_at->format('Y-m-d H:i') : '—' }}
            </p>
        </div>
        <a class="rounded-lg px-6 py-2.5 text-sm font-semibold text-on-surface-variant transition-colors hover:bg-surface-container" href="{{ route('admin.orders.index') }}">
            العودة للقائمة
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                <h2 class="mb-4 text-lg font-semibold text-on-surface">منتجات الطلب</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-right text-sm">
                        <thead class="border-b border-outline-variant text-on-surface-variant">
                            <tr>
                                <th class="px-3 py-3">المنتج</th>
                                <th class="px-3 py-3">السعر</th>
                                <th class="px-3 py-3">الكمية</th>
                                <th class="px-3 py-3">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr class="border-b border-outline-variant/60">
                                    <td class="px-3 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($item->image_url)
                                                <img alt="{{ $item->product_name }}" class="h-10 w-10 rounded-lg border border-outline-variant object-cover" src="{{ asset(ltrim($item->image_url, '/')) }}">
                                            @endif
                                            <div>
                                                <div class="font-semibold text-on-surface">{{ $item->product_name }}</div>
                                                @if ($item->unit_label)
                                                    <div class="text-xs text-on-surface-variant">{{ $item->unit_label }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4" dir="ltr">{{ number_format($item->unit_price, 2) }} ج.م</td>
                                    <td class="px-3 py-4">{{ $item->quantity }}</td>
                                    <td class="px-3 py-4 font-semibold" dir="ltr">{{ number_format($item->line_total, 2) }} ج.م</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                <h2 class="mb-4 text-lg font-semibold text-on-surface">ملخص الدفع</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-on-surface-variant">المجموع الفرعي</span><span dir="ltr">{{ number_format($order->subtotal, 2) }} ج.م</span></div>
                    <div class="flex justify-between"><span class="text-on-surface-variant">رسوم التوصيل</span><span dir="ltr">{{ number_format($order->delivery_fee, 2) }} ج.م</span></div>
                    <div class="flex justify-between border-t border-outline-variant pt-3 text-base font-bold text-on-surface"><span>الإجمالي</span><span dir="ltr">{{ number_format($order->total, 2) }} ج.م</span></div>
                    <div class="flex justify-between"><span class="text-on-surface-variant">طريقة الدفع</span><span>{{ $order->paymentMethodLabel() }}</span></div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                <h2 class="mb-4 text-lg font-semibold text-on-surface">حالة الطلب</h2>
                <p class="mb-4 text-sm text-on-surface-variant">
                    الحالة الحالية:
                    @include('dashboard.orders.partials.status-badge', ['order' => $order])
                </p>
                @if (! in_array($order->status, ['delivered', 'cancelled'], true))
                    <form action="{{ route('admin.orders.update-status', $order) }}" class="space-y-4" method="POST">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface" for="expected_delivery_at">تاريخ التوصيل المتوقع</label>
                            <input
                                class="block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface"
                                id="expected_delivery_at"
                                name="expected_delivery_at"
                                type="datetime-local"
                                value="{{ old('expected_delivery_at', optional($order->expected_delivery_at)?->format('Y-m-d\TH:i')) }}"
                            >
                            @error('expected_delivery_at')
                                <p class="mt-1 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface" for="status">حالة الطلب</label>
                            <select class="dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface" id="status" name="status" required>
                                @foreach (\App\Models\Order::STATUSES as $option)
                                    <option @selected(old('status', $order->status) === $option) value="{{ $option }}">{{ (new \App\Models\Order(['status' => $option]))->statusLabel() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="w-full rounded-lg bg-primary-container px-4 py-2.5 text-sm font-semibold text-on-primary" type="submit">تحديث الطلب</button>
                    </form>
                @else
                    <div class="rounded-lg bg-surface-container-low px-4 py-3 text-sm text-on-surface-variant">
                        <span class="font-semibold text-on-surface">تاريخ التوصيل المتوقع:</span>
                        {{ $order->expected_delivery_at ? $order->expected_delivery_at->format('Y-m-d H:i') : '—' }}
                    </div>
                @endif
            </div>

            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                <h2 class="mb-4 text-lg font-semibold text-on-surface">بيانات العميل</h2>
                <div class="space-y-3 text-sm">
                    <div><span class="text-on-surface-variant">الاسم:</span> {{ $order->client_name }}</div>
                    <div><span class="text-on-surface-variant">الهاتف:</span> {{ $order->client_phone }}</div>
                    <div><span class="text-on-surface-variant">الفرع:</span> {{ $order->branch_name ?: '—' }}</div>
                    <div><span class="text-on-surface-variant">العنوان:</span> {{ $order->delivery_address ?: '—' }}</div>
                    <div>
                        <span class="text-on-surface-variant">الميعاد المناسب للتوصيل:</span>
                        {{ $order->preferredDeliveryLabel() ?: '—' }}
                    </div>
                    @php
                        $latitude = $order->client?->latitude;
                        $longitude = $order->client?->longitude;
                        $mapsUrl = $latitude && $longitude
                            ? 'https://www.google.com/maps?q='.$latitude.','.$longitude
                            : ($order->delivery_address
                                ? 'https://www.google.com/maps/search/?api=1&query='.urlencode($order->delivery_address)
                                : null);
                    @endphp
                    @if ($mapsUrl)
                        <div>
                            <a
                                class="inline-flex items-center gap-1 text-sm font-medium text-primary-container transition-colors hover:text-primary"
                                href="{{ $mapsUrl }}"
                                rel="noopener noreferrer"
                                target="_blank"
                            >
                                <span class="material-symbols-outlined text-base">map</span>
                                اعرض الموقع على الخريطة
                                <span class="material-symbols-outlined text-sm">open_in_new</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if ($order->notes)
                <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                    <h2 class="mb-4 text-lg font-semibold text-on-surface">ملاحظات الطلب</h2>
                    <p class="text-sm text-on-surface-variant">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
