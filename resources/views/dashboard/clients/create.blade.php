@extends('dashboard.layouts.app')

@section('title', 'إضافة عميل')

@section('breadcrumb', 'العملاء / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة عميل جديد</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">أدخل تفاصيل العميل الجديد لتسجيله في النظام.</p>
    </div>

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
