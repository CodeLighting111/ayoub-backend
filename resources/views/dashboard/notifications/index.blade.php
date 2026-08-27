@extends('dashboard.layouts.app')

@section('title', 'الإشعارات')

@section('breadcrumb', 'الإشعارات')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">الإشعارات</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">مراجعة التنبيهات الخاصة بالطلبات الجديدة والملغاة من العملاء.</p>
    </div>

    <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
        @foreach ([
            ['label' => 'الكل', 'value' => $stats['total'], 'class' => 'text-on-surface'],
            ['label' => 'غير مقروء', 'value' => $stats['unread'], 'class' => 'text-error'],
            ['label' => 'طلبات جديدة', 'value' => $stats['new_orders'], 'class' => 'text-tertiary'],
            ['label' => 'طلبات ملغاة', 'value' => $stats['cancelled'], 'class' => 'text-error'],
        ] as $stat)
            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-4">
                <p class="text-xs text-on-surface-variant">{{ $stat['label'] }}</p>
                <p class="mt-1 text-2xl font-bold {{ $stat['class'] }}">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="dashboard-card overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
        <div class="flex items-center justify-between border-b border-outline-variant bg-surface-container-lowest p-6">
            <h2 class="text-lg font-semibold text-on-surface">تنبيهات الطلبات</h2>
            @if ($stats['unread'] > 0)
                <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button class="text-sm font-medium text-primary-container transition-colors hover:text-primary hover:underline" type="submit">
                        تحديد الكل كمقروء
                    </button>
                </form>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-right">
                <thead class="border-b border-outline-variant bg-surface-container-low text-on-surface-variant">
                    <tr>
                        <th class="w-12 p-4 text-center font-semibold"></th>
                        <th class="p-4 font-semibold">رقم الطلب</th>
                        <th class="p-4 font-semibold">نوع الإشعار</th>
                        <th class="p-4 font-semibold">العميل</th>
                        <th class="p-4 font-semibold">التاريخ</th>
                        <th class="p-4 font-semibold">حالة الطلب</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notifications as $notification)
                        <tr
                            @class([
                                'cursor-pointer border-b border-outline-variant/20 transition-colors hover:bg-surface-container/50',
                                'border-r-4 border-r-error bg-primary-container/5' => ! $notification->isRead(),
                            ])
                            onclick="window.location='{{ route('admin.notifications.show', $notification) }}'"
                        >
                            <td class="p-4 text-center align-middle">
                                @unless ($notification->isRead())
                                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-error" title="غير مقروء"></span>
                                @endunless
                            </td>
                            <td class="p-4 align-middle">
                                <div class="flex flex-col">
                                    <span class="text-xs text-on-surface-variant">رقم الطلب</span>
                                    <span @class(['font-bold text-primary-container' => ! $notification->isRead(), 'font-medium text-on-surface' => $notification->isRead()])>
                                        #{{ $notification->order?->order_number }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-4 align-middle">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $notification->typeBadgeClasses() }}">
                                    {{ $notification->typeLabel() }}
                                </span>
                            </td>
                            <td class="p-4 align-middle text-on-surface-variant">
                                {{ $notification->order?->client_name }}
                            </td>
                            <td class="p-4 align-middle text-on-surface-variant">
                                {{ $notification->created_at?->locale('ar')->diffForHumans() }}
                            </td>
                            <td class="p-4 align-middle">
                                @if ($notification->order)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $notification->order->statusBadgeClasses() }}">
                                        {{ $notification->order->statusLabel() }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-12 text-center text-sm text-on-surface-variant" colspan="6">
                                لا توجد إشعارات حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
