@extends('dashboard.layouts.app')

@section('title', 'تعديل مدينة')

@section('breadcrumb', 'المدن / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل المدينة',
        'subtitle' => 'حدّث المحافظة واسم المدينة.',
    ])

    @include('dashboard.cities._form', [
        'action' => route('admin.cities.update', $city),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'city' => $city,
        'governorates' => $governorates,
    ])
@endsection
