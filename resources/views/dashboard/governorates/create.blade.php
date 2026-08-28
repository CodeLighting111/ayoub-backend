@extends('dashboard.layouts.app')

@section('title', 'إضافة محافظة')

@section('breadcrumb', 'المحافظات / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة محافظة جديدة',
        'subtitle' => 'قم بإدخال بيانات المحافظة الجديدة لإضافتها إلى النظام.',
    ])

    @include('dashboard.governorates._form', [
        'action' => route('admin.governorates.store'),
        'submitLabel' => 'حفظ',
        'governorate' => $governorate,
    ])
@endsection
