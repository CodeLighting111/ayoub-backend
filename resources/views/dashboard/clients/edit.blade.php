@extends('dashboard.layouts.app')

@section('title', 'تعديل عميل')

@section('breadcrumb', 'العملاء / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل بيانات العميل',
        'subtitle' => 'حدّث بيانات العميل كما ستظهر في النظام.',
    ])

    @include('dashboard.clients._form', [
        'action' => route('admin.clients.update', $client),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'client' => $client,
        'categories' => $categories,
        'governorates' => $governorates,
        'cities' => $cities,
        'areas' => $areas,
    ])
@endsection
