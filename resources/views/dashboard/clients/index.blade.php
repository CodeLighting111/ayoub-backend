@extends('dashboard.layouts.app')

@section('title', 'قائمة العملاء')

@section('breadcrumb', 'العملاء')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="dashboard-page-title mb-2 text-on-surface">قائمة العملاء</h1>
            <p class="dashboard-page-subtitle text-on-surface-variant">إدارة وتحديث بيانات العملاء المسجلين في النظام.</p>
        </div>
        <a class="flex items-center gap-2 rounded-lg bg-primary-container px-6 py-2.5 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" href="{{ route('admin.clients.create') }}">
            <span class="material-symbols-outlined text-[20px]">add</span>
            إضافة عميل جديد
        </a>
    </div>

    <div class="dashboard-card overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
        <form action="{{ route('admin.clients.index') }}" class="flex flex-col items-center justify-between gap-4 border-b border-outline-variant bg-surface-bright p-6 sm:flex-row" method="GET">
            <div class="relative w-full sm:w-96">
                <span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input class="w-full rounded-lg border border-outline-variant bg-surface py-2.5 pl-4 pr-10 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" name="q" placeholder="البحث عن عميل..." type="search" value="{{ $search }}">
            </div>
        </form>

        <div class="overflow-x-auto">
            <div class="dashboard-table-head grid min-w-[1100px] grid-cols-12 items-center gap-3 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
                <div class="col-span-2">الاسم</div>
                <div class="col-span-2">رقم الهاتف</div>
                <div class="col-span-1">اسم الفرع</div>
                <div class="col-span-1">المحافظة</div>
                <div class="col-span-1">المدينة</div>
                <div class="col-span-1">المنطقة</div>
                <div class="col-span-1 text-center">الحالة</div>
                <div class="col-span-3 text-center">الإجراءات</div>
            </div>

            <div class="min-w-[1100px] divide-y divide-outline-variant/70">
                @forelse ($clients as $client)
                    <div @class([
                        'grid grid-cols-12 items-center gap-3 p-6 text-right transition-colors hover:bg-surface-container-low/80',
                        'bg-surface-container-low/30' => $client->status === 'suspended',
                    ])>
                        <div class="col-span-2">
                            <a class="text-sm font-semibold text-primary-container hover:text-primary" href="{{ route('admin.clients.show', $client) }}">{{ $client->name }}</a>
                        </div>
                        <div class="col-span-2 text-sm tabular-nums text-on-surface-variant" dir="ltr">{{ $client->phone }}</div>
                        <div class="col-span-1 text-sm text-on-surface">{{ $client->branch_name }}</div>
                        <div class="col-span-1 text-sm text-on-surface-variant">{{ $client->governorate?->name ?? '—' }}</div>
                        <div class="col-span-1 text-sm text-on-surface-variant">{{ $client->city?->name ?? '—' }}</div>
                        <div class="col-span-1 text-sm text-on-surface-variant">{{ $client->area?->name ?? '—' }}</div>
                        <div class="col-span-1 text-center">
                            @if ($client->status === 'active')
                                <span class="inline-block rounded-full bg-secondary-container px-2 py-0.5 text-xs font-medium text-on-secondary-container">نشط</span>
                            @else
                                <span class="inline-block rounded-full bg-surface-variant px-2 py-0.5 text-xs font-medium text-on-surface-variant">غير نشط</span>
                            @endif
                        </div>
                        <div class="col-span-3 flex justify-center gap-2">
                            <a class="rounded p-2 text-on-surface-variant transition-colors hover:bg-surface-container hover:text-primary-container" href="{{ route('admin.clients.show', $client) }}" title="عرض">
                                <span class="material-symbols-outlined">visibility</span>
                            </a>
                            <a class="rounded p-2 text-on-surface-variant transition-colors hover:bg-surface-container hover:text-primary-container" href="{{ route('admin.clients.edit', $client) }}" title="تعديل">
                                <span class="material-symbols-outlined">edit</span>
                            </a>
                            <div class="relative" data-status-menu>
                                <button class="rounded p-2 text-on-surface-variant transition-colors hover:bg-surface-container hover:text-primary-container" data-status-toggle title="تغيير الحالة" type="button">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                                <div class="absolute left-0 z-10 mt-1 hidden w-28 overflow-hidden rounded-lg border border-outline-variant bg-surface-container-lowest shadow-sm" data-status-dropdown>
                                    @if ($client->status !== 'active')
                                        <form action="{{ route('admin.clients.update-status', $client) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input name="status" type="hidden" value="active">
                                            <button class="flex w-full items-center gap-2 px-2.5 py-1.5 text-right text-xs transition-colors hover:bg-surface-container-low" type="submit">
                                                <span class="material-symbols-outlined text-[16px] text-primary-container">check_circle</span>
                                                نشط
                                            </button>
                                        </form>
                                    @endif
                                    @if ($client->status !== 'suspended')
                                        <form action="{{ route('admin.clients.update-status', $client) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input name="status" type="hidden" value="suspended">
                                            <button class="flex w-full items-center gap-2 px-2.5 py-1.5 text-right text-xs text-error transition-colors hover:bg-surface-container-low" type="submit">
                                                <span class="material-symbols-outlined text-[16px]">block</span>
                                                غير نشط
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('admin.clients.destroy', $client) }}" data-confirm="هل أنت متأكد من حذف عميل «{{ $client->name }}»؟" data-confirm-title="تأكيد الحذف" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="rounded p-2 text-error transition-colors hover:bg-error-container/30" title="حذف" type="submit">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-sm text-on-surface-variant">
                        {{ $search !== '' ? 'لا توجد نتائج مطابقة للبحث.' : 'لا يوجد عملاء حتى الآن.' }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        document.querySelectorAll('[data-status-menu]').forEach(function (menu) {
            const toggle = menu.querySelector('[data-status-toggle]');
            const dropdown = menu.querySelector('[data-status-dropdown]');

            toggle.addEventListener('click', function (event) {
                event.stopPropagation();
                document.querySelectorAll('[data-status-dropdown]').forEach(function (item) {
                    if (item !== dropdown) {
                        item.classList.add('hidden');
                    }
                });
                dropdown.classList.toggle('hidden');
            });
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('[data-status-dropdown]').forEach(function (dropdown) {
                dropdown.classList.add('hidden');
            });
        });
    })();
</script>
@endpush
