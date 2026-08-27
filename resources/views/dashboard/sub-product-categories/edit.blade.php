@extends('dashboard.layouts.app')

@section('title', 'تعديل فئة فرعية')

@section('breadcrumb', 'فئات المنتجات الفرعية / تعديل')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل فئة المنتجات الفرعية</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">حدّث الفئة الرئيسية وعنوان الفئة الفرعية.</p>
    </div>

    @include('dashboard.sub-product-categories._form', [
        'action' => route('admin.sub-product-categories.update', $category),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'category' => $category,
        'mainCategories' => $mainCategories,
    ])
@endsection
