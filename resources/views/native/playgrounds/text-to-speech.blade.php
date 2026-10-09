{{-- Text to Speech playground. See App\NativeComponents\Playgrounds\TextToSpeechPlayground. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Text to Speech" subtitle="Playground" back />

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:outlined-text-input label="Text to speak" native:model.debounce.300ms="text" multiline min-lines="3" class="w-full" />

            <native:column class="w-full gap-4 rounded-3xl bg-theme-surface border border-theme-outline-variant p-5">
                <native:row class="w-full items-center gap-2">
                    <native:icon ios="person.wave.2.fill" android="record_voice_over" :size="18" class="text-theme-primary" />
                    <native:text class="text-xs font-semibold text-theme-on-surface-variant">VOICE</native:text>
                </native:row>
                <native:row class="w-full gap-2 flex-wrap">
                    @foreach (\App\NativeComponents\Playgrounds\TextToSpeechPlayground::LOCALES as $tag => $name)
                        <native:button key="voice-{{ $loop->index }}" variant="{{ $locale === $tag ? 'primary' : 'secondary' }}" size="sm" label="{{ $name }}" @press="chooseLocale('{{ $tag }}')" />
                    @endforeach
                </native:row>

                <native:toggle label="Set my own rate and pitch" native:model="customVoice" />

                @if ($customVoice)
                    <native:column class="w-full gap-1">
                        <native:row class="w-full items-center gap-2">
                            <native:icon ios="hare.fill" android="speed" :size="14" class="text-theme-on-surface-variant" />
                            <native:text class="flex-1 text-sm font-semibold text-theme-on-surface">Rate</native:text>
                            <native:text class="text-sm font-mono text-theme-on-surface-variant">{{ number_format($rate, 2) }}</native:text>
                        </native:row>
                        <native:slider native:model="rate" min="0.1" max="1" step="0.05" class="w-full" />
                    </native:column>
                    <native:column class="w-full gap-1">
                        <native:row class="w-full items-center gap-2">
                            <native:icon ios="tuningfork" android="graphic_eq" :size="14" class="text-theme-on-surface-variant" />
                            <native:text class="flex-1 text-sm font-semibold text-theme-on-surface">Pitch</native:text>
                            <native:text class="text-sm font-mono text-theme-on-surface-variant">{{ number_format($pitch, 2) }}</native:text>
                        </native:row>
                        <native:slider native:model="pitch" min="0.5" max="2" step="0.1" class="w-full" />
                    </native:column>
                @else
                    <native:text class="text-sm text-theme-on-surface-variant">Using the phone’s own voice, speed and pitch.</native:text>
                @endif
            </native:column>

            <native:row class="w-full gap-3">
                <native:button variant="primary" size="lg" label="Speak" ios-icon="play.fill" android-icon="play_arrow" @press="speak" class="flex-1" />
                <native:button variant="secondary" size="lg" label="Stop" ios-icon="stop.fill" android-icon="stop" @press="stopReading" class="flex-1" />
            </native:row>
            @if ($speaking && ! $paused)
                <native:button variant="secondary" size="lg" label="Pause" ios-icon="pause.fill" android-icon="pause" @press="pauseReading" class="w-full" />
            @elseif ($paused)
                <native:button variant="secondary" size="lg" label="Resume" ios-icon="play.fill" android-icon="play_arrow" @press="resumeReading" class="w-full" />
            @endif
            <native:row class="w-full gap-3">
                <native:button variant="ghost" label="isSpeaking()" ios-icon="questionmark.circle" android-icon="help_outline" @press="checkSpeaking" class="flex-1" />
                <native:button variant="ghost" label="Reset voice" ios-icon="arrow.counterclockwise" android-icon="restart_alt" @press="resetVoice" class="flex-1" />
            </native:row>

            @include('native.partials.status', ['text' => $status])
            @include('native.playgrounds.event-log')
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('play-tts')])
        </native:column>
    </native:scroll-view>
</native:column>
