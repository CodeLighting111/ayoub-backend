@extends('dashboard.layouts.app')

@section('title', 'تعديل منتج')

@section('breadcrumb', 'المنتجات / تعديل')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل المنتج</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">حدّث بيانات المنتج.</p>
    </div>

    @include('dashboard.products._form', [
        'action' => route('admin.products.update', $product),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'product' => $product,
        'brands' => $brands,
        'mainCategories' => $mainCategories,
        'subCategories' => $subCategories,
    ])
@endsection
