@extends('dashboard.layouts.app')

@section('title', 'إضافة شاشة ترحيب')

@section('breadcrumb', 'الصفحات الابتدائية / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة شاشة ترحيب جديدة',
        'subtitle' => 'قم بإنشاء وتكوين شاشة ترحيب جديدة لتظهر للمستخدمين الجدد.',
    ])

    @include('dashboard.onboarding._form', [
        'action' => route('admin.onboarding.store'),
        'submitLabel' => 'حفظ الشاشة',
        'screen' => $screen,
        'sortOptions' => $sortOptions,
    ])
@endsection
