@extends('dashboard.layouts.app')

@section('title', 'إضافة علامة تجارية')

@section('breadcrumb', 'العلامات التجارية / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة علامة تجارية',
        'subtitle' => 'أدخل اسم العلامة التجارية الجديدة لإضافتها إلى النظام.',
    ])

    @include('dashboard.brands._form', [
        'action' => route('admin.brands.store'),
        'submitLabel' => 'حفظ',
        'brand' => $brand,
    ])
@endsection
