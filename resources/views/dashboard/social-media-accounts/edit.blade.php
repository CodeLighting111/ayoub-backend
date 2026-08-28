@extends('dashboard.layouts.app')

@section('title', 'تعديل حساب سوشيال ميديا')

@section('breadcrumb', 'حسابات السوشيال ميديا / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل حساب سوشيال ميديا',
        'subtitle' => 'تحديث بيانات حساب «'.$account->name.'».',
    ])

    @include('dashboard.social-media-accounts._form', [
        'action' => route('admin.social-media-accounts.update', $account),
        'method' => 'PUT',
        'submitLabel' => 'تحديث',
        'account' => $account,
    ])
@endsection
