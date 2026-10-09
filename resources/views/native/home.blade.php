{{-- The Demos tab. See App\NativeComponents\Home. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Demos" subtitle="Speech, share and update for NativePHP" display-mode="large" />

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-3 px-4 pt-2 pb-28">
            @if ($newerVersion)
                <native:row class="w-full items-center gap-3 rounded-2xl bg-theme-accent/10 border border-theme-accent/30 px-4 py-3">
                    <native:icon ios="arrow.down.circle.fill" android="system_update" :size="22" class="text-theme-accent" />
                    <native:column class="flex-1 gap-[2]">
                        <native:text class="text-sm font-semibold text-theme-on-surface">Version {{ $newerVersion }} is available</native:text>
                        <native:text class="text-xs text-theme-on-surface-variant">This version still works. Update when it suits you.</native:text>
                    </native:column>
                    <native:button ref="update" variant="secondary" size="sm" label="Update" @press="update" />
                </native:row>
            @endif

            <native:column class="w-full gap-4 rounded-3xl bg-theme-primary/10 border border-theme-primary/30 p-5">
                <native:row class="gap-2">
                    @foreach ([['mic.fill', 'mic'], ['speaker.wave.2.fill', 'volume_up'], ['square.and.arrow.up', 'share'], ['arrow.down.app.fill', 'system_update']] as [$ios, $android])
                        <native:column key="hero-icon-{{ $loop->index }}" class="w-[40] h-[40] rounded-full items-center justify-center bg-theme-primary">
                            <native:icon ios="{{ $ios }}" android="{{ $android }}" :size="20" class="text-theme-on-primary" />
                        </native:column>
                    @endforeach
                </native:row>
                <native:text class="text-2xl font-bold text-theme-on-surface">Four plugins, six everyday apps</native:text>
                <native:text class="text-sm text-theme-on-surface-variant">Speech to Text, Text to Speech, Native Share and Force Update by PARqore, used where people expect them.</native:text>
            </native:column>

            @include('native.partials.section-label', ['text' => 'EXAMPLE APPS'])

            @foreach (\App\NativeComponents\Home::DEMOS as $demo)
                @include('native.partials.nav-card', [
                    'key' => 'demo-'.$loop->index,
                    'route' => $demo['route'],
                    'title' => $demo['name'],
                    'text' => $demo['does'],
                    'ios' => $demo['ios'],
                    'android' => $demo['android'],
                    'chips' => $demo['uses'],
                ])
            @endforeach

            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('home')])

            <native:column class="w-full items-center gap-1 pt-4">
                <native:row class="items-center">
                    <native:text class="text-sm font-bold text-theme-on-surface">vipertecpro</native:text>
                    <native:text class="text-sm font-bold text-theme-primary">_</native:text>
                </native:row>
                <native:text class="text-xs text-center text-theme-on-surface-variant">An independent demo by vipertecpro.com. Plugins by PARqore.</native:text>
            </native:column>
        </native:column>
    </native:scroll-view>
</native:column>
