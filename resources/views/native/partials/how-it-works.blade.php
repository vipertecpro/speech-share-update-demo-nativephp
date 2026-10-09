{{-- What the plugins do on this screen and what the demo adds. Expects $howItWorks. See App\Support\DemoNotes. --}}
<native:column class="w-full gap-4 rounded-3xl bg-theme-surface border border-theme-outline-variant p-5 mt-2">
    <native:row class="w-full items-center gap-2">
        <native:icon ios="info.circle" android="info" :size="18" class="text-theme-on-surface-variant" />
        <native:text class="text-xs font-semibold text-theme-on-surface-variant">HOW THIS DEMO WORKS</native:text>
    </native:row>
    <native:row class="w-full gap-3">
        <native:column class="w-[32] h-[32] rounded-full items-center justify-center bg-theme-primary/15">
            <native:icon ios="puzzlepiece.extension.fill" android="extension" :size="16" class="text-theme-primary" />
        </native:column>
        <native:column class="flex-1 gap-1">
            <native:text class="text-sm font-semibold text-theme-on-surface">From the plugins</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">{{ $howItWorks['plugins'] }}</native:text>
        </native:column>
    </native:row>
    <native:row class="w-full gap-3">
        <native:column class="w-[32] h-[32] rounded-full items-center justify-center bg-theme-surface-variant">
            <native:icon ios="hammer.fill" android="build" :size="16" class="text-theme-on-surface-variant" />
        </native:column>
        <native:column class="flex-1 gap-1">
            <native:text class="text-sm font-semibold text-theme-on-surface">Built in this demo</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">{{ $howItWorks['demo'] }}</native:text>
        </native:column>
    </native:row>
</native:column>
