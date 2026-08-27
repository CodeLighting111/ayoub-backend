@extends('dashboard.layouts.app')

@section('title', 'إضافة فئة فرعية')

@section('breadcrumb', 'فئات المنتجات الفرعية / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة فئة منتجات فرعية</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">الرجاء اختيار الفئة الرئيسية وإدخال عنوان الفئة الفرعية.</p>
    </div>

    @include('dashboard.sub-product-categories._form', [
        'action' => route('admin.sub-product-categories.store'),
        'submitLabel' => 'حفظ',
        'category' => $category,
        'mainCategories' => $mainCategories,
    ])
@endsection
