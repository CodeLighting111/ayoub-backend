@extends('dashboard.layouts.app')

@section('title', 'تعديل محافظة')

@section('breadcrumb', 'المحافظات / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل المحافظة',
        'subtitle' => 'حدّث اسم المحافظة كما سيظهر عند إضافة العملاء والمدن.',
    ])

    @include('dashboard.governorates._form', [
        'action' => route('admin.governorates.update', $governorate),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'governorate' => $governorate,
    ])
@endsection
