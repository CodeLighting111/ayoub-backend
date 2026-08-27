@extends('dashboard.layouts.app')

@section('title', 'تعديل دور')

@section('breadcrumb', 'الأدوار / تعديل')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">تعديل الدور</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">تحديث بيانات الدور «{{ $role->name }}».</p>
    </div>

    @include('dashboard.roles._form', [
        'action' => route('admin.roles.update', $role),
        'method' => 'PUT',
        'submitLabel' => 'حفظ التغييرات',
        'role' => $role,
        'permissions' => $permissions,
        'assignedPermissionIds' => $assignedPermissionIds,
    ])
@endsection
