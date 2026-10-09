{{-- The update gate. See App\NativeComponents\UpdateRequired. --}}
<native:column class="w-full h-full items-center justify-center gap-4 px-8 bg-theme-background">
    <native:column class="w-[88] h-[88] rounded-full items-center justify-center bg-theme-primary/15">
        <native:icon ios="arrow.down.app.fill" android="system_update" :size="44" class="text-theme-primary" />
    </native:column>
    <native:text class="text-2xl font-bold text-center text-theme-on-surface">Update required</native:text>
    <native:text class="text-base text-center text-theme-on-surface-variant">This version of Voice Notes ({{ $status['current'] ?? '' }}) is no longer supported. Please update to version {{ $status['minimum'] ?? '' }} or later to keep going.</native:text>
    <native:button variant="primary" size="lg" label="Update now" ios-icon="arrow.up.right.square" android-icon="open_in_new" @press="openStore" class="w-full" />
    @if ($message !== '')
        <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $message }}</native:text>
    @endif
    <native:text class="text-xs text-center text-theme-on-surface-variant">Demo: turn off “Pretend this build is too old” in the Force Update playground, then reopen the app.</native:text>
    <native:button variant="ghost" size="sm" label="Open the Force Update playground" @press="navigate('/play/force-update')" />
</native:column>
