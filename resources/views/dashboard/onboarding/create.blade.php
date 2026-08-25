@extends('dashboard.layouts.app')

@section('title', 'إضافة شاشة ترحيب')

@section('breadcrumb', 'الصفحات الابتدائية / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة شاشة ترحيب جديدة</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">قم بإنشاء وتكوين شاشة ترحيب جديدة لتظهر للمستخدمين الجدد.</p>
    </div>

    @include('dashboard.onboarding._form', [
        'action' => route('admin.onboarding.store'),
        'submitLabel' => 'حفظ الشاشة',
        'screen' => $screen,
        'sortOptions' => $sortOptions,
    ])
@endsection
