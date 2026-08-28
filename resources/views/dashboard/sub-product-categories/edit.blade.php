@extends('dashboard.layouts.app')

@section('title', 'تعديل فئة فرعية')

@section('breadcrumb', 'فئات المنتجات الفرعية / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل فئة المنتجات الفرعية',
        'subtitle' => 'حدّث الفئة الرئيسية وعنوان الفئة الفرعية.',
    ])

    @include('dashboard.sub-product-categories._form', [
        'action' => route('admin.sub-product-categories.update', $category),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'category' => $category,
        'mainCategories' => $mainCategories,
    ])
@endsection
