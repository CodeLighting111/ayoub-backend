@extends('dashboard.layouts.app')

@section('title', 'الملف الشخصي')

@section('breadcrumb', 'الملف الشخصي')

@section('content')
    <div class="mb-8">
        <h1 class="dashboard-page-title mb-2 text-on-surface">الملف الشخصي</h1>
        <p class="dashboard-page-subtitle text-on-surface-variant">إدارة معلومات حسابك الشخصي وإعدادات الأمان.</p>
    </div>

    <form action="{{ route('admin.profile.update') }}" class="grid grid-cols-1 gap-6 xl:grid-cols-3" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6 xl:col-span-2">
            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                <h2 class="mb-6 border-b border-outline-variant pb-4 text-lg font-semibold text-on-surface">المعلومات الشخصية</h2>

                <div class="mb-8 flex flex-col items-start gap-8 md:flex-row">
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative flex h-32 w-32 items-center justify-center overflow-hidden rounded-full border-4 border-surface-container bg-surface-container-high">
                            @if ($admin->avatar_url)
                                <img alt="{{ $admin->name }}" class="h-full w-full object-cover" src="{{ asset(ltrim($admin->avatar_url, '/')) }}">
                            @else
                                <span class="material-symbols-outlined text-5xl text-primary-container">person</span>
                            @endif
                        </div>
                        <label class="cursor-pointer text-sm font-medium text-primary-container hover:text-primary" for="avatar">
                            <span class="material-symbols-outlined align-middle text-base">edit</span>
                            تغيير الصورة
                        </label>
                        <input accept="image/*" class="sr-only" id="avatar" name="avatar" type="file">
                        @error('avatar')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid w-full flex-1 grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface" for="name">الاسم الكامل <span class="text-error">*</span></label>
                            <input
                                @class([
                                    'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                                    'border-red-500' => $errors->has('name'),
                                ])
                                id="name"
                                name="name"
                                placeholder="أدخل اسمك الكامل"
                                required
                                type="text"
                                value="{{ old('name', $admin->name) }}"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-on-surface" for="phone">رقم الهاتف</label>
                            <input
                                @class([
                                    'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                                    'border-red-500' => $errors->has('phone'),
                                ])
                                id="phone"
                                name="phone"
                                placeholder="01xxxxxxxxx"
                                type="text"
                                value="{{ old('phone', $admin->phone) }}"
                            >
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-semibold text-on-surface" for="email">البريد الإلكتروني <span class="text-error">*</span></label>
                            <input
                                @class([
                                    'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                                    'border-red-500' => $errors->has('email'),
                                ])
                                id="email"
                                name="email"
                                placeholder="example@email.com"
                                required
                                type="email"
                                value="{{ old('email', $admin->email) }}"
                            >
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                <div class="mb-6 flex items-center gap-3 border-b border-outline-variant pb-4">
                    <span class="material-symbols-outlined text-primary-container">lock</span>
                    <h2 class="text-lg font-semibold text-on-surface">الأمان وتغيير كلمة المرور</h2>
                </div>

                <div class="grid max-w-xl grid-cols-1 gap-5">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface" for="current_password">كلمة المرور الحالية</label>
                        <input
                            @class([
                                'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                                'border-red-500' => $errors->has('current_password'),
                            ])
                            id="current_password"
                            name="current_password"
                            placeholder="••••••••"
                            type="password"
                        >
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface" for="password">كلمة المرور الجديدة</label>
                        <input
                            @class([
                                'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                                'border-red-500' => $errors->has('password'),
                            ])
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            type="password"
                        >
                        <p class="mt-1 text-xs text-on-surface-variant">يجب أن تحتوي على 8 أحرف على الأقل، وحرف كبير، ورقم.</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-on-surface" for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                        <input
                            class="block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="••••••••"
                            type="password"
                        >
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a class="rounded-lg border border-outline-variant px-6 py-3 text-sm font-semibold text-primary transition-colors hover:bg-surface-container" href="{{ route('admin.onboarding.index') }}">
                    إلغاء
                </a>
                <button class="flex items-center justify-center gap-2 rounded-lg bg-primary-container px-8 py-3 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" type="submit">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    حفظ التغييرات
                </button>
            </div>
        </div>

        <div class="hidden xl:block">
            <div class="dashboard-card sticky top-24 rounded-xl border border-outline-variant bg-secondary-fixed p-6">
                <div class="flex flex-col items-center gap-4 text-center">
                    <div class="mb-2 flex h-16 w-16 items-center justify-center rounded-full bg-on-secondary-fixed text-secondary-fixed">
                        <span class="material-symbols-outlined text-[32px]">shield_person</span>
                    </div>
                    <h3 class="text-lg font-semibold text-on-secondary-fixed">حساب مسؤول النظام</h3>
                    <p class="text-sm text-on-secondary-fixed-variant">لديك صلاحيات كاملة لإدارة النظام، يرجى الحفاظ على سرية معلومات الدخول الخاصة بك.</p>
                </div>
            </div>
        </div>
    </form>
@endsection
