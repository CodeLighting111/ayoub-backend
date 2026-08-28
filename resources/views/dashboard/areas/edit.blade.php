@extends('dashboard.layouts.app')

@section('title', 'تعديل منطقة')

@section('breadcrumb', 'المناطق / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل المنطقة',
        'subtitle' => 'حدّث المدينة واسم المنطقة.',
    ])

    @include('dashboard.areas._form', [
        'action' => route('admin.areas.update', $area),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'area' => $area,
        'cities' => $cities,
    ])
@endsection
