@extends('dashboard.layouts.app')

@section('title', 'تعديل شاشة ترحيب')

@section('breadcrumb', 'الصفحات الابتدائية / تفاصيل / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل شاشة الترحيب',
        'subtitle' => 'قم بتحديث محتوى شاشة الترحيب كما تظهر في التطبيق.',
    ])

    @include('dashboard.onboarding._form', [
        'action' => route('admin.onboarding.update', $screen),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'screen' => $screen,
        'sortOptions' => $sortOptions,
    ])
@endsection
