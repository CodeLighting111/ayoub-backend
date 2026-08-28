@extends('dashboard.layouts.app')

@section('title', 'إضافة دور')

@section('breadcrumb', 'الأدوار / إضافة')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إضافة دور جديد',
        'subtitle' => 'قم بتعريف دور جديد وتحديد أذوناته.',
    ])

    @include('dashboard.roles._form', [
        'action' => route('admin.roles.store'),
        'submitLabel' => 'حفظ',
        'role' => $role,
        'permissions' => $permissions,
        'assignedPermissionIds' => $assignedPermissionIds,
    ])
@endsection
