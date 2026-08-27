@php
    $isEdit = $brand->exists;
    $cancelUrl = route('admin.brands.index');
@endphp

<div class="dashboard-card w-full rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
    <form action="{{ $action }}" class="space-y-6" enctype="multipart/form-data" method="POST">
        @csrf
        @if ($method ?? false)
            @method($method)
        @endif

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-on-surface" for="name">اسم العلامة التجارية <span class="text-error">*</span></label>
            <input
                @class([
                    'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                    'border-red-500' => $errors->has('name'),
                ])
                id="name"
                name="name"
                placeholder="مثال: بيبسي"
                required
                type="text"
                value="{{ old('name', $brand->name) }}"
            >
            @error('name')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-on-surface" for="image">
                صورة العلامة التجارية @if (! $isEdit)<span class="text-error">*</span>@endif
            </label>
            @if ($isEdit && $brand->image_url)
                <div class="mb-3">
                    <img alt="{{ $brand->name }}" class="h-20 w-20 rounded-lg border border-outline-variant object-cover" src="{{ asset(ltrim($brand->image_url, '/')) }}">
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
