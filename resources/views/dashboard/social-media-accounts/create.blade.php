@extends('dashboard.layouts.app')

@section('title', 'إضافة حساب سوشيال ميديا')

@section('breadcrumb', 'حسابات السوشيال ميديا / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة حساب سوشيال ميديا</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">أضف اسم الحساب ورابطه وصورته أو أيقونته.</p>
    </div>

    @include('dashboard.social-media-accounts._form', [
        'action' => route('admin.social-media-accounts.store'),
        'submitLabel' => 'حفظ',
        'account' => $account,
    ])
@endsection
