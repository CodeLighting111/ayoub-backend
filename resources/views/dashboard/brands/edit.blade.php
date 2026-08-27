@extends('dashboard.layouts.app')

@section('title', 'تعديل علامة تجارية')

@section('breadcrumb', 'العلامات التجارية / تعديل')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل العلامة التجارية</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">حدّث اسم العلامة التجارية.</p>
    </div>

    @include('dashboard.brands._form', [
        'action' => route('admin.brands.update', $brand),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'brand' => $brand,
    ])
@endsection
