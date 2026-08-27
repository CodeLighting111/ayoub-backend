@extends('dashboard.layouts.app')

@section('title', 'إضافة دور')

@section('breadcrumb', 'الأدوار / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة دور جديد</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">قم بتعريف دور جديد وتحديد أذوناته.</p>
    </div>

    @include('dashboard.roles._form', [
        'action' => route('admin.roles.store'),
        'submitLabel' => 'حفظ',
        'role' => $role,
        'permissions' => $permissions,
        'assignedPermissionIds' => $assignedPermissionIds,
    ])
@endsection
