<span @class([
    'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold',
    $complaint->statusBadgeClasses(),
])>
    {{ $complaint->statusLabel() }}
</span>
