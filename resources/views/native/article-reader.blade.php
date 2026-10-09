{{-- An article read aloud. See App\NativeComponents\ArticleReader. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Article Reader" back>
        <native:top-bar-action id="share-article" label="Share link" ios-icon="square.and.arrow.up" android-icon="share" @tap="shareArticle" />
    </native:top-bar>

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:column class="w-full gap-1">
                <native:text class="text-2xl font-bold text-theme-on-surface">{{ \App\Support\Article::TITLE }}</native:text>
                <native:text class="text-sm text-theme-on-surface-variant">{{ \App\Support\Article::BYLINE }}</native:text>
            </native:column>

            @foreach (\App\Support\Article::PARAGRAPHS as $index => $text)
                <native:pressable key="paragraph-{{ $index }}" @press="playFrom({{ $index }})" a11y-label="Play from paragraph {{ $index + 1 }}" class="w-full rounded-2xl p-3 {{ $paragraph === $index ? 'bg-theme-primary/10 border border-theme-primary/40' : '' }}">
                    <native:text class="text-base text-theme-on-surface">{{ $text }}</native:text>
                </native:pressable>
            @endforeach
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('reader')])
        </native:column>
    </native:scroll-view>

    <native:column class="w-full gap-3 px-4 pt-3 pb-6 bg-theme-surface border-t border-theme-outline-variant">
        <native:row class="w-full items-center gap-2">
            <native:text class="text-xs font-bold text-theme-on-surface-variant">SPEED</native:text>
            @foreach (\App\NativeComponents\ArticleReader::SPEEDS as $index => $speed)
                <native:button key="speed-{{ $index }}" variant="{{ $rate === $speed['rate'] ? 'primary' : 'ghost' }}" size="sm" label="{{ $speed['label'] }}" @press="chooseSpeed({{ $index }})" />
            @endforeach
        </native:row>
        <native:row class="w-full gap-2">
            @if (! $speaking)
                <native:button ref="play" variant="primary" label="{{ $paragraph === null ? 'Listen' : 'Continue' }}" ios-icon="play.fill" android-icon="play_arrow" @press="play" class="flex-1" />
            @elseif ($paused)
                <native:button variant="primary" label="Resume" ios-icon="play.fill" android-icon="play_arrow" @press="resumeReading" class="flex-1" />
            @else
                <native:button variant="primary" label="Pause" ios-icon="pause.fill" android-icon="pause" @press="pauseReading" class="flex-1" />
            @endif
            @if ($speaking)
                <native:button variant="secondary" label="Stop" ios-icon="stop.fill" android-icon="stop" @press="stopListening" class="flex-1" />
            @endif
        </native:row>
        @if ($status !== '')
            <native:text class="text-xs text-center text-theme-on-surface-variant">{{ $status }}</native:text>
        @endif
    </native:column>
</native:column>
