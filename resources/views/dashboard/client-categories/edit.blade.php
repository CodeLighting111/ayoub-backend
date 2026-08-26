@extends('dashboard.layouts.app')

@section('title', 'تعديل فئة عملاء')

@section('breadcrumb', 'فئات العملاء / تعديل')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل فئة العملاء</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">حدّث عنوان الفئة كما سيظهر عند إضافة العملاء.</p>
    </div>

    @include('dashboard.client-categories._form', [
        'action' => route('admin.client-categories.update', $category),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'category' => $category,
    ])
@endsection
