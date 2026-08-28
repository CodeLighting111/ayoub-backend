@extends('dashboard.layouts.app')

@section('title', 'تعديل فئة عملاء')

@section('breadcrumb', 'فئات العملاء / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل فئة العملاء',
        'subtitle' => 'حدّث عنوان الفئة كما سيظهر عند إضافة العملاء.',
    ])

    @include('dashboard.client-categories._form', [
        'action' => route('admin.client-categories.update', $category),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'category' => $category,
    ])
@endsection
