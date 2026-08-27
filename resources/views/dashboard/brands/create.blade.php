@extends('dashboard.layouts.app')

@section('title', 'إضافة علامة تجارية')

@section('breadcrumb', 'العلامات التجارية / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة علامة تجارية</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">أدخل اسم العلامة التجارية الجديدة لإضافتها إلى النظام.</p>
    </div>

    @include('dashboard.brands._form', [
        'action' => route('admin.brands.store'),
        'submitLabel' => 'حفظ',
        'brand' => $brand,
    ])
@endsection
