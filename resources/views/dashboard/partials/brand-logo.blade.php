@php
    $size = $size ?? 'md';
    $showText = $showText ?? true;
    $framed = $framed ?? false;

    $imgClass = match ($size) {
        'sm' => 'h-12 w-auto object-contain',
        'header' => 'h-16 w-auto object-contain',
        'sidebar' => 'mx-auto w-full max-h-[7.5rem] object-contain',
        'lg' => 'h-28 w-auto object-contain',
        default => 'h-20 w-auto object-contain',
    };

    $titleClass = match ($size) {
        'sm' => 'text-sm',
        'sidebar' => 'text-lg font-semibold',
        'lg' => 'text-xl font-semibold',
        default => 'text-lg font-semibold',
    };
@endphp

<div @class(['flex items-center gap-3', 'flex-col text-center' => $stacked ?? false, 'flex-row' => ! ($stacked ?? false)])>
    @if ($framed)
        <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-outline-variant bg-surface-container-lowest p-1.5 shadow-sm">
            <img
                alt="شعار {{ $platformName }}"
                class="h-full w-full object-contain"
                src="{{ $platformLogoUrl }}"
            >
        </div>
    @else
        <img
            alt="شعار {{ $platformName }}"
            @class([$imgClass, 'shrink-0'])
            src="{{ $platformLogoUrl }}"
        >
    @endif
    @if ($showText)
        <div @class(['text-center' => $stacked ?? false, 'w-full' => ($stacked ?? false) && $size === 'sidebar'])>
            <div @class([$titleClass, 'font-bold text-primary-container'])>{{ $platformName }}</div>
            @if ($subtitle ?? false)
                <p class="mt-1 text-xs leading-4 text-on-surface-variant">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
</div>
