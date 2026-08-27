@extends('dashboard.layouts.app')

@section('title', 'إضافة محافظة')

@section('breadcrumb', 'المحافظات / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة محافظة جديدة</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">قم بإدخال بيانات المحافظة الجديدة لإضافتها إلى النظام.</p>
    </div>

    @include('dashboard.governorates._form', [
        'action' => route('admin.governorates.store'),
        'submitLabel' => 'حفظ',
        'governorate' => $governorate,
    ])
@endsection
