@extends('dashboard.layouts.app')

@section('title', 'إرسال إشعار')

@section('breadcrumb', 'الإشعارات / إرسال')

@section('content')
    @include('dashboard.partials.page-header', [
        'title' => 'إرسال إشعار للعملاء',
        'subtitle' => 'أرسل إشعاراً لجميع مستخدمي التطبيق أو لعميل محدد.',
    ])

    <div class="dashboard-card w-full rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
        <form action="{{ route('admin.notifications.send.store') }}" class="space-y-6" method="POST">
            @csrf

            <div class="space-y-4">
                <label class="block text-sm font-semibold text-on-surface">نوع الإرسال <span class="text-error">*</span></label>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <label @class([
                        'flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition-colors',
                        'border-primary-container bg-primary-container/5' => old('target', $selectedClientId ? 'client' : 'all') === 'all',
                        'border-outline-variant hover:bg-surface-container-low' => old('target', $selectedClientId ? 'client' : 'all') !== 'all',
                    ])>
                        <input @checked(old('target', $selectedClientId ? 'client' : 'all') === 'all') class="mt-1" name="target" type="radio" value="all">
                        <span>
                            <span class="block text-sm font-semibold text-on-surface">جميع العملاء</span>
                            <span class="mt-1 block text-xs text-on-surface-variant">إرسال الإشعار لكل العملاء النشطين في التطبيق.</span>
                        </span>
                    </label>
                    <label @class([
                        'flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition-colors',
                        'border-primary-container bg-primary-container/5' => old('target', $selectedClientId ? 'client' : 'all') === 'client',
                        'border-outline-variant hover:bg-surface-container-low' => old('target', $selectedClientId ? 'client' : 'all') !== 'client',
                    ])>
                        <input @checked(old('target', $selectedClientId ? 'client' : 'all') === 'client') class="mt-1" name="target" type="radio" value="client">
                        <span>
                            <span class="block text-sm font-semibold text-on-surface">عميل محدد</span>
                            <span class="mt-1 block text-xs text-on-surface-variant">اختر عميلاً واحداً لإرسال الإشعار إليه.</span>
                        </span>
                    </label>
                </div>
                @error('target')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div @class(['space-y-2', 'hidden' => old('target', $selectedClientId ? 'client' : 'all') !== 'client']) data-client-select id="client-select-wrap">
                <label class="block text-sm font-semibold text-on-surface" for="client_id">العميل</label>
                <select
                    @class([
                        'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('client_id'),
                    ])
                    id="client_id"
                    name="client_id"
                >
                    <option value="">اختر العميل...</option>
                    @foreach ($clients as $client)
                        <option @selected(old('client_id', $selectedClientId) == $client->id) value="{{ $client->id }}">
                            {{ $client->name }} — {{ $client->phone }}@if ($client->branch_name) ({{ $client->branch_name }})@endif
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="title">عنوان الإشعار <span class="text-error">*</span></label>
                <input
                    @class([
                        'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('title'),
                    ])
                    id="title"
                    name="title"
                    placeholder="مثال: عرض خاص جديد"
                    required
                    type="text"
                    value="{{ old('title') }}"
                >
                @error('title')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="message">نص الإشعار <span class="text-error">*</span></label>
                <textarea
                    @class([
                        'block min-h-32 w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('message'),
                    ])
                    id="message"
                    name="message"
                    placeholder="اكتب رسالة الإشعار التي ستظهر للعميل في التطبيق..."
                    required
                >{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-outline-variant pt-6">
                <a class="rounded-lg border border-outline px-6 py-2.5 text-sm font-semibold text-on-surface transition-colors hover:bg-surface-container" href="{{ route('admin.notifications.index') }}">إلغاء</a>
                <button class="flex items-center gap-2 rounded-lg bg-primary-container px-6 py-2.5 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" type="submit">
                    <span class="material-symbols-outlined text-[20px]">send</span>
                    إرسال الإشعار
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        const targetInputs = document.querySelectorAll('input[name="target"]');
        const clientWrap = document.getElementById('client-select-wrap');
        const clientSelect = document.getElementById('client_id');

        function syncClientField() {
            const selected = document.querySelector('input[name="target"]:checked');
            const isClient = selected && selected.value === 'client';
            clientWrap.classList.toggle('hidden', !isClient);
            clientSelect.required = isClient;
            if (!isClient) {
                clientSelect.value = '';
            }
        }

        targetInputs.forEach(function (input) {
            input.addEventListener('change', syncClientField);
        });

        syncClientField();
    })();
</script>
@endpush
