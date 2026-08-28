@extends('dashboard.layouts.app')

@section('title', 'تعديل فئة منتجات رئيسية')

@section('breadcrumb', 'فئات المنتجات الرئيسية / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل فئة المنتجات الرئيسية',
        'subtitle' => 'حدّث عنوان الفئة كما سيظهر عند تصنيف المنتجات.',
    ])

    @include('dashboard.main-product-categories._form', [
        'action' => route('admin.main-product-categories.update', $category),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'category' => $category,
    ])
@endsection
