@extends('dashboard.layouts.app')

@section('title', 'المشرفين')

@section('breadcrumb', 'المشرفين')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="dashboard-page-title mb-2 text-on-surface">المشرفين</h1>
            <p class="dashboard-page-subtitle text-on-surface-variant">قائمة بحسابات المشرفين وأدوارهم في النظام.</p>
        </div>
        <a class="flex items-center gap-2 rounded-lg bg-primary-container px-6 py-2.5 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" href="{{ route('admin.admins.create') }}">
            <span class="material-symbols-outlined text-[20px]">add</span>
            إضافة مشرف
        </a>
    </div>

    <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest">
        <form action="{{ route('admin.admins.index') }}" class="flex flex-col items-center justify-between gap-4 border-b border-outline-variant bg-surface-bright p-6 sm:flex-row" method="GET">
            <div class="relative w-full sm:w-96">
                <span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input class="w-full rounded-lg border border-outline-variant bg-surface py-2.5 pl-4 pr-10 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" name="q" placeholder="البحث بالاسم أو البريد أو الهاتف..." type="search" value="{{ $search }}">
            </div>
        </form>

        <div class="dashboard-table-head grid grid-cols-12 items-center gap-4 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
            <div class="col-span-1 text-center">#</div>
            <div class="col-span-2">اسم المشرف</div>
            <div class="col-span-2">البريد الإلكتروني</div>
            <div class="col-span-2">رقم الهاتف</div>
            <div class="col-span-2">الدور</div>
            <div class="col-span-1 text-center">الحالة</div>
            <div class="col-span-2 text-center">الإجراءات</div>
        </div>

        <div class="divide-y divide-outline-variant/70 overflow-visible">
            @forelse ($admins as $index => $admin)
                <div @class([
                    'relative grid grid-cols-12 items-center gap-4 overflow-visible p-6 text-right transition-colors hover:bg-surface-container-low/80',
                    'bg-surface-container-low/30' => ($admin->status ?? 'active') === 'suspended',
                ])>
                    <div class="col-span-1 text-center text-sm text-on-surface-variant">{{ $index + 1 }}</div>
                    <div class="col-span-2 text-sm font-semibold text-on-surface">{{ $admin->name }}</div>
                    <div class="col-span-2 text-sm text-on-surface-variant">{{ $admin->email }}</div>
                    <div class="col-span-2 text-sm text-on-surface">{{ $admin->phone ?: '—' }}</div>
                    <div class="col-span-2">
                        <span class="inline-flex items-center rounded-full bg-primary-container/10 px-2.5 py-0.5 text-xs font-medium text-primary-container">
                            {{ $admin->roleLabel() }}
                        </span>
                    </div>
                    <div class="col-span-1 text-center">
                        @if (($admin->status ?? 'active') === 'active')
                            <span class="inline-block rounded-full bg-secondary-container px-2 py-0.5 text-xs font-medium text-on-secondary-container">نشط</span>
                        @else
                            <span class="inline-block rounded-full bg-surface-variant px-2 py-0.5 text-xs font-medium text-on-surface-variant">غير نشط</span>
                        @endif
                    </div>
                    <div class="col-span-2 flex justify-center gap-2">
                        <a class="rounded p-2 text-on-surface-variant transition-colors hover:bg-surface-container hover:text-primary-container" href="{{ route('admin.admins.show', $admin) }}" title="عرض التفاصيل">
                            <span class="material-symbols-outlined">visibility</span>
                        </a>
                        @unless ($admin->isPrimarySuperAdmin() || $admin->id === auth('admin')->id())
                            <div class="relative" data-status-menu>
                                <button class="rounded p-2 text-on-surface-variant transition-colors hover:bg-surface-container hover:text-primary-container" data-status-toggle title="تغيير الحالة" type="button">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                                <div class="absolute right-0 z-50 mt-1 hidden w-40 rounded-lg border border-outline-variant bg-surface-container-lowest py-1 shadow-lg" data-status-dropdown>
                                    <p class="border-b border-outline-variant px-3 py-2 text-xs font-semibold text-on-surface">تغيير الحالة</p>
                                    @if (($admin->status ?? 'active') === 'active')
                                        <div class="flex w-full items-center gap-2 bg-surface-container-low px-3 py-2.5 text-right text-xs font-medium text-primary-container">
                                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                            نشط
                                        </div>
                                    @else
                                        <form action="{{ route('admin.admins.update-status', $admin) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input name="status" type="hidden" value="active">
                                            <button class="flex w-full items-center gap-2 px-3 py-2.5 text-right text-xs transition-colors hover:bg-surface-container-low" type="submit">
                                                <span class="material-symbols-outlined text-[16px] text-primary-container">check_circle</span>
                                                نشط
                                            </button>
                                        </form>
                                    @endif
                                    @if (($admin->status ?? 'active') === 'suspended')
                                        <div class="flex w-full items-center gap-2 bg-surface-container-low px-3 py-2.5 text-right text-xs font-medium text-error">
                                            <span class="material-symbols-outlined text-[16px]">block</span>
                                            غير نشط
                                        </div>
                                    @else
                                        <form action="{{ route('admin.admins.update-status', $admin) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input name="status" type="hidden" value="suspended">
                                            <button class="flex w-full items-center gap-2 px-3 py-2.5 text-right text-xs text-error transition-colors hover:bg-surface-container-low" type="submit">
                                                <span class="material-symbols-outlined text-[16px]">block</span>
                                                غير نشط
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('admin.admins.destroy', $admin) }}" data-confirm="هل أنت متأكد من حذف مشرف «{{ $admin->name }}»؟" data-confirm-title="تأكيد الحذف" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="rounded p-2 text-error transition-colors hover:bg-error-container/30" title="حذف" type="submit">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        @endunless
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-sm text-on-surface-variant">
                    {{ $search !== '' ? 'لا توجد نتائج مطابقة.' : 'لا يوجد مشرفون حتى الآن.' }}
                </div>
            @endforelse
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

            dropdown.addEventListener('click', function (event) {
                event.stopPropagation();
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
