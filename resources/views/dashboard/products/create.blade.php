@extends('dashboard.layouts.app')

@section('title', 'إضافة منتج')

@section('breadcrumb', 'المنتجات / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة منتج',
        'subtitle' => 'أدخل بيانات المنتج الجديد لإضافته إلى المتجر.',
    ])

    @include('dashboard.products._form', [
        'action' => route('admin.products.store'),
        'submitLabel' => 'حفظ',
        'product' => $product,
        'brands' => $brands,
        'mainCategories' => $mainCategories,
        'subCategories' => $subCategories,
    ])
@endsection
