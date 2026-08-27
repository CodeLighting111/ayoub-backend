@php
    $isEdit = $product->exists;
    $cancelUrl = route('admin.products.index');
    $selectedMainCategoryId = (int) old('main_product_category_id', $product->subCategory?->main_product_category_id);
@endphp

<div class="dashboard-card w-full rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
    <form action="{{ $action }}" class="space-y-6" enctype="multipart/form-data" method="POST">
        @csrf
        @if ($method ?? false)
            @method($method)
        @endif

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-semibold text-on-surface" for="name">اسم المنتج <span class="text-error">*</span></label>
                <input
                    @class([
                        'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('name'),
                    ])
                    id="name"
                    name="name"
                    placeholder="مثال: مياه معدنية 600 مل"
                    required
                    type="text"
                    value="{{ old('name', $product->name) }}"
                >
                @error('name')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="brand_id">العلامة التجارية <span class="text-error">*</span></label>
                <select
                    @class([
                        'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('brand_id'),
                    ])
                    id="brand_id"
                    name="brand_id"
                    required
                >
                    <option disabled @selected(! old('brand_id', $product->brand_id)) value="">اختر العلامة التجارية</option>
                    @foreach ($brands as $brand)
                        <option @selected((int) old('brand_id', $product->brand_id) === $brand->id) value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="status">الحالة <span class="text-error">*</span></label>
                <select
                    @class([
                        'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('status'),
                    ])
                    id="status"
                    name="status"
                    required
                >
                    <option @selected(old('status', $product->status) === 'active') value="active">نشط</option>
                    <option @selected(old('status', $product->status) === 'inactive') value="inactive">غير نشط</option>
                </select>
                @error('status')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="main_product_category_id">الفئة الرئيسية <span class="text-error">*</span></label>
                <select
                    @class([
                        'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('main_product_category_id'),
                    ])
                    id="main_product_category_id"
                    name="main_product_category_id"
                    required
                >
                    <option disabled @selected(! $selectedMainCategoryId) value="">اختر الفئة الرئيسية</option>
                    @foreach ($mainCategories as $mainCategory)
                        <option @selected($selectedMainCategoryId === $mainCategory->id) value="{{ $mainCategory->id }}">{{ $mainCategory->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="sub_product_category_id">الفئة الفرعية <span class="text-error">*</span></label>
                <select
                    @class([
                        'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('sub_product_category_id'),
                    ])
                    id="sub_product_category_id"
                    name="sub_product_category_id"
                    required
                >
                    <option disabled @selected(! old('sub_product_category_id', $product->sub_product_category_id)) value="">اختر الفئة الفرعية</option>
                </select>
                @error('sub_product_category_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="price">السعر (ج.م) <span class="text-error">*</span></label>
                <input
                    @class([
                        'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('price'),
                    ])
                    id="price"
                    min="0"
                    name="price"
                    placeholder="0.00"
                    required
                    step="0.01"
                    type="number"
                    value="{{ old('price', $product->price) }}"
                >
                @error('price')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="discount_price">السعر بعد الخصم (ج.م)</label>
                <input
                    @class([
                        'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('discount_price'),
                    ])
                    id="discount_price"
                    min="0"
                    name="discount_price"
                    placeholder="اختياري"
                    step="0.01"
                    type="number"
                    value="{{ old('discount_price', $product->discount_price) }}"
                >
                @error('discount_price')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="pieces">عدد القطع <span class="text-error">*</span></label>
                <input
                    @class([
                        'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('pieces'),
                    ])
                    id="pieces"
                    min="1"
                    name="pieces"
                    required
                    type="number"
                    value="{{ old('pieces', $product->pieces ?? 1) }}"
                >
                @error('pieces')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="stock">المخزون <span class="text-error">*</span></label>
                <input
                    @class([
                        'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('stock'),
                    ])
                    id="stock"
                    min="0"
                    name="stock"
                    required
                    type="number"
                    value="{{ old('stock', $product->stock ?? 0) }}"
                >
                @error('stock')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="block text-sm font-semibold text-on-surface" for="description">الوصف</label>
                <textarea
                    @class([
                        'block w-full resize-y rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('description'),
                    ])
                    id="description"
                    name="description"
                    placeholder="اكتب وصفاً للمنتج..."
                    rows="4"
                >{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-on-surface" for="image">
                صورة المنتج @if (! $isEdit)<span class="text-error">*</span>@endif
            </label>
            @if ($isEdit && $product->image_url)
                <div class="mb-3">
                    <img alt="{{ $product->name }}" class="h-20 w-20 rounded-lg border border-outline-variant object-cover" src="{{ asset(ltrim($product->image_url, '/')) }}">
                    <p class="mt-2 text-xs text-on-surface-variant">اترك الحقل فارغاً للاحتفاظ بالصورة الحالية.</p>
                </div>
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
                <input
                    @class([
                        'sr-only',
                        'border-red-500' => $errors->has('image'),
                    ])
                    id="image"
                    name="image"
                    type="file"
                    accept="image/*"
                    @if (! $isEdit) required @endif
                >
            </label>
            @error('image')
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

@push('scripts')
<script>
    (function () {
        const subCategories = @json($subCategories);
        const selectedMainCategoryId = @json($selectedMainCategoryId);
        const selectedSubCategoryId = @json((int) old('sub_product_category_id', $product->sub_product_category_id));

        const mainCategorySelect = document.getElementById('main_product_category_id');
        const subCategorySelect = document.getElementById('sub_product_category_id');

        function fillSubCategories(mainCategoryId, selectedId) {
            if (!subCategorySelect) {
                return;
            }

            subCategorySelect.innerHTML = '';

            const placeholderOption = document.createElement('option');
            placeholderOption.value = '';
            placeholderOption.textContent = 'اختر الفئة الفرعية';
            placeholderOption.disabled = true;
            placeholderOption.selected = !selectedId;
            subCategorySelect.appendChild(placeholderOption);

            subCategories
                .filter(function (category) {
                    return Number(category.main_product_category_id) === Number(mainCategoryId);
                })
                .forEach(function (category) {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.title;
                    option.selected = Number(selectedId) === Number(category.id);
                    subCategorySelect.appendChild(option);
                });
        }

        if (mainCategorySelect && subCategorySelect) {
            mainCategorySelect.addEventListener('change', function () {
                fillSubCategories(mainCategorySelect.value, '');
            });

            if (selectedMainCategoryId) {
                fillSubCategories(selectedMainCategoryId, selectedSubCategoryId);
            }
        }
    })();
</script>
@endpush
