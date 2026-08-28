@extends('dashboard.layouts.app')

@section('title', 'إضافة مدينة')

@section('breadcrumb', 'المدن / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة مدينة جديدة',
        'subtitle' => 'الرجاء إدخال تفاصيل المدينة الجديدة المراد إضافتها إلى النظام.',
    ])

    @include('dashboard.cities._form', [
        'action' => route('admin.cities.store'),
        'submitLabel' => 'حفظ',
        'city' => $city,
        'governorates' => $governorates,
    ])
@endsection
