{{-- An article read aloud. See App\NativeComponents\ArticleReader. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Article Reader" back>
        <native:top-bar-action id="share-article" label="Share link" ios-icon="square.and.arrow.up" android-icon="share" @tap="shareArticle" />
    </native:top-bar>

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-3 px-4 pt-2 pb-8">
            <native:column class="w-full gap-2 pb-2">
                @include('native.partials.chip', ['label' => \App\Support\Article::BYLINE, 'tone' => 'primary'])
                <native:text class="text-3xl font-bold text-theme-on-surface">{{ \App\Support\Article::TITLE }}</native:text>
            </native:column>

            @foreach (\App\Support\Article::PARAGRAPHS as $index => $text)
                <native:pressable key="paragraph-{{ $index }}" @press="playFrom({{ $index }})" a11y-label="Play from paragraph {{ $index + 1 }}" class="w-full rounded-2xl px-4 py-3 border {{ $paragraph === $index ? 'bg-theme-primary/10 border-theme-primary/40' : 'border-theme-background' }}">
                    <native:row class="w-full gap-3">
                        @if ($paragraph === $index)
                            <native:icon ios="speaker.wave.2.fill" android="volume_up" :size="16" class="text-theme-primary mt-1" />
                        @endif
                        <native:text class="flex-1 text-base text-theme-on-surface">{{ $text }}</native:text>
                    </native:row>
                </native:pressable>
            @endforeach

            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('reader')])
        </native:column>
    </native:scroll-view>

    <native:column class="w-full gap-3 px-4 pt-3 pb-6 bg-theme-surface border-t border-theme-outline-variant">
        <native:row class="w-full items-center gap-2">
            <native:icon ios="gauge.with.dots.needle.50percent" android="speed" :size="16" class="text-theme-on-surface-variant" />
            @foreach (\App\NativeComponents\ArticleReader::SPEEDS as $index => $speed)
                <native:button key="speed-{{ $index }}" variant="{{ $rate === $speed['rate'] ? 'primary' : 'ghost' }}" size="sm" label="{{ $speed['label'] }}" @press="chooseSpeed({{ $index }})" />
            @endforeach
        </native:row>
        <native:row class="w-full gap-3">
            @if (! $speaking)
                <native:button ref="play" variant="primary" size="lg" label="{{ $paragraph === null ? 'Listen' : 'Continue' }}" ios-icon="play.fill" android-icon="play_arrow" @press="play" class="flex-1" />
            @elseif ($paused)
                <native:button variant="primary" size="lg" label="Resume" ios-icon="play.fill" android-icon="play_arrow" @press="resumeReading" class="flex-1" />
            @else
                <native:button variant="primary" size="lg" label="Pause" ios-icon="pause.fill" android-icon="pause" @press="pauseReading" class="flex-1" />
            @endif
            @if ($speaking)
                <native:button variant="secondary" size="lg" label="Stop" ios-icon="stop.fill" android-icon="stop" @press="stopListening" class="flex-1" />
            @endif
        </native:row>
        @if ($status !== '')
            <native:text class="text-xs text-center text-theme-on-surface-variant">{{ $status }}</native:text>
        @endif
    </native:column>
</native:column>
