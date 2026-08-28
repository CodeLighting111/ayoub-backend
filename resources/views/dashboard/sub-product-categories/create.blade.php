@extends('dashboard.layouts.app')

@section('title', 'إضافة فئة فرعية')

@section('breadcrumb', 'فئات المنتجات الفرعية / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة فئة منتجات فرعية',
        'subtitle' => 'الرجاء اختيار الفئة الرئيسية وإدخال عنوان الفئة الفرعية.',
    ])

    @include('dashboard.sub-product-categories._form', [
        'action' => route('admin.sub-product-categories.store'),
        'submitLabel' => 'حفظ',
        'category' => $category,
        'mainCategories' => $mainCategories,
    ])
@endsection
