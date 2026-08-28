@extends('dashboard.layouts.app')

@section('title', 'إضافة بانر')

@section('breadcrumb', 'البانرات / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة بانر',
        'subtitle' => 'أضف صورة البانر مع العنوان والوصف.',
    ])

    @include('dashboard.banners._form', [
        'action' => route('admin.banners.store'),
        'submitLabel' => 'حفظ',
        'banner' => $banner,
    ])
@endsection
