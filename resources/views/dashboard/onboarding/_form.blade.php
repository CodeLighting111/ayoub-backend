@php
    $isEdit = $screen->exists;
    $cancelUrl = $isEdit
        ? route('admin.onboarding.show', $screen)
        : route('admin.onboarding.index');
@endphp

<div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-5">
    <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6 lg:col-span-3">
        <form action="{{ $action }}" class="space-y-6" enctype="multipart/form-data" method="POST">
            @csrf
            @if ($method ?? false)
                @method($method)
            @endif

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="image">
                    صورة الشاشة @if (! $isEdit)<span class="text-error">*</span>@endif
                </label>
                @if ($isEdit && $screen->image_url)
                    <p class="text-xs text-on-surface-variant">اترك الحقل فارغاً للاحتفاظ بالصورة الحالية.</p>
                @endif
                <label class="mt-2 flex cursor-pointer justify-center rounded-xl border-2 border-dashed border-outline-variant bg-surface px-6 py-10 transition-colors hover:bg-surface-container-low" for="image">
                    <div class="text-center">
                        <span class="material-symbols-outlined mb-4 text-4xl text-primary-container">cloud_upload</span>
                        <div class="flex justify-center text-sm leading-6 text-on-surface-variant">
                            <span class="font-semibold text-primary-container">{{ $isEdit ? 'اضغط لرفع صورة جديدة' : 'اضغط لرفع صورة' }}</span>
                            <span class="pr-1">أو اسحب وأفلت هنا</span>
                        </div>
                        <p class="mt-2 text-xs text-on-surface-variant">PNG, JPG, GIF, WEBP حتى 10 ميجابايت</p>
                    </div>
                    <input @class([
                        'sr-only',
                        'border-red-500' => $errors->has('image'),
                    ]) id="image" name="image" type="file" accept="image/*" @if (! $isEdit) required @endif>
                </label>
                @error('image')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-on-surface" for="title">العنوان الرئيسي <span class="text-error">*</span></label>
                    <input
                        @class([
                            'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                            'border-red-500' => $errors->has('title'),
                        ])
                        id="title"
                        name="title"
                        placeholder="أدخل عنوان الشاشة"
                        required
                        type="text"
                        value="{{ old('title', $screen->title) }}"
                    >
                    @error('title')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-on-surface" for="sort_order">ترتيب الشاشة <span class="text-error">*</span></label>
                    <select
                        @class([
                            'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                            'border-red-500' => $errors->has('sort_order'),
                        ])
                        id="sort_order"
                        name="sort_order"
                        required
                    >
                        @foreach ($sortOptions as $order)
                            <option @selected((int) old('sort_order', $screen->sort_order) === $order) value="{{ $order }}">
                                الشاشة رقم ({{ $order }})
                            </option>
                        @endforeach
                    </select>
                    @error('sort_order')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="status">حالة الشاشة <span class="text-error">*</span></label>
                <select
                    @class([
                        'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container md:max-w-xs',
                        'border-red-500' => $errors->has('status'),
                    ])
                    id="status"
                    name="status"
                    required
                >
                    <option @selected(old('status', $screen->status) === 'active') value="active">مفعل</option>
                    <option @selected(old('status', $screen->status) === 'draft') value="draft">غير مفعلة</option>
                </select>
                @error('status')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="description">الوصف الإيضاحي <span class="text-error">*</span></label>
                <textarea
                    @class([
                        'block w-full resize-y rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('description'),
                    ])
                    id="description"
                    name="description"
                    placeholder="اكتب وصفاً موجزاً يظهر أسفل العنوان..."
                    required
                    rows="4"
                >{{ old('description', $screen->description) }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-outline-variant">

            <div class="flex items-center justify-end gap-4 pt-2">
                <a class="rounded-lg px-6 py-2.5 text-sm font-semibold text-on-surface-variant transition-colors hover:bg-surface-container" href="{{ $cancelUrl }}">
                    إلغاء
                </a>
                <button class="flex items-center gap-2 rounded-lg bg-primary-container px-8 py-2.5 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" type="submit">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    {{ $submitLabel }}
                </button>
            </div>
        </form>
    </div>

    <div class="lg:col-span-2">
        @include('dashboard.onboarding._preview', [
            'screen' => $screen,
            'previewHeading' => 'معاينة مباشرة',
            'previewSubtitle' => 'تعكس التغييرات كيف ستظهر الشاشة في التطبيق.',
            'previewTitle' => old('title', $screen->title) ?: 'عنوان الشاشة',
            'previewDescription' => old('description', $screen->description) ?: 'سيظهر الوصف الإيضاحي هنا.',
            'previewOrder' => old('sort_order', $screen->sort_order),
        ])
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('description');
        const orderInput = document.getElementById('sort_order');
        const imageInput = document.getElementById('image');
        const previewTitle = document.querySelector('[data-preview-title]');
        const previewDescription = document.querySelector('[data-preview-description]');
        const previewImage = document.querySelector('[data-preview-image]');
        const previewPlaceholder = document.querySelector('[data-preview-image-placeholder]');
        const previewDots = document.querySelector('[data-preview-dots]');

        if (titleInput && previewTitle) {
            titleInput.addEventListener('input', function () {
                previewTitle.textContent = titleInput.value.trim() || 'عنوان الشاشة';
            });
        }

        if (descriptionInput && previewDescription) {
            descriptionInput.addEventListener('input', function () {
                previewDescription.textContent = descriptionInput.value.trim() || 'سيظهر الوصف الإيضاحي هنا.';
            });
        }

        if (orderInput && previewDots) {
            orderInput.addEventListener('change', function () {
                const order = Math.min(Math.max(parseInt(orderInput.value, 10) || 1, 1), 3);
                previewDots.querySelectorAll('span').forEach(function (dot, index) {
                    const isActive = index + 1 === order;
                    dot.className = isActive
                        ? 'h-2 rounded-full w-8 bg-primary-container'
                        : 'h-2 rounded-full w-2 bg-surface-variant';
                });
            });
        }

        if (imageInput && previewImage && previewPlaceholder) {
            imageInput.addEventListener('change', function () {
                const file = imageInput.files && imageInput.files[0];
                if (!file) {
                    return;
                }
                const url = URL.createObjectURL(file);
                previewImage.src = url;
                previewImage.style.display = 'block';
                previewPlaceholder.style.display = 'none';
            });
        }
    })();
</script>
@endpush
