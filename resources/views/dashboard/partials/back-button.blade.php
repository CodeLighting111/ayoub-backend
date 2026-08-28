@php
    $url = $url ?? ($pageBackUrl ?? null);
@endphp

@if ($url)
    <a class="flex items-center gap-2 rounded-lg border border-outline px-4 py-2.5 text-sm font-semibold text-on-surface transition-colors hover:bg-surface-container" href="{{ $url }}">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        {{ $label ?? 'رجوع' }}
    </a>
@endif
