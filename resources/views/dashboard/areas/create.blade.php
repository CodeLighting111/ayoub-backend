@extends('dashboard.layouts.app')

@section('title', 'إضافة منطقة')

@section('breadcrumb', 'المناطق / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة منطقة جديدة',
        'subtitle' => 'الرجاء إدخال تفاصيل المنطقة الجديدة المراد إضافتها إلى النظام.',
    ])

    @include('dashboard.areas._form', [
        'action' => route('admin.areas.store'),
        'submitLabel' => 'حفظ',
        'area' => $area,
        'cities' => $cities,
    ])
@endsection
