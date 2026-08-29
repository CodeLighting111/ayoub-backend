@extends('dashboard.layouts.app')

@section('title', 'عنا')

@section('breadcrumb', 'عنا')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل صفحة عنا</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">قم بتحديث المعلومات والصورة التي تظهر للعملاء في صفحة التعريف بالشركة.</p>
    </div>

    <form action="{{ route('admin.about.update') }}" class="grid grid-cols-1 gap-6 xl:grid-cols-3" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PUT')

        <div class="dashboard-card space-y-6 rounded-xl border border-outline-variant bg-surface-container-lowest p-6 xl:col-span-2">
            <div>
                <label class="mb-2 block text-sm font-semibold text-on-surface" for="title">العنوان <span class="text-error">*</span></label>
                <input
                    @class([
                        'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('title'),
                    ])
                    id="title"
                    name="title"
                    placeholder="أدخل عنوان القسم الرئيسي"
                    required
                    type="text"
                    value="{{ old('title', $about->title) }}"
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-on-surface" for="description">الوصف <span class="text-error">*</span></label>
                <textarea
                    @class([
                        'block min-h-64 w-full resize-y rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('description'),
                    ])
                    id="description"
                    name="description"
                    placeholder="اكتب وصفاً عن شركتك، رؤيتك، وقيمك..."
                    required
                >{{ old('description', $about->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="space-y-6">
            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                <h2 class="mb-4 text-lg font-semibold text-on-surface">صورة القسم</h2>

                @if ($about->image_url)
                    <div class="mb-4 flex min-h-48 w-full items-center justify-center rounded-lg border border-outline-variant bg-surface-container p-4">
                        <img alt="{{ $about->title }}" class="max-h-64 w-full object-contain" src="{{ asset(ltrim($about->image_url, '/')) }}">
                    </div>
                    <p class="mb-3 text-xs text-on-surface-variant">اترك الحقل فارغاً للاحتفاظ بالصورة الحالية.</p>
                @endif

                <label class="flex cursor-pointer justify-center rounded-xl border-2 border-dashed border-outline-variant bg-surface px-6 py-10 transition-colors hover:bg-surface-container-low" for="image">
                    <div class="text-center">
                        <span class="material-symbols-outlined mb-4 text-4xl text-primary-container">cloud_upload</span>
                        <div class="text-sm leading-6 text-on-surface-variant">
                            <span class="font-semibold text-primary-container">اضغط لرفع صورة</span>
                            <span class="pr-1">أو اسحب وأفلت هنا</span>
                        </div>
                        <p class="mt-2 text-xs text-on-surface-variant">PNG, JPG, GIF, WEBP حتى 10 ميجابايت</p>
                    </div>
                    <input accept="image/*" class="sr-only" id="image" name="image" type="file">
                </label>
                @error('image')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary-container px-8 py-3 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" type="submit">
                <span class="material-symbols-outlined text-[18px]">save</span>
                حفظ
            </button>
        </div>
    </form>
@endsection
