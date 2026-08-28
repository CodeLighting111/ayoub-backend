@extends('dashboard.layouts.app')

@section('title', 'الطلبات')

@section('breadcrumb', 'الطلبات')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'الطلبات',
        'subtitle' => 'متابعة وإدارة طلبات العملاء وحالاتها.',
    ])

    <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4 xl:grid-cols-7">
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
                href="{{ route('admin.orders.index', array_filter(['status' => $stat['key'] ?: null, 'q' => $search ?: null])) }}"
            >
                <p class="text-xs text-on-surface-variant">{{ $stat['label'] }}</p>
                <p @class(['mt-1 text-2xl font-bold', \App\Models\Order::statusAccentClass($stat['key'] ?: null)])>{{ $stat['value'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="dashboard-card overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
        <form action="{{ route('admin.orders.index') }}" class="flex flex-col items-stretch justify-between gap-4 border-b border-outline-variant bg-surface-bright p-6 sm:flex-row sm:items-center" method="GET">
            @if ($status !== '')
                <input name="status" type="hidden" value="{{ $status }}">
            @endif
            <div class="relative w-full sm:w-96">
                <span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input class="w-full rounded-lg border border-outline-variant bg-surface py-2.5 pl-4 pr-10 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" name="q" placeholder="البحث برقم الطلب أو العميل أو الفرع..." type="search" value="{{ $search }}">
            </div>
        </form>

        <div class="dashboard-table-head grid grid-cols-12 items-center gap-4 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
            <div class="col-span-2">رقم الطلب</div>
            <div class="col-span-2">اسم العميل</div>
            <div class="col-span-1">الفرع</div>
            <div class="col-span-2">تاريخ الطلب</div>
            <div class="col-span-1">الإجمالي</div>
            <div class="col-span-2">الحالة</div>
            <div class="col-span-2 text-center">الإجراءات</div>
        </div>

        <div class="divide-y divide-outline-variant/70">
            @forelse ($orders as $order)
                <div class="grid grid-cols-12 items-center gap-4 p-6 text-right transition-colors hover:bg-surface-container-low/80">
                    <div class="col-span-2 text-sm font-semibold text-on-surface" dir="ltr">{{ $order->order_number }}</div>
                    <div class="col-span-2 text-sm text-on-surface">{{ $order->client_name }}</div>
                    <div class="col-span-1 text-sm text-on-surface-variant">{{ $order->branch_name ?: '—' }}</div>
                    <div class="col-span-2 text-sm text-on-surface-variant" dir="ltr">{{ optional($order->created_at)->format('Y-m-d H:i') }}</div>
                    <div class="col-span-1 text-sm font-semibold text-on-surface" dir="ltr">{{ number_format($order->total, 2) }} ج.م</div>
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
                    {{ $search !== '' || $status !== '' ? 'لا توجد نتائج مطابقة.' : 'لا توجد طلبات حتى الآن.' }}
                </div>
            @endforelse
        </div>
    </div>
@endsection
