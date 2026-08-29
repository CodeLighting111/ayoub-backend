@extends('dashboard.layouts.app')

@section('title', 'المنتجات')

@section('breadcrumb', 'المنتجات')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="dashboard-page-title mb-2 text-on-surface">المنتجات</h1>
            <p class="dashboard-page-subtitle text-on-surface-variant">إدارة منتجات المتجر وأسعارها ومخزونها.</p>
        </div>
        <a class="flex items-center gap-2 rounded-lg bg-primary-container px-6 py-2.5 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" href="{{ route('admin.products.create') }}">
            <span class="material-symbols-outlined text-[20px]">add</span>
            إضافة منتج
        </a>
    </div>

    <div class="dashboard-card overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest">
        <form action="{{ route('admin.products.index') }}" class="flex flex-col items-stretch justify-between gap-4 border-b border-outline-variant bg-surface-bright p-6 sm:flex-row sm:items-center" method="GET">
            <div class="relative w-full sm:w-96">
                <span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input class="w-full rounded-lg border border-outline-variant bg-surface py-2.5 pl-4 pr-10 text-sm outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20" name="q" placeholder="البحث عن منتج أو علامة أو فئة..." type="search" value="{{ $search }}">
            </div>
            <div class="w-full sm:w-56">
                <select
                    class="dashboard-select block w-full rounded-lg border border-outline-variant bg-surface py-2.5 px-4 text-sm text-on-surface outline-none focus:border-primary-container focus:ring-2 focus:ring-primary-container/20"
                    name="status"
                    onchange="this.form.submit()"
                >
                    <option @selected($status === '') value="">كل الحالات</option>
                    <option @selected($status === 'active') value="active">نشط</option>
                    <option @selected($status === 'inactive') value="inactive">غير نشط</option>
                </select>
            </div>
        </form>

        <div class="dashboard-table-head grid grid-cols-12 items-center gap-4 border-b border-outline-variant bg-surface-container-low p-6 text-right text-on-surface-variant">
            <div class="col-span-1 text-center">#</div>
            <div class="col-span-1 text-center">الصورة</div>
            <div class="col-span-2">اسم المنتج</div>
            <div class="col-span-1">العلامة</div>
            <div class="col-span-2">الفئة</div>
            <div class="col-span-1">السعر</div>
            <div class="col-span-1">المخزون</div>
            <div class="col-span-1">الحالة</div>
            <div class="col-span-2 text-center">الإجراءات</div>
        </div>

        <div class="divide-y divide-outline-variant/70">
            @forelse ($products as $index => $product)
                <div class="grid grid-cols-12 items-center gap-4 p-6 text-right transition-colors hover:bg-surface-container-low/80">
                    <div class="col-span-1 text-center text-sm text-on-surface-variant">{{ $index + 1 }}</div>
                    <div class="col-span-1 flex justify-center">
                        @if ($product->image_url)
                            <img alt="{{ $product->name }}" class="h-12 w-12 rounded-lg border border-outline-variant object-cover" src="{{ asset(ltrim($product->image_url, '/')) }}">
                        @else
                            <span class="text-sm text-on-surface-variant">—</span>
                        @endif
                    </div>
                    <div class="col-span-2 text-sm font-semibold text-on-surface">{{ $product->name }}</div>
                    <div class="col-span-1 text-sm text-on-surface-variant">{{ $product->brand?->name ?? '—' }}</div>
                    <div class="col-span-2 text-sm text-on-surface-variant">
                        <div>{{ $product->subCategory?->mainCategory?->title ?? '—' }}</div>
                        <div class="text-xs text-on-surface-variant/80">{{ $product->subCategory?->title ?? '' }}</div>
                    </div>
                    <div class="col-span-1 text-sm font-semibold text-on-surface">@include('dashboard.partials.money', ['amount' => $product->discount_price ?? $product->price])</div>
                    <div class="col-span-1">
                        @if ($product->stock <= \App\Models\AdminNotification::LOW_STOCK_THRESHOLD)
                            <span class="inline-flex rounded-full bg-error/10 px-2.5 py-1 text-xs font-semibold text-error">{{ $product->stock }}</span>
                        @else
                            <span class="inline-flex rounded-full bg-secondary-container/40 px-2.5 py-1 text-xs font-semibold text-on-secondary-container">{{ $product->stock }}</span>
                        @endif
                    </div>
                    <div class="col-span-1">
                        @if ($product->status === 'active')
                            <span class="inline-flex rounded-full bg-secondary-container/40 px-2.5 py-1 text-xs font-semibold text-on-secondary-container">نشط</span>
                        @else
                            <span class="inline-flex rounded-full bg-surface-container-high px-2.5 py-1 text-xs font-semibold text-on-surface-variant">غير نشط</span>
                        @endif
                    </div>
                    <div class="col-span-2 flex justify-center gap-3">
                        <a class="text-on-surface-variant transition-colors hover:text-primary-container" href="{{ route('admin.products.edit', $product) }}" title="تعديل">
                            <span class="material-symbols-outlined">edit</span>
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" data-confirm="هل أنت متأكد من حذف منتج «{{ $product->name }}»؟" data-confirm-title="تأكيد الحذف" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="text-error transition-colors hover:text-error/80" title="حذف" type="submit">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-12 p-12 text-center text-sm text-on-surface-variant">
                    @if ($search !== '' || $status !== '')
                        لا توجد نتائج مطابقة للبحث أو الفلتر.
                    @else
                        لا توجد منتجات حتى الآن.
                    @endif
                </div>
            @endforelse
        </div>
    </div>
@endsection
