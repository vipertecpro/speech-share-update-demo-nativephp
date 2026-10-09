{{-- Force Update playground. See App\NativeComponents\Playgrounds\ForceUpdatePlayground. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Force Update" subtitle="Playground" back />

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:column class="w-full gap-3 rounded-3xl bg-theme-surface border border-theme-outline-variant p-5">
                <native:row class="w-full items-center gap-2">
                    <native:icon ios="checkmark.shield.fill" android="verified" :size="18" class="text-theme-primary" />
                    <native:text class="flex-1 text-xs font-semibold text-theme-on-surface-variant">STATUS()</native:text>
                    <native:button variant="ghost" size="sm" label="Refresh" ios-icon="arrow.clockwise" android-icon="refresh" @press="refresh" />
                </native:row>
                @foreach (['current' => 'This build', 'minimum' => 'Minimum', 'latest' => 'Latest', 'outdated' => 'Outdated', 'mode' => 'Mode', 'storeUrl' => 'Store link'] as $key => $label)
                    <native:row key="status-{{ $key }}" class="w-full gap-3">
                        <native:text class="w-[92] text-sm text-theme-on-surface-variant">{{ $label }}</native:text>
                        <native:text class="flex-1 text-sm font-mono text-theme-on-surface">{{ is_bool($versionStatus[$key] ?? null) ? (($versionStatus[$key] ?? false) ? 'true' : 'false') : ($versionStatus[$key] ?? 'null') }}</native:text>
                    </native:row>
                @endforeach
            </native:column>

            <native:column class="w-full gap-3 rounded-3xl bg-theme-surface border border-theme-outline-variant p-5">
                <native:row class="w-full items-center gap-2">
                    <native:icon ios="arrow.triangle.branch" android="call_split" :size="18" class="text-theme-primary" />
                    <native:text class="text-xs font-semibold text-theme-on-surface-variant">HARD AND SOFT UPDATES</native:text>
                </native:row>
                <native:text class="text-sm text-theme-on-surface">Below the minimum, the app shows a blocking update screen at launch. Below the latest but above the minimum, the Demos tab shows a gentle banner instead.</native:text>
                <native:text class="text-sm text-theme-on-surface-variant">Your app decides; the plugin compares versions and opens the store. The minimum and latest come from your app’s configure() call, for example from your own API.</native:text>
            </native:column>

            <native:column class="w-full gap-3 rounded-3xl bg-theme-surface border border-theme-outline-variant p-5">
                <native:toggle label="Pretend this build is too old" native:model="simulateOldBuild" />
                <native:button variant="primary" size="lg" label="Open the store listing" ios-icon="arrow.up.right.square.fill" android-icon="open_in_new" @press="openStore" class="w-full" />
            </native:column>

            @include('native.partials.status', ['text' => $status])
            @include('native.playgrounds.event-log')
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('play-update')])
        </native:column>
    </native:scroll-view>
</native:column>
