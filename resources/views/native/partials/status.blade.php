{{-- The result of the last action, as a quiet line with an icon. Expects $text (string). --}}
@if ($text !== '')
    <native:row class="w-full items-center gap-3 rounded-2xl bg-theme-surface-variant px-4 py-3">
        <native:icon ios="info.circle.fill" android="info" :size="18" class="text-theme-on-surface-variant" />
        <native:text class="flex-1 text-sm text-theme-on-surface">{{ $text }}</native:text>
    </native:row>
@endif
