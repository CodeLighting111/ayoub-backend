@extends('dashboard.layouts.app')

@section('title', 'تعديل منطقة')

@section('breadcrumb', 'المناطق / تعديل')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل المنطقة</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">حدّث المدينة واسم المنطقة.</p>
    </div>

    @include('dashboard.areas._form', [
        'action' => route('admin.areas.update', $area),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'area' => $area,
        'cities' => $cities,
    ])
@endsection
