{{-- What the plugins do on this screen and what the demo adds. See App\Support\DemoNotes. --}}
<native:column class="w-full gap-3 rounded-3xl border border-theme-outline-variant p-4 mt-2">
    <native:text class="text-xs font-bold text-theme-on-surface-variant">HOW THIS DEMO WORKS</native:text>
    <native:column class="w-full gap-1">
        <native:text class="text-sm font-bold text-theme-on-surface">From the plugins</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">{{ $howItWorks['plugins'] }}</native:text>
    </native:column>
    <native:column class="w-full gap-1">
        <native:text class="text-sm font-bold text-theme-on-surface">Built in this demo</native:text>
        <native:text class="text-sm text-theme-on-surface-variant">{{ $howItWorks['demo'] }}</native:text>
    </native:column>
</native:column>
