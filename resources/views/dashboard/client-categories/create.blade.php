@extends('dashboard.layouts.app')

@section('title', 'إضافة فئة عملاء')

@section('breadcrumb', 'فئات العملاء / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة فئة عملاء جديدة',
        'subtitle' => 'قم بإدخال تفاصيل الفئة الجديدة لإضافتها إلى النظام.',
    ])

    @include('dashboard.client-categories._form', [
        'action' => route('admin.client-categories.store'),
        'submitLabel' => 'حفظ',
        'category' => $category,
    ])
@endsection
