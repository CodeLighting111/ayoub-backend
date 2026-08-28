@extends('dashboard.layouts.app')

@section('title', 'إضافة مشرف')

@section('breadcrumb', 'المشرفين / إضافة')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">إضافة مشرف</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">إنشاء حساب مشرف جديد وربطه بدور محدد.</p>
    </div>

    <div class="dashboard-card w-full rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
        <form action="{{ route('admin.admins.store') }}" class="space-y-6" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-on-surface" for="name">اسم المشرف <span class="text-error">*</span></label>
                    <input
                        @class([
                            'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                            'border-red-500' => $errors->has('name'),
                        ])
                        id="name"
                        name="name"
                        required
                        type="text"
                        value="{{ old('name') }}"
                    >
                    @error('name')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-on-surface" for="email">البريد الإلكتروني <span class="text-error">*</span></label>
                    <input
                        @class([
                            'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                            'border-red-500' => $errors->has('email'),
                        ])
                        id="email"
                        name="email"
                        required
                        type="email"
                        value="{{ old('email') }}"
                    >
                    @error('email')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-on-surface" for="phone">رقم الهاتف</label>
                    <input
                        @class([
                            'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                            'border-red-500' => $errors->has('phone'),
                        ])
                        id="phone"
                        name="phone"
                        type="text"
                        value="{{ old('phone') }}"
                    >
                    @error('phone')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-on-surface" for="role_id">الدور <span class="text-error">*</span></label>
                    <select
                        @class([
                            'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                            'border-red-500' => $errors->has('role_id'),
                        ])
                        id="role_id"
                        name="role_id"
                        required
                    >
                        <option value="">اختر الدور</option>
                        @foreach ($roles as $role)
                            <option @selected((string) old('role_id') === (string) $role->id) value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2 md:col-span-2 md:max-w-xl">
                    <label class="block text-sm font-semibold text-on-surface" for="password">كلمة المرور <span class="text-error">*</span></label>
                    <input
                        @class([
                            'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                            'border-red-500' => $errors->has('password'),
                        ])
                        id="password"
                        name="password"
                        required
                        type="password"
                    >
                    <p class="text-xs text-on-surface-variant">8 أحرف على الأقل.</p>
                    @error('password')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-4 border-t border-outline-variant pt-6">
                <button class="rounded-lg bg-primary-container px-8 py-3 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" type="submit">
                    إضافة المشرف
                </button>
                <a class="rounded-lg border border-outline-variant px-6 py-3 text-sm font-semibold text-primary transition-colors hover:bg-surface-container" href="{{ route('admin.admins.index') }}">
                    رجوع للقائمة
                </a>
            </div>
        </form>
    </div>
@endsection
