@extends('dashboard.layouts.app')

@section('title', 'تعديل حساب سوشيال ميديا')

@section('breadcrumb', 'حسابات السوشيال ميديا / تعديل')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل حساب سوشيال ميديا</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">تحديث بيانات حساب «{{ $account->name }}».</p>
    </div>

    @include('dashboard.social-media-accounts._form', [
        'action' => route('admin.social-media-accounts.update', $account),
        'method' => 'PUT',
        'submitLabel' => 'تحديث',
        'account' => $account,
    ])
@endsection
