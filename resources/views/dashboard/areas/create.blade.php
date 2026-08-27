@extends('dashboard.layouts.app')

@section('title', 'إضافة منطقة')

@section('breadcrumb', 'المناطق / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة منطقة جديدة</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">الرجاء إدخال تفاصيل المنطقة الجديدة المراد إضافتها إلى النظام.</p>
    </div>

    @include('dashboard.areas._form', [
        'action' => route('admin.areas.store'),
        'submitLabel' => 'حفظ',
        'area' => $area,
        'cities' => $cities,
    ])
@endsection
