@extends('dashboard.layouts.app')

@section('title', 'إضافة فئة عملاء')

@section('breadcrumb', 'فئات العملاء / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة فئة عملاء جديدة</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">قم بإدخال تفاصيل الفئة الجديدة لإضافتها إلى النظام.</p>
    </div>

    @include('dashboard.client-categories._form', [
        'action' => route('admin.client-categories.store'),
        'submitLabel' => 'حفظ',
        'category' => $category,
    ])
@endsection
