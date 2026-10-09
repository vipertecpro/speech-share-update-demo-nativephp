{{-- The Playgrounds tab. See App\NativeComponents\Playgrounds\PlaygroundsIndex. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Playgrounds" subtitle="Every option, every event" display-mode="large" />

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-3 px-4 pt-2 pb-28">
            <native:row class="w-full items-center gap-3 rounded-2xl bg-theme-surface-variant px-4 py-3">
                <native:icon ios="list.bullet.rectangle" android="receipt_long" :size="18" class="text-theme-on-surface-variant" />
                <native:text class="flex-1 text-sm text-theme-on-surface">Each playground logs the calls your app makes and the events the plugin sends back, with timings.</native:text>
            </native:row>

            @foreach (\App\NativeComponents\Playgrounds\PlaygroundsIndex::PLAYGROUNDS as $playground)
                @include('native.partials.nav-card', [
                    'key' => 'playground-'.$loop->index,
                    'route' => $playground['route'],
                    'title' => $playground['name'],
                    'text' => $playground['does'],
                    'ios' => $playground['ios'],
                    'android' => $playground['android'],
                    'chips' => $playground['tries'],
                    'chipsMono' => true,
                    'chipsIos' => 'chevron.left.forwardslash.chevron.right',
                    'chipsAndroid' => 'code',
                ])
            @endforeach
        </native:column>
    </native:scroll-view>
</native:column>
