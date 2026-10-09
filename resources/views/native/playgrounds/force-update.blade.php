{{-- Force Update playground. See App\NativeComponents\Playgrounds\ForceUpdatePlayground. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Force Update" subtitle="Playground" back />

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:column class="w-full gap-2 rounded-3xl bg-theme-surface border border-theme-outline-variant p-4">
                <native:row class="w-full items-center">
                    <native:text class="flex-1 text-base font-bold text-theme-on-surface">status()</native:text>
                    <native:button variant="ghost" size="sm" label="Refresh" @press="refresh" />
                </native:row>
                @foreach (['current' => 'This build', 'minimum' => 'Minimum', 'latest' => 'Latest', 'outdated' => 'Outdated', 'mode' => 'Mode', 'storeUrl' => 'Store link'] as $key => $label)
                    <native:row key="status-{{ $key }}" class="w-full gap-2">
                        <native:text class="w-[96] text-sm text-theme-on-surface-variant">{{ $label }}</native:text>
                        <native:text class="flex-1 text-sm font-mono text-theme-on-surface">{{ is_bool($versionStatus[$key] ?? null) ? (($versionStatus[$key] ?? false) ? 'true' : 'false') : ($versionStatus[$key] ?? 'null') }}</native:text>
                    </native:row>
                @endforeach
            </native:column>

            <native:column class="w-full gap-2 rounded-3xl bg-theme-surface border border-theme-outline-variant p-4">
                <native:text class="text-base font-bold text-theme-on-surface">Hard and soft updates</native:text>
                <native:text class="text-sm text-theme-on-surface-variant">Below the minimum, the app shows a blocking update screen at launch. Below the latest but above the minimum, the home screen shows a gentle banner instead. Your app decides; the plugin compares versions and opens the store. The minimum and latest come from your app’s configure() call, for example from your own API.</native:text>
            </native:column>

            <native:toggle label="Pretend this build is too old" native:model="simulateOldBuild" />
            <native:button variant="primary" label="Open the store listing" ios-icon="arrow.up.forward.app" android-icon="open_in_new" @press="openStore" class="w-full" />

            @if ($status !== '')
                <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $status }}</native:text>
            @endif

            @include('native.playgrounds.event-log')
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('play-update')])
        </native:column>
    </native:scroll-view>
</native:column>
