@extends('dashboard.layouts.app')

@section('title', 'الشكاوى')

@section('breadcrumb', 'الشكاوى')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إدارة الشكاوى',
        'subtitle' => 'قائمة بجميع الشكاوى الواردة من العملاء.',
    ])

    <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
        @foreach ([
            ['key' => '', 'label' => 'الكل', 'value' => $stats['total']],
            ['key' => 'pending', 'label' => 'قيد المراجعة', 'value' => $stats['pending']],
            ['key' => 'resolved', 'label' => 'تم الحل', 'value' => $stats['resolved']],
            ['key' => 'rejected', 'label' => 'تم الرفض', 'value' => $stats['rejected']],
        ] as $stat)
            <a
                @class([
                    'dashboard-card rounded-xl border p-4 transition-colors',
                    'border-primary-container bg-primary-container/5' => $status === $stat['key'],
                    'border-outline-variant bg-surface-container-lowest hover:bg-surface-container-low' => $status !== $stat['key'],
                ])
                href="{{ route('admin.complaints.index', array_filter(['status' => $stat['key'] ?: null, 'q' => $search ?: null])) }}"
            >
                <p class="text-xs text-on-surface-variant">{{ $stat['label'] }}</p>
                <p @class(['mt-1 text-2xl font-bold', \App\Models\Complaint::statusAccentClass($stat['key'] ?: null)])>{{ $stat['value'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="dashboard-card overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
        <form action="{{ route('admin.complaints.index') }}" class="flex flex-col items-stretch justify-between gap-4 border-b border-outline-variant bg-surface-bright p-6 sm:flex-row sm:items-center" method="GET">
            @if ($status !== '')
                <input name="status" type="hidden" value="{{ $status }}">
            @endif
            <div class="relative w-full sm:w-96">
                <span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input class="w-full rounded-lg border border-outline-variant bg-surface py-2.5 pl-4 pr-10 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" name="q" placeholder="البحث بالعميل أو الهاتف أو الموضوع..." type="search" value="{{ $search }}">
            </div>
        </form>

        <div class="dashboard-table-head grid grid-cols-12 items-center gap-4 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
            <div class="col-span-1">#</div>
            <div class="col-span-2">اسم العميل</div>
            <div class="col-span-2">رقم الهاتف</div>
            <div class="col-span-2">الموضوع</div>
            <div class="col-span-2">تاريخ الشكوى</div>
            <div class="col-span-2">الحالة</div>
            <div class="col-span-1 text-center">إجراءات</div>
        </div>

        <div class="divide-y divide-outline-variant/70">
            @forelse ($complaints as $complaint)
                <div class="grid grid-cols-12 items-center gap-4 p-6 text-right transition-colors hover:bg-surface-container-low/80">
                    <div class="col-span-1 text-sm font-semibold text-on-surface">{{ $complaint->id }}</div>
                    <div class="col-span-2 text-sm text-on-surface">{{ $complaint->client_name }}</div>
                    <div class="col-span-2 text-sm text-on-surface">{{ $complaint->client_phone }}</div>
                    <div class="col-span-2 truncate text-sm text-on-surface">{{ $complaint->subject }}</div>
                    <div class="col-span-2 text-sm text-on-surface-variant" dir="ltr">{{ optional($complaint->created_at)->format('Y-m-d H:i') }}</div>
                    <div class="col-span-2">
                        @include('dashboard.complaints.partials.status-badge', ['complaint' => $complaint])
                    </div>
                    <div class="col-span-1 flex justify-center">
                        <a class="text-on-surface-variant transition-colors hover:text-primary-container" href="{{ route('admin.complaints.show', $complaint) }}" title="عرض">
                            <span class="material-symbols-outlined">visibility</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-sm text-on-surface-variant">
                    {{ $search !== '' || $status !== '' ? 'لا توجد نتائج مطابقة.' : 'لا توجد شكاوى حتى الآن.' }}
                </div>
            @endforelse
        </div>
    </div>
@endsection
