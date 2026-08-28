@extends('dashboard.layouts.app')

@section('title', 'إضافة حساب سوشيال ميديا')

@section('breadcrumb', 'حسابات السوشيال ميديا / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة حساب سوشيال ميديا',
        'subtitle' => 'أضف اسم الحساب ورابطه وصورته أو أيقونته.',
    ])

    @include('dashboard.social-media-accounts._form', [
        'action' => route('admin.social-media-accounts.store'),
        'submitLabel' => 'حفظ',
        'account' => $account,
    ])
@endsection
