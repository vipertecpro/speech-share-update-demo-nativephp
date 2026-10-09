{{-- Speech to Text playground. See App\NativeComponents\Playgrounds\SpeechToTextPlayground. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Speech to Text" subtitle="Playground" back />

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:column class="w-full gap-4 rounded-3xl bg-theme-surface border border-theme-outline-variant p-5">
                <native:row class="w-full items-center gap-2">
                    <native:icon ios="globe" android="language" :size="18" class="text-theme-primary" />
                    <native:text class="text-xs font-semibold text-theme-on-surface-variant">RECOGNITION LANGUAGE</native:text>
                </native:row>
                <native:row class="w-full gap-2 flex-wrap">
                    @foreach (\App\NativeComponents\Playgrounds\SpeechToTextPlayground::LOCALES as $tag => $name)
                        <native:button key="locale-{{ $loop->index }}" variant="{{ $locale === $tag ? 'primary' : 'secondary' }}" size="sm" label="{{ $name }}" :disabled="$listening" @press="chooseLocale('{{ $tag }}')" />
                    @endforeach
                </native:row>
                <native:toggle label="Show words while speaking" native:model="partial" />
            </native:column>

            <native:column class="w-full gap-2 rounded-3xl bg-theme-primary/10 border border-theme-primary/30 p-5">
                <native:row class="w-full items-center gap-2">
                    <native:icon ios="{{ $listening ? 'waveform' : 'text.quote' }}" android="{{ $listening ? 'graphic_eq' : 'format_quote' }}" :size="18" class="text-theme-primary" />
                    <native:text class="text-xs font-semibold text-theme-primary">{{ $listening ? 'LISTENING' : 'TRANSCRIPT' }}</native:text>
                </native:row>
                <native:text class="text-lg text-theme-on-surface">{{ $transcript !== '' ? $transcript : ($listening ? 'Start speaking…' : 'Press Listen and say something.') }}</native:text>
            </native:column>

            @if ($listening)
                <native:row class="w-full gap-3">
                    <native:button variant="primary" size="lg" label="Stop" ios-icon="stop.fill" android-icon="stop" @press="stopDictation" class="flex-1" />
                    <native:button variant="secondary" size="lg" label="Cancel" ios-icon="xmark" android-icon="close" @press="cancelDictation" class="flex-1" />
                </native:row>
            @else
                <native:button variant="primary" size="lg" label="Listen" ios-icon="mic.fill" android-icon="mic" @press="toggleDictation('transcript')" class="w-full" />
            @endif

            @include('native.partials.status', ['text' => $status])
            @include('native.playgrounds.event-log')
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('play-stt')])
        </native:column>
    </native:scroll-view>
</native:column>
