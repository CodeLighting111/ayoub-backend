@extends('dashboard.layouts.app')

@section('title', 'إضافة عميل')

@section('breadcrumb', 'العملاء / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة عميل جديد',
        'subtitle' => 'أدخل تفاصيل العميل الجديد لتسجيله في النظام.',
    ])

    @include('dashboard.clients._form', [
        'action' => route('admin.clients.store'),
        'submitLabel' => 'حفظ العميل',
        'client' => $client,
        'categories' => $categories,
        'governorates' => $governorates,
        'cities' => $cities,
        'areas' => $areas,
    ])
@endsection
