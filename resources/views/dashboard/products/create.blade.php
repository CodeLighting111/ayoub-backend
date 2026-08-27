@extends('dashboard.layouts.app')

@section('title', 'إضافة منتج')

@section('breadcrumb', 'المنتجات / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة منتج</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">أدخل بيانات المنتج الجديد لإضافته إلى المتجر.</p>
    </div>

    @include('dashboard.products._form', [
        'action' => route('admin.products.store'),
        'submitLabel' => 'حفظ',
        'product' => $product,
        'brands' => $brands,
        'mainCategories' => $mainCategories,
        'subCategories' => $subCategories,
    ])
@endsection
