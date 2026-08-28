@extends('dashboard.layouts.app')

@section('title', 'تعديل دور')

@section('breadcrumb', 'الأدوار / تعديل')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'تعديل الدور',
        'subtitle' => 'تحديث بيانات الدور «'.$role->name.'».',
    ])

    @include('dashboard.roles._form', [
        'action' => route('admin.roles.update', $role),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التغييرات',
        'role' => $role,
        'permissions' => $permissions,
        'assignedPermissionIds' => $assignedPermissionIds,
    ])
@endsection
