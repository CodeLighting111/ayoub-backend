@php
    $previewTitle = $previewTitle ?? ($screen->title ?: 'عنوان الشاشة');
    $previewDescription = $previewDescription ?? ($screen->description ?: 'سيظهر الوصف الإيضاحي هنا.');
    $previewImage = $previewImage ?? $screen->image_url;
    $previewOrder = (int) ($previewOrder ?? $screen->sort_order ?? 1);
    $activeDot = min(max($previewOrder, 1), 3);
@endphp

<div class="dashboard-card rounded-xl border border-outline-variant/40 bg-surface-container-lowest p-6 {{ ($sticky ?? true) ? 'lg:sticky lg:top-24' : '' }}">
    <h2 class="mb-1 flex items-center gap-2 text-lg font-semibold text-primary-container">
        <span class="material-symbols-outlined">smartphone</span>
        {{ $previewHeading ?? 'معاينة التطبيق' }}
    </h2>
    <p class="mb-6 text-sm text-on-surface-variant">{{ $previewSubtitle ?? 'كيف تظهر هذه الشاشة للمستخدم الجديد.' }}</p>

    <div class="flex justify-center">
        <div class="flex h-[560px] w-[270px] flex-col overflow-hidden rounded-[36px] border-[10px] border-on-surface bg-surface shadow-[0px_10px_30px_rgba(20,83,45,0.08)]">
            <div class="flex items-center justify-between px-4 pb-2 pt-5">
                <span class="text-xs font-medium text-on-surface-variant">تخطي</span>
                <span class="text-sm font-semibold text-primary-container">ايوب جملة</span>
                <span class="w-10"></span>
            </div>
            <div class="flex flex-1 flex-col px-4 pt-2">
                <div class="relative min-h-0 flex-[5] overflow-hidden rounded-[28px] bg-surface-container">
                    <img
                        alt="معاينة صورة الشاشة"
                        class="h-full w-full object-cover"
                        data-preview-image
                        src="{{ $previewImage ?: '' }}"
                        style="display: {{ filled($previewImage) ? 'block' : 'none' }};"
                    >
                    <div
                        class="absolute inset-0 flex items-center justify-center"
                        data-preview-image-placeholder
                        style="display: {{ filled($previewImage) ? 'none' : 'flex' }};"
                    >
                        <span class="material-symbols-outlined text-5xl text-outline-variant">image</span>
                    </div>
                </div>
                <div class="pb-3 pt-5 text-center">
                    <h3 class="mb-2 text-lg font-semibold text-primary-container" data-preview-title>{{ $previewTitle }}</h3>
                    <p class="text-sm leading-6 text-on-surface-variant" data-preview-description>{{ $previewDescription }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between px-4 pb-6 pt-2">
                <div class="flex items-center gap-1" data-preview-dots>
                    @for ($i = 1; $i <= 3; $i++)
                        <span class="h-2 rounded-full {{ $i === $activeDot ? 'w-8 bg-primary-container' : 'w-2 bg-surface-variant' }}"></span>
                    @endfor
                </div>
                <span class="inline-flex items-center gap-1 rounded-full bg-primary-container px-5 py-2 text-sm font-semibold text-on-primary">
                    التالي
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </span>
            </div>
        </div>
    </div>
</div>
