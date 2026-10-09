{{-- Shown while dictating: what has been heard, and a way to cancel. Expects $heard (string). --}}
<native:row class="w-full items-center gap-3 rounded-2xl bg-theme-primary/10 border border-theme-primary/30 px-4 py-3">
    <native:icon ios="waveform" android="graphic_eq" :size="20" class="text-theme-primary" />
    <native:column class="flex-1 gap-[2]">
        <native:text class="text-xs font-semibold text-theme-primary">LISTENING</native:text>
        <native:text class="text-sm text-theme-on-surface">{{ $heard !== '' ? $heard : 'Start speaking…' }}</native:text>
    </native:column>
    <native:button variant="ghost" size="sm" label="Cancel" ios-icon="xmark" android-icon="close" @press="cancelDictation" />
</native:row>
