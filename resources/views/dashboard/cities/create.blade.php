@extends('dashboard.layouts.app')

@section('title', 'إضافة مدينة')

@section('breadcrumb', 'المدن / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة مدينة جديدة</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">الرجاء إدخال تفاصيل المدينة الجديدة المراد إضافتها إلى النظام.</p>
    </div>

    @include('dashboard.cities._form', [
        'action' => route('admin.cities.store'),
        'submitLabel' => 'حفظ',
        'city' => $city,
        'governorates' => $governorates,
    ])
@endsection
