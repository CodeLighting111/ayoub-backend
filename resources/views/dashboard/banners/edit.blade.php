@extends('dashboard.layouts.app')

@section('title', 'تعديل بانر')

@section('breadcrumb', 'البانرات / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل بانر',
        'subtitle' => 'قم بتحديث صورة البانر والعنوان والوصف.',
    ])

    @include('dashboard.banners._form', [
        'action' => route('admin.banners.update', $banner),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التغييرات',
        'banner' => $banner,
    ])
@endsection
