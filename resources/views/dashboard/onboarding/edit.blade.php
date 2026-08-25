@extends('dashboard.layouts.app')

@section('title', 'تعديل شاشة ترحيب')

@section('breadcrumb', 'الصفحات الابتدائية / تفاصيل / تعديل')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل شاشة الترحيب</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">قم بتحديث محتوى شاشة الترحيب كما تظهر في التطبيق.</p>
    </div>

    @include('dashboard.onboarding._form', [
        'action' => route('admin.onboarding.update', $screen),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'screen' => $screen,
        'sortOptions' => $sortOptions,
    ])
@endsection
