{{-- A small chip. Expects $label; optional $tone: primary, success, accent or muted (default). --}}
@php
    [$bg, $fg] = match ($tone ?? 'muted') {
        'primary' => ['bg-theme-primary/15', 'text-theme-primary'],
        'success' => ['bg-theme-success/15', 'text-theme-success'],
        'accent' => ['bg-theme-accent/15', 'text-theme-accent'],
        default => ['bg-theme-surface-variant', 'text-theme-on-surface-variant'],
    };
@endphp
<native:column class="rounded-full px-3 py-1 {{ $bg }}">
    <native:text class="text-xs font-semibold {{ $fg }}">{{ $label }}</native:text>
</native:column>
