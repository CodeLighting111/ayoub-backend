@extends('dashboard.layouts.app')

@section('title', 'تعديل منتج')

@section('breadcrumb', 'المنتجات / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل المنتج',
        'subtitle' => 'حدّث بيانات المنتج.',
    ])

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
