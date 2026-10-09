{{-- Hear a phrase, say it back, get a score. See App\NativeComponents\LanguagePractice. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Language Practice" subtitle="{{ \App\Support\Phrases::LANGUAGES[$language]['name'] }}" back>
        <native:top-bar-action id="share-progress" label="Share progress" ios-icon="square.and.arrow.up" android-icon="share" @tap="shareProgress" />
    </native:top-bar>

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:row class="w-full gap-2">
                @foreach (\App\Support\Phrases::LANGUAGES as $tag => $info)
                    <native:button key="language-{{ $tag }}" variant="{{ $language === $tag ? 'primary' : 'secondary' }}" size="sm" label="{{ $info['name'] }}" @press="chooseLanguage('{{ $tag }}')" class="flex-1" />
                @endforeach
            </native:row>

            <native:column class="w-full items-center gap-2 rounded-3xl bg-theme-surface border border-theme-outline-variant px-5 py-8">
                <native:text class="text-xs font-bold text-theme-on-surface-variant">PHRASE {{ $phrase + 1 }} OF {{ count(\App\Support\Phrases::LANGUAGES[$language]['phrases']) }}</native:text>
                <native:text class="text-2xl font-bold text-center text-theme-on-surface">{{ $this->current()['say'] }}</native:text>
                <native:text class="text-base text-center text-theme-on-surface-variant">{{ $this->current()['means'] }}</native:text>
            </native:column>

            <native:row class="w-full gap-2">
                <native:button variant="secondary" label="Listen" ios-icon="speaker.wave.2.fill" android-icon="volume_up" @press="listen" class="flex-1" />
                <native:button variant="secondary" label="Slowly" ios-icon="tortoise.fill" android-icon="slow_motion_video" @press="listenSlowly" class="flex-1" />
            </native:row>

            <native:button variant="{{ $listening ? 'destructive' : 'primary' }}" size="lg" label="{{ $listening ? 'Done' : 'Say it' }}" ios-icon="{{ $listening ? 'stop.fill' : 'mic.fill' }}" android-icon="{{ $listening ? 'stop' : 'mic' }}" @press="toggleDictation('attempt')" class="w-full" />

            @if ($listening || $attempt !== '')
                <native:column class="w-full gap-2 rounded-3xl bg-theme-primary/10 border border-theme-primary/40 p-4">
                    <native:text class="text-xs font-bold text-theme-primary">{{ $listening ? 'LISTENING' : 'YOU SAID' }}</native:text>
                    <native:text class="text-lg text-theme-on-surface">{{ $attempt !== '' ? $attempt : 'Start speaking…' }}</native:text>
                    @if ($score !== null)
                        <native:row class="w-full items-center gap-3 pt-1">
                            <native:column class="items-center">
                                <native:text class="text-3xl font-bold text-theme-primary">{{ $score }}%</native:text>
                                <native:text class="text-xs text-theme-on-surface-variant">match</native:text>
                            </native:column>
                            <native:text class="flex-1 text-sm text-theme-on-surface">{{ $status }}</native:text>
                        </native:row>
                    @endif
                </native:column>
            @endif

            @if ($status !== '' && $score === null)
                <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $status }}</native:text>
            @endif

            <native:button variant="ghost" label="Next phrase" ios-icon-trailing="arrow.right" android-icon-trailing="arrow_forward" @press="nextPhrase" class="w-full" />
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('language')])
        </native:column>
    </native:scroll-view>
</native:column>
