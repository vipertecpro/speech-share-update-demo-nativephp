{{-- Text to Speech playground. See App\NativeComponents\Playgrounds\TextToSpeechPlayground. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Text to Speech" subtitle="Playground" back />

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:outlined-text-input label="Text to speak" native:model.debounce.300ms="text" multiline min-lines="3" class="w-full" />

            <native:column class="w-full gap-2">
                <native:text class="text-sm font-bold text-theme-on-surface-variant">VOICE</native:text>
                <native:row class="w-full gap-2 flex-wrap">
                    @foreach (\App\NativeComponents\Playgrounds\TextToSpeechPlayground::LOCALES as $tag => $name)
                        <native:button key="voice-{{ $loop->index }}" variant="{{ $locale === $tag ? 'primary' : 'secondary' }}" size="sm" label="{{ $name }}" @press="chooseLocale('{{ $tag }}')" />
                    @endforeach
                </native:row>
            </native:column>

            <native:toggle label="Set my own rate and pitch" native:model="customVoice" />

            @if ($customVoice)
            <native:column class="w-full gap-1">
                <native:row class="w-full">
                    <native:text class="flex-1 text-sm font-bold text-theme-on-surface-variant">RATE</native:text>
                    <native:text class="text-sm font-mono text-theme-on-surface">{{ number_format($rate, 2) }}</native:text>
                </native:row>
                <native:slider native:model="rate" min="0.1" max="1" step="0.05" class="w-full" />
            </native:column>

            <native:column class="w-full gap-1">
                <native:row class="w-full">
                    <native:text class="flex-1 text-sm font-bold text-theme-on-surface-variant">PITCH</native:text>
                    <native:text class="text-sm font-mono text-theme-on-surface">{{ number_format($pitch, 2) }}</native:text>
                </native:row>
                <native:slider native:model="pitch" min="0.5" max="2" step="0.1" class="w-full" />
            </native:column>
            @else
            <native:text class="text-sm text-theme-on-surface-variant">Using the phone’s own voice, speed and pitch.</native:text>
            @endif

            <native:row class="w-full gap-2">
                <native:button variant="primary" label="Speak" ios-icon="play.fill" android-icon="play_arrow" @press="speak" class="flex-1" />
                @if ($speaking && ! $paused)
                    <native:button variant="secondary" label="Pause" ios-icon="pause.fill" android-icon="pause" @press="pauseReading" class="flex-1" />
                @elseif ($paused)
                    <native:button variant="secondary" label="Resume" ios-icon="play.fill" android-icon="play_arrow" @press="resumeReading" class="flex-1" />
                @endif
                <native:button variant="secondary" label="Stop" ios-icon="stop.fill" android-icon="stop" @press="stopReading" class="flex-1" />
            </native:row>
            <native:row class="w-full gap-2">
                <native:button variant="ghost" label="isSpeaking()" @press="checkSpeaking" class="flex-1" />
                <native:button variant="ghost" label="Reset voice" @press="resetVoice" class="flex-1" />
            </native:row>

            @if ($status !== '')
                <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $status }}</native:text>
            @endif

            @include('native.playgrounds.event-log')
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('play-tts')])
        </native:column>
    </native:scroll-view>
</native:column>
