@extends('dashboard.layouts.app')

@section('title', 'تعديل عميل')

@section('breadcrumb', 'العملاء / تعديل')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل بيانات العميل</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">حدّث بيانات العميل كما ستظهر في النظام.</p>
    </div>

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
