@extends('dashboard.layouts.app')

@section('title', 'تعديل محافظة')

@section('breadcrumb', 'المحافظات / تعديل')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل المحافظة</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">حدّث اسم المحافظة كما سيظهر عند إضافة العملاء والمدن.</p>
    </div>

    @include('dashboard.governorates._form', [
        'action' => route('admin.governorates.update', $governorate),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التعديلات',
        'governorate' => $governorate,
    ])
@endsection
