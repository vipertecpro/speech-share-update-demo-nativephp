{{-- One note: dictate, read aloud, share. See App\NativeComponents\Note. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="{{ $noteId ? \App\Support\NoteBook::title($body) : 'New note' }}" back>
        @if ($noteId)
            <native:top-bar-action id="delete" label="Delete" ios-icon="trash" android-icon="delete" @tap="delete" />
        @endif
    </native:top-bar>

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:outlined-text-input label="Your note" placeholder="Type, or tap the microphone and speak" native:model.debounce.400ms="body" multiline min-lines="5" class="w-full" />

            @if ($listening)
                <native:column class="w-full gap-2 rounded-3xl bg-theme-primary/10 border border-theme-primary/40 p-4">
                    <native:row class="w-full items-center gap-2">
                        <native:icon ios="waveform" android="graphic_eq" :size="18" class="text-theme-primary" />
                        <native:text class="flex-1 text-sm font-bold text-theme-primary">Listening</native:text>
                        <native:button variant="ghost" size="sm" label="Cancel" @press="cancelDictation" />
                    </native:row>
                    <native:text class="text-base text-theme-on-surface">{{ $heard !== '' ? $heard : 'Start speaking…' }}</native:text>
                </native:column>
            @endif

            <native:row class="w-full gap-2">
                <native:button variant="{{ $listening ? 'destructive' : 'primary' }}" label="{{ $listening ? 'Stop' : 'Dictate' }}" ios-icon="{{ $listening ? 'stop.fill' : 'mic.fill' }}" android-icon="{{ $listening ? 'stop' : 'mic' }}" @press="toggleDictation" class="flex-1" />
                <native:button variant="secondary" label="{{ $speaking ? 'Stop' : 'Read aloud' }}" ios-icon="{{ $speaking ? 'stop.fill' : 'speaker.wave.2.fill' }}" android-icon="{{ $speaking ? 'stop' : 'volume_up' }}" @press="toggleReading" class="flex-1" />
            </native:row>
            <native:button variant="secondary" label="Share" ios-icon="square.and.arrow.up" android-icon="share" @press="share" class="w-full" />

            @if ($status !== '')
                <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $status }}</native:text>
            @endif
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('note')])
        </native:column>
    </native:scroll-view>
</native:column>
