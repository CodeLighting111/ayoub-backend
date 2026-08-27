@extends('dashboard.layouts.app')

@section('title', 'الإعدادات العامة')

@section('breadcrumb', 'الإعدادات العامة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">الإعدادات العامة</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">تخصيص الهوية البصرية وخيارات التوصيل للمنصة.</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" class="grid grid-cols-1 gap-6 xl:grid-cols-3" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6 xl:col-span-2">
            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                <h2 class="mb-6 flex items-center gap-2 border-b border-outline-variant pb-4 text-lg font-semibold text-on-surface">
                    <span class="material-symbols-outlined text-primary-container">branding_watermark</span>
                    الهوية البصرية
                </h2>

                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface">تحميل الشعار</label>
                        <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                            <div class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-lg border-2 border-dashed border-outline-variant bg-surface-container">
                                @if ($settings->logo_url)
                                    <img alt="{{ $settings->app_title }}" class="h-full w-full object-contain" src="{{ asset(ltrim($settings->logo_url, '/')) }}">
                                @else
                                    <span class="material-symbols-outlined text-3xl text-outline">add_photo_alternate</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-on-surface-variant">قم بتحميل شعار المنصة. يُنصح باستخدام PNG بخلفية شفافة.</p>
                                <input accept="image/*" class="mt-3 block w-full text-sm text-on-surface-variant file:mr-4 file:rounded-lg file:border-0 file:bg-primary-container file:px-4 file:py-2 file:text-sm file:font-semibold file:text-on-primary hover:file:bg-primary" id="logo" name="logo" type="file">
                                @error('logo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface" for="app_title">اسم المنصة <span class="text-error">*</span></label>
                        <input
                            @class([
                                'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                                'border-red-500' => $errors->has('app_title'),
                            ])
                            id="app_title"
                            name="app_title"
                            required
                            type="text"
                            value="{{ old('app_title', $settings->app_title) }}"
                        >
                        @error('app_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface" for="app_description">وصف المنصة</label>
                        <textarea
                            @class([
                                'block min-h-32 w-full resize-y rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                                'border-red-500' => $errors->has('app_description'),
                            ])
                            id="app_description"
                            name="app_description"
                            placeholder="اكتب وصفاً مختصراً عن المنصة..."
                        >{{ old('app_description', $settings->app_description) }}</textarea>
                        @error('app_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                <h2 class="mb-6 flex items-center gap-2 border-b border-outline-variant pb-4 text-lg font-semibold text-on-surface">
                    <span class="material-symbols-outlined text-primary-container">local_shipping</span>
                    إعدادات التوصيل
                </h2>

                <div class="space-y-6">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface" for="delivery_fee">رسوم التوصيل (ج.م) <span class="text-error">*</span></label>
                        <input
                            @class([
                                'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                                'border-red-500' => $errors->has('delivery_fee'),
                            ])
                            id="delivery_fee"
                            min="0"
                            name="delivery_fee"
                            required
                            step="0.01"
                            type="number"
                            value="{{ old('delivery_fee', $settings->delivery_fee) }}"
                        >
                        @error('delivery_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface" for="min_order_amount">الحد الأدنى لقيمة الطلب (ج.م) <span class="text-error">*</span></label>
                        <input
                            @class([
                                'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                                'border-red-500' => $errors->has('min_order_amount'),
                            ])
                            id="min_order_amount"
                            min="0"
                            name="min_order_amount"
                            required
                            step="0.01"
                            type="number"
                            value="{{ old('min_order_amount', $settings->min_order_amount) }}"
                        >
                        @error('min_order_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <button class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-8 py-3 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" type="submit">
                <span class="material-symbols-outlined text-[18px]">save</span>
                حفظ
            </button>
        </div>
    </form>
@endsection
