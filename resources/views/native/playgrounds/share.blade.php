{{-- Native Share playground. See App\NativeComponents\Playgrounds\SharePlayground. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Native Share" subtitle="Playground" back />

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:column class="w-full gap-3 rounded-3xl bg-theme-surface border border-theme-outline-variant p-5">
                <native:row class="w-full items-center gap-2">
                    <native:icon ios="square.and.arrow.up" android="share" :size="18" class="text-theme-primary" />
                    <native:text class="text-xs font-semibold text-theme-on-surface-variant">WHAT TO SHARE</native:text>
                </native:row>
                <native:button variant="primary" size="lg" label="Share text" ios-icon="text.bubble.fill" android-icon="chat" @press="shareSomeText" class="w-full" />
                <native:button variant="secondary" size="lg" label="Share a link" ios-icon="link" android-icon="link" @press="shareSomeLink" class="w-full" />
                <native:button variant="secondary" size="lg" label="Share a file" ios-icon="doc.fill" android-icon="description" @press="shareSomeFile" class="w-full" />
                @if (! \Native\Mobile\Platform::isIos())
                    <native:toggle label="Send straight to WhatsApp" native:model="straightToWhatsApp" />
                @endif
            </native:column>

            @if ($status !== '')
                <native:row class="w-full items-center gap-3 rounded-2xl bg-theme-primary/10 border border-theme-primary/30 px-4 py-3">
                    <native:icon ios="checkmark.circle.fill" android="check_circle" :size="20" class="text-theme-primary" />
                    <native:column class="flex-1 gap-[2]">
                        <native:text class="text-sm font-semibold text-theme-on-surface">{{ $status }}</native:text>
                        <native:text class="text-xs text-theme-on-surface-variant">ShareCompleted says whether the user finished and which app they chose.</native:text>
                    </native:column>
                </native:row>
            @endif

            @include('native.playgrounds.event-log')
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('play-share')])
        </native:column>
    </native:scroll-view>
</native:column>
