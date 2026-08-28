@extends('dashboard.layouts.app')

@section('title', 'إضافة فئة منتجات رئيسية')

@section('breadcrumb', 'فئات المنتجات الرئيسية / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة فئة منتجات رئيسية',
        'subtitle' => 'أدخل عنوان الفئة الجديدة لتصنيف المنتجات في المتجر.',
    ])

    @include('dashboard.main-product-categories._form', [
        'action' => route('admin.main-product-categories.store'),
        'submitLabel' => 'حفظ',
        'category' => $category,
    ])
@endsection
