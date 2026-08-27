@extends('dashboard.layouts.app')

@section('title', 'تعديل مدينة')

@section('breadcrumb', 'المدن / تعديل')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل المدينة</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">حدّث المحافظة واسم المدينة.</p>
    </div>

    @include('dashboard.cities._form', [
        'action' => route('admin.cities.update', $city),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'city' => $city,
        'governorates' => $governorates,
    ])
@endsection
