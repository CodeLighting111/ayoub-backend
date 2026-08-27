@extends('dashboard.layouts.app')

@section('title', 'تعديل فئة منتجات رئيسية')

@section('breadcrumb', 'فئات المنتجات الرئيسية / تعديل')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل فئة المنتجات الرئيسية</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">حدّث عنوان الفئة كما سيظهر عند تصنيف المنتجات.</p>
    </div>

    @include('dashboard.main-product-categories._form', [
        'action' => route('admin.main-product-categories.update', $category),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'category' => $category,
    ])
@endsection
