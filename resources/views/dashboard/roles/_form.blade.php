<div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
    <form action="{{ $action }}" method="POST">
        @csrf
        @if (($method ?? 'POST') !== 'POST')
            @method($method)
        @endif

        <div class="mb-8">
            <label class="mb-2 block text-sm font-semibold text-on-surface" for="name">اسم الدور <span class="text-error">*</span></label>
            <input
                @class([
                    'block w-full max-w-xl rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                    'border-red-500' => $errors->has('name'),
                ])
                id="name"
                name="name"
                required
                type="text"
                value="{{ old('name', $role->name) }}"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="border-t border-outline-variant pt-8">
            <h2 class="mb-2 flex items-center gap-2 text-lg font-semibold text-on-surface">
                <span class="material-symbols-outlined text-primary-container">key</span>
                الأذونات
            </h2>
            <p class="mb-6 text-sm text-on-surface-variant">حدد الصلاحيات المتاحة لهذا الدور.</p>

            @if ($role->slug === 'superadmin')
                <div class="rounded-lg border border-primary-container/20 bg-primary-container/5 p-6 text-sm text-on-surface">
                    دور <strong>مدير النظام</strong> يمتلك جميع الأذونات تلقائياً.
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($permissions as $group => $groupPermissions)
                        <div class="rounded-xl border border-outline-variant/70 p-5">
                            <h3 class="mb-4 text-base font-semibold text-on-surface">{{ $group }}</h3>
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($groupPermissions as $permission)
                                    <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-outline-variant/50 px-4 py-3 transition-colors hover:bg-surface-container-low">
                                        <input
                                            @checked(in_array($permission->id, $assignedPermissionIds))
                                            class="rounded border-outline-variant text-primary-container focus:ring-primary-container"
                                            name="permissions[]"
                                            type="checkbox"
                                            value="{{ $permission->id }}"
                                        >
                                        <span class="text-sm text-on-surface">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-8 flex gap-4 border-t border-outline-variant pt-6">
            <button class="rounded-lg bg-primary-container px-8 py-3 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" type="submit">
                {{ $submitLabel }}
            </button>
            <a class="rounded-lg border border-outline-variant px-6 py-3 text-sm font-semibold text-primary transition-colors hover:bg-surface-container" href="{{ route('admin.roles.index') }}">
                إلغاء
            </a>
        </div>
    </form>
</div>
