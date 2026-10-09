{{-- Native Share playground. See App\NativeComponents\Playgrounds\SharePlayground. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Native Share" subtitle="Playground" back />

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:button variant="primary" label="Share text" ios-icon="text.bubble" android-icon="chat" @press="shareSomeText" class="w-full" />
            <native:button variant="secondary" label="Share a link" ios-icon="link" android-icon="link" @press="shareSomeLink" class="w-full" />
            <native:button variant="secondary" label="Share a file" ios-icon="doc" android-icon="description" @press="shareSomeFile" class="w-full" />

            @if (! \Native\Mobile\Platform::isIos())
                <native:toggle label="Send straight to WhatsApp (Android)" native:model="straightToWhatsApp" />
            @endif

            @if ($status !== '')
                <native:column class="w-full items-center gap-1 rounded-3xl bg-theme-primary/10 border border-theme-primary/40 p-4">
                    <native:text class="text-base font-bold text-center text-theme-on-surface">{{ $status }}</native:text>
                    <native:text class="text-xs text-center text-theme-on-surface-variant">ShareCompleted tells your app whether the user finished and which app they chose.</native:text>
                </native:column>
            @endif

            @include('native.playgrounds.event-log')
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('play-share')])
        </native:column>
    </native:scroll-view>
</native:column>
