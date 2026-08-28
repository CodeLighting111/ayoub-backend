@php
    $backUrl = $backUrl ?? ($pageBackUrl ?? null);
@endphp

<div @class([
    'mb-8 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center',
    $wrapperClass ?? null,
])>
    <div>
        @if ($title ?? false)
            <h1 @class([
                'dashboard-page-title text-on-surface',
                'mb-2' => $subtitle ?? false,
            ])>{{ $title }}</h1>
        @endif
        @if ($subtitle ?? false)
            <p class="dashboard-page-subtitle text-on-surface-variant">{{ $subtitle }}</p>
        @endif
        {{ $leading ?? '' }}
    </div>
    @if (($trailing ?? false) || $backUrl)
        <div class="flex flex-wrap items-center gap-3">
            {!! $trailing ?? '' !!}
            @include('dashboard.partials.back-button', ['url' => $backUrl])
        </div>
    @endif
</div>
