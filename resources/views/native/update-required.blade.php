{{-- The update gate. See App\NativeComponents\UpdateRequired. --}}
<native:column class="w-full h-full items-center justify-center gap-4 px-6 bg-theme-background">
    <native:column class="w-[96] h-[96] rounded-full items-center justify-center bg-theme-primary">
        <native:icon ios="arrow.down.app.fill" android="system_update" :size="46" class="text-theme-on-primary" />
    </native:column>
    <native:text class="text-3xl font-bold text-center text-theme-on-surface">Update required</native:text>
    <native:text class="text-base text-center text-theme-on-surface-variant">This version of the app ({{ $status['current'] ?? '' }}) is no longer supported. Please update to version {{ $status['minimum'] ?? '' }} or later to keep going.</native:text>
    <native:button variant="primary" size="lg" label="Update now" ios-icon="arrow.up.right.square.fill" android-icon="open_in_new" @press="openStore" class="w-full mt-2" />
    @if ($message !== '')
        <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $message }}</native:text>
    @endif
    <native:column class="w-full items-center gap-2 rounded-2xl bg-theme-surface-variant px-4 py-4 mt-6">
        <native:row class="items-center gap-2">
            <native:icon ios="hammer.fill" android="build" :size="14" class="text-theme-on-surface-variant" />
            <native:text class="text-xs font-semibold text-theme-on-surface-variant">DEMO ONLY</native:text>
        </native:row>
        <native:text class="text-sm text-center text-theme-on-surface">Turn off “Pretend this build is too old” in the Force Update playground, then reopen the app.</native:text>
        <native:button variant="ghost" size="sm" label="Open the playground" ios-icon="slider.horizontal.3" android-icon="tune" @press="navigate('/play/force-update')" />
    </native:column>
</native:column>
