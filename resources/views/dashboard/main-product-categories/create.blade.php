@extends('dashboard.layouts.app')

@section('title', 'إضافة فئة منتجات رئيسية')

@section('breadcrumb', 'فئات المنتجات الرئيسية / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة فئة منتجات رئيسية</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">أدخل عنوان الفئة الجديدة لتصنيف المنتجات في المتجر.</p>
    </div>

    @include('dashboard.main-product-categories._form', [
        'action' => route('admin.main-product-categories.store'),
        'submitLabel' => 'حفظ',
        'category' => $category,
    ])
@endsection
