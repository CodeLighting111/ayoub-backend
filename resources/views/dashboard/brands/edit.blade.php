@extends('dashboard.layouts.app')

@section('title', 'تعديل علامة تجارية')

@section('breadcrumb', 'العلامات التجارية / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل العلامة التجارية',
        'subtitle' => 'حدّث اسم العلامة التجارية.',
    ])

    @include('dashboard.brands._form', [
        'action' => route('admin.brands.update', $brand),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'brand' => $brand,
    ])
@endsection
