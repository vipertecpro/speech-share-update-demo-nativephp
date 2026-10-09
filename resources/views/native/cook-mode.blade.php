{{-- A recipe read aloud step by step. See App\NativeComponents\CookMode. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Cook Mode" subtitle="{{ \App\Support\Recipe::NAME }}" back>
        <native:top-bar-action id="share-list" label="Share shopping list" ios-icon="cart" android-icon="shopping_cart" @tap="shareShoppingList" />
    </native:top-bar>

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:column class="w-full gap-3 rounded-3xl bg-theme-primary/10 border border-theme-primary/40 p-5">
                <native:row class="w-full items-center gap-2">
                    <native:text class="flex-1 text-sm font-bold text-theme-primary">STEP {{ $step + 1 }} OF {{ count(\App\Support\Recipe::STEPS) }}</native:text>
                    @if ($speaking)
                        <native:icon ios="speaker.wave.2.fill" android="volume_up" :size="18" class="text-theme-primary" />
                    @endif
                </native:row>
                <native:text class="text-xl font-bold text-theme-on-surface">{{ \App\Support\Recipe::STEPS[$step] }}</native:text>
            </native:column>

            <native:row class="w-full gap-2">
                <native:button variant="secondary" label="Back" ios-icon="chevron.left" android-icon="chevron_left" :disabled="$step === 0" @press="previous" class="flex-1" />
                <native:button variant="secondary" label="Next" ios-icon-trailing="chevron.right" android-icon-trailing="chevron_right" :disabled="$step === count(\App\Support\Recipe::STEPS) - 1" @press="next" class="flex-1" />
            </native:row>

            @if ($handsFree)
                <native:row class="w-full gap-2">
                    @if ($paused)
                        <native:button variant="primary" label="Resume" ios-icon="play.fill" android-icon="play_arrow" @press="resumeReading" class="flex-1" />
                    @else
                        <native:button variant="primary" label="Pause" ios-icon="pause.fill" android-icon="pause" @press="pauseReading" class="flex-1" />
                    @endif
                    <native:button variant="destructive" label="Stop" ios-icon="stop.fill" android-icon="stop" @press="stopHandsFree" class="flex-1" />
                </native:row>
            @else
                <native:button variant="primary" size="lg" label="Read every step" ios-icon="play.fill" android-icon="play_arrow" @press="startHandsFree" class="w-full" />
                <native:button variant="ghost" label="{{ $this->isReading('step-'.$step) ? 'Stop' : 'Read this step' }}" ios-icon="speaker.wave.2" android-icon="volume_up" @press="{{ $this->isReading('step-'.$step) ? 'stopReading' : 'readStep' }}" class="w-full" />
            @endif

            @if ($status !== '')
                <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $status }}</native:text>
            @endif

            <native:column class="w-full gap-2 rounded-3xl bg-theme-surface border border-theme-outline-variant p-4">
                <native:row class="w-full items-center">
                    <native:text class="flex-1 text-base font-bold text-theme-on-surface">Ingredients</native:text>
                    <native:text class="text-xs text-theme-on-surface-variant">{{ \App\Support\Recipe::SERVES }}</native:text>
                </native:row>
                @foreach (\App\Support\Recipe::INGREDIENTS as $ingredient)
                    <native:text key="ingredient-{{ $loop->index }}" class="text-sm text-theme-on-surface">• {{ $ingredient }}</native:text>
                @endforeach
                <native:button variant="secondary" label="Share shopping list" ios-icon="square.and.arrow.up" android-icon="share" @press="shareShoppingList" class="w-full mt-2" />
            </native:column>
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('cook')])
        </native:column>
    </native:scroll-view>
</native:column>
