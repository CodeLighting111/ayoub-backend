@extends('dashboard.layouts.app')

@section('title', 'تفاصيل الشكوى')

@section('breadcrumb', 'الشكاوى / تفاصيل')

@section('content')
    <div class="mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="dashboard-page-title mb-2 text-on-surface">تفاصيل الشكوى #{{ $complaint->id }}</h1>
            <p class="dashboard-page-subtitle text-on-surface-variant">تاريخ الشكوى: {{ optional($complaint->created_at)->format('Y-m-d H:i') }}</p>
        </div>
        <div class="flex items-center gap-3">
            @include('dashboard.complaints.partials.status-badge', ['complaint' => $complaint])
            @include('dashboard.partials.back-button')
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                <h2 class="mb-4 text-lg font-semibold text-on-surface">معلومات العميل</h2>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-lg bg-surface-container-low p-4">
                        <p class="mb-1 text-xs text-on-surface-variant">الاسم</p>
                        <p class="font-medium text-on-surface">{{ $complaint->client_name }}</p>
                    </div>
                    <div class="rounded-lg bg-surface-container-low p-4">
                        <p class="mb-1 text-xs text-on-surface-variant">رقم الهاتف</p>
                        <p class="font-medium text-on-surface">{{ $complaint->client_phone }}</p>
                    </div>
                </div>
            </div>

            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                <h2 class="mb-4 text-lg font-semibold text-on-surface">نص الشكوى او الاستفسار</h2>
                <div class="rounded-lg border border-outline-variant/50 bg-surface-bright p-5">
                    <p class="text-sm leading-relaxed text-on-surface">{{ $complaint->message }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="dashboard-card rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
                <h2 class="mb-4 text-lg font-semibold text-on-surface">تغيير الحالة</h2>
                @if ($complaint->status === 'pending')
                    <form action="{{ route('admin.complaints.update-status', $complaint) }}" class="space-y-4" method="POST">
                        @csrf
                        @method('PATCH')
                        <select class="dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface" name="status" required>
                            @foreach (\App\Models\Complaint::STATUSES as $option)
                                <option @selected(old('status', $complaint->status) === $option) value="{{ $option }}">{{ (new \App\Models\Complaint(['status' => $option]))->statusLabel() }}</option>
                            @endforeach
                        </select>
                        <button class="w-full rounded-lg bg-primary-container px-4 py-2.5 text-sm font-semibold text-on-primary" type="submit">تحديث الحالة</button>
                    </form>
                @else
                    <div class="rounded-lg bg-surface-container-low px-4 py-3 text-sm text-on-surface-variant">
                        تم إغلاق هذه الشكوى ولا يمكن تعديل حالتها.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
