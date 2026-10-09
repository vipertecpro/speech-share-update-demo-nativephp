{{-- The demo menu. See App\NativeComponents\Home. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Plugin demos" subtitle="Four PARqore plugins in six example apps" display-mode="large" />

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-3 px-4 pt-2 pb-10">
            @if ($newerVersion)
                <native:row class="w-full items-center gap-3 rounded-3xl bg-theme-primary/10 border border-theme-primary/40 p-4">
                    <native:icon ios="arrow.down.app.fill" android="system_update" :size="22" class="text-theme-primary" />
                    <native:column class="flex-1 gap-1">
                        <native:text class="text-sm font-bold text-theme-on-surface">Version {{ $newerVersion }} is available</native:text>
                        <native:text class="text-xs text-theme-on-surface-variant">This version still works. Update when it suits you.</native:text>
                    </native:column>
                    <native:button ref="update" variant="primary" size="sm" label="Update" @press="update" />
                </native:row>
            @endif

            <native:text class="text-sm font-bold text-theme-on-surface-variant pt-2">EXAMPLE APPS</native:text>

            @foreach (\App\NativeComponents\Home::DEMOS as $demo)
                <native:pressable key="demo-{{ $loop->index }}" @navigate="$demo['route']" a11y-label="{{ $demo['name'] }}" class="w-full rounded-3xl bg-theme-surface border border-theme-outline-variant p-4">
                    <native:row class="w-full items-center gap-3">
                        <native:column class="w-[48] h-[48] rounded-2xl items-center justify-center bg-theme-primary/15">
                            <native:icon ios="{{ $demo['ios'] }}" android="{{ $demo['android'] }}" :size="24" class="text-theme-primary" />
                        </native:column>
                        <native:column class="flex-1 gap-1">
                            <native:text class="text-base font-bold text-theme-on-surface">{{ $demo['name'] }}</native:text>
                            <native:text class="text-sm text-theme-on-surface-variant">{{ $demo['does'] }}</native:text>
                            <native:row class="gap-2 pt-1">
                                @foreach ($demo['uses'] as $plugin)
                                    <native:icon key="uses-{{ $loop->parent->index }}-{{ $plugin }}" ios="{{ \App\NativeComponents\Home::PLUGIN_ICONS[$plugin]['ios'] }}" android="{{ \App\NativeComponents\Home::PLUGIN_ICONS[$plugin]['android'] }}" :size="14" class="text-theme-on-surface-variant" />
                                @endforeach
                            </native:row>
                        </native:column>
                        <native:icon ios="chevron.right" android="chevron_right" :size="18" class="text-theme-on-surface-variant" />
                    </native:row>
                </native:pressable>
            @endforeach

            <native:text class="text-sm font-bold text-theme-on-surface-variant pt-4">PLUGIN PLAYGROUNDS</native:text>

            @foreach (\App\NativeComponents\Home::PLAYGROUNDS as $playground)
                <native:pressable key="play-{{ $loop->index }}" @navigate="$playground['route']" a11y-label="{{ $playground['name'] }}" class="w-full rounded-3xl bg-theme-surface border border-theme-outline-variant p-4">
                    <native:row class="w-full items-center gap-3">
                        <native:icon ios="{{ $playground['ios'] }}" android="{{ $playground['android'] }}" :size="22" class="text-theme-primary" />
                        <native:column class="flex-1 gap-1">
                            <native:text class="text-base font-bold text-theme-on-surface">{{ $playground['name'] }}</native:text>
                            <native:text class="text-sm text-theme-on-surface-variant">{{ $playground['does'] }}</native:text>
                        </native:column>
                        <native:icon ios="chevron.right" android="chevron_right" :size="18" class="text-theme-on-surface-variant" />
                    </native:row>
                </native:pressable>
            @endforeach

            <native:text class="text-xs text-center text-theme-on-surface-variant pt-4">A demo by vipertecpro. Plugins by PARqore.</native:text>
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('home')])
        </native:column>
    </native:scroll-view>
</native:column>
