@php
    $cancelUrl = route('admin.governorates.index');
@endphp

<div class="dashboard-card w-full rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
    <form action="{{ $action }}" class="space-y-6" method="POST">
        @csrf
        @if ($method ?? false)
            @method($method)
        @endif

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-on-surface" for="name">اسم المحافظة <span class="text-error">*</span></label>
            <input
                @class([
                    'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                    'border-red-500' => $errors->has('name'),
                ])
                id="name"
                name="name"
                placeholder="أدخل اسم المحافظة"
                required
                type="text"
                value="{{ old('name', $governorate->name) }}"
            >
            @error('name')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <hr class="border-outline-variant">

        <div class="flex items-center justify-end gap-4 pt-2">
            <a class="rounded-lg px-6 py-2.5 text-sm font-semibold text-on-surface-variant transition-colors hover:bg-surface-container" href="{{ $cancelUrl }}">
                إلغاء
            </a>
            <button class="flex items-center gap-2 rounded-lg bg-primary-container px-8 py-2.5 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" type="submit">
                <span class="material-symbols-outlined text-[18px]">save</span>
                {{ $submitLabel }}
            </button>
        </div>
    </form>
</div>
