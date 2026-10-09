{{-- Speech to Text playground. See App\NativeComponents\Playgrounds\SpeechToTextPlayground. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Speech to Text" subtitle="Playground" back />

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:column class="w-full gap-2">
                <native:text class="text-sm font-bold text-theme-on-surface-variant">LANGUAGE</native:text>
                <native:row class="w-full gap-2 flex-wrap">
                    @foreach (\App\NativeComponents\Playgrounds\SpeechToTextPlayground::LOCALES as $tag => $name)
                        <native:button key="locale-{{ $loop->index }}" variant="{{ $locale === $tag ? 'primary' : 'secondary' }}" size="sm" label="{{ $name }}" :disabled="$listening" @press="chooseLocale('{{ $tag }}')" />
                    @endforeach
                </native:row>
            </native:column>

            <native:toggle label="Show words while speaking (partial results)" native:model="partial" />

            <native:column class="w-full gap-2 rounded-3xl bg-theme-primary/10 border border-theme-primary/40 p-4">
                <native:text class="text-xs font-bold text-theme-primary">{{ $listening ? 'LISTENING' : 'TRANSCRIPT' }}</native:text>
                <native:text class="text-lg text-theme-on-surface">{{ $transcript !== '' ? $transcript : ($listening ? 'Start speaking…' : 'Press Listen and say something.') }}</native:text>
            </native:column>

            @if ($listening)
                <native:row class="w-full gap-2">
                    <native:button variant="primary" label="Stop" ios-icon="stop.fill" android-icon="stop" @press="stopDictation" class="flex-1" />
                    <native:button variant="secondary" label="Cancel" ios-icon="xmark" android-icon="close" @press="cancelDictation" class="flex-1" />
                </native:row>
            @else
                <native:button variant="primary" size="lg" label="Listen" ios-icon="mic.fill" android-icon="mic" @press="toggleDictation('transcript')" class="w-full" />
            @endif

            @if ($status !== '')
                <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $status }}</native:text>
            @endif

            @include('native.playgrounds.event-log')
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('play-stt')])
        </native:column>
    </native:scroll-view>
</native:column>
