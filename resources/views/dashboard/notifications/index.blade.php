@extends('dashboard.layouts.app')

@section('title', 'الإشعارات')

@section('breadcrumb', 'الإشعارات')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="dashboard-page-title mb-2 text-on-surface">الإشعارات</h1>
            <p class="dashboard-page-subtitle text-on-surface-variant">مراجعة تنبيهات الطلبات والشكاوى والمخزون وإرسال إشعارات للعملاء.</p>
        </div>
        <a class="flex items-center gap-2 rounded-lg bg-primary-container px-6 py-2.5 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" href="{{ route('admin.notifications.send.create') }}">
            <span class="material-symbols-outlined text-[20px]">send</span>
            إرسال إشعار
        </a>
    </div>

    <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">
        @foreach ([
            ['label' => 'الكل', 'value' => $stats['total'], 'class' => 'text-on-surface'],
            ['label' => 'غير مقروء', 'value' => $stats['unread'], 'class' => 'text-error'],
            ['label' => 'طلبات جديدة', 'value' => $stats['new_orders'], 'class' => 'text-tertiary'],
            ['label' => 'طلبات ملغاة', 'value' => $stats['cancelled'], 'class' => 'text-error'],
            ['label' => 'شكاوى جديدة', 'value' => $stats['complaints'], 'class' => 'text-deal'],
            ['label' => 'مخزون منخفض', 'value' => $stats['low_stock'], 'class' => 'text-primary-container'],
        ] as $stat)
            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-4">
                <p class="text-xs text-on-surface-variant">{{ $stat['label'] }}</p>
                <p class="mt-1 text-2xl font-bold {{ $stat['class'] }}">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="dashboard-card overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
        <div class="flex items-center justify-between border-b border-outline-variant bg-surface-container-lowest p-6">
            <h2 class="text-lg font-semibold text-on-surface">تنبيهات النظام</h2>
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
                        <th class="p-4 font-semibold">المرجع</th>
                        <th class="p-4 font-semibold">نوع الإشعار</th>
                        <th class="p-4 font-semibold">التفاصيل</th>
                        <th class="p-4 font-semibold">التاريخ</th>
                        <th class="p-4 font-semibold">الحالة</th>
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
                                    <span class="text-xs text-on-surface-variant">{{ $notification->referenceHint() }}</span>
                                    <span @class(['font-bold text-primary-container' => ! $notification->isRead(), 'font-medium text-on-surface' => $notification->isRead()])>
                                        {{ $notification->referenceLabel() }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-4 align-middle">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $notification->typeBadgeClasses() }}">
                                    {{ $notification->typeLabel() }}
                                </span>
                            </td>
                            <td class="p-4 align-middle text-on-surface-variant">
                                {{ $notification->relatedName() ?? '—' }}
                            </td>
                            <td class="p-4 align-middle text-on-surface-variant">
                                {{ $notification->created_at?->locale('ar')->diffForHumans() }}
                            </td>
                            <td class="p-4 align-middle">
                                @if ($notification->detailLabel())
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $notification->detailBadgeClasses() }}">
                                        {{ $notification->detailLabel() }}
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

    <div class="dashboard-card mt-6 overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
        <div class="border-b border-outline-variant bg-surface-container-lowest p-6">
            <h2 class="text-lg font-semibold text-on-surface">إشعارات مرسلة للعملاء</h2>
            <p class="mt-1 text-sm text-on-surface-variant">آخر الإشعارات التي تم إرسالها لمستخدمي التطبيق.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-right">
                <thead class="border-b border-outline-variant bg-surface-container-low text-on-surface-variant">
                    <tr>
                        <th class="p-4 font-semibold">العميل</th>
                        <th class="p-4 font-semibold">العنوان</th>
                        <th class="p-4 font-semibold">النص</th>
                        <th class="p-4 font-semibold">المرسل</th>
                        <th class="p-4 font-semibold">التاريخ</th>
                        <th class="p-4 font-semibold">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sentNotifications as $sent)
                        <tr class="border-b border-outline-variant/20 transition-colors hover:bg-surface-container/50">
                            <td class="p-4 align-middle text-sm font-medium text-on-surface">{{ $sent->client?->name ?? '—' }}</td>
                            <td class="p-4 align-middle text-sm text-on-surface">{{ $sent->title }}</td>
                            <td class="max-w-xs p-4 align-middle text-sm text-on-surface-variant">
                                <span class="line-clamp-2">{{ $sent->message }}</span>
                            </td>
                            <td class="p-4 align-middle text-sm text-on-surface-variant">{{ $sent->admin?->name ?? '—' }}</td>
                            <td class="p-4 align-middle text-sm text-on-surface-variant">{{ $sent->created_at?->locale('ar')->diffForHumans() }}</td>
                            <td class="p-4 align-middle">
                                @if ($sent->isRead())
                                    <span class="inline-flex items-center rounded-full bg-surface-variant px-2.5 py-0.5 text-xs font-medium text-on-surface-variant">مقروء</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-secondary-container px-2.5 py-0.5 text-xs font-medium text-on-secondary-container">غير مقروء</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-12 text-center text-sm text-on-surface-variant" colspan="6">
                                لم يتم إرسال إشعارات للعملاء بعد.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
