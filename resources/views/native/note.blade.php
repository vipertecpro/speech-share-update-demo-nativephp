{{-- One note: dictate, read aloud, share. See App\NativeComponents\Note. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="{{ $noteId ? \App\Support\NoteBook::title($body) : 'New note' }}" back>
        @if ($noteId)
            <native:top-bar-action id="delete" label="Delete" ios-icon="trash" android-icon="delete" @tap="delete" />
        @endif
    </native:top-bar>

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:outlined-text-input label="Your note" placeholder="Type, or tap Dictate and speak" native:model.debounce.400ms="body" multiline min-lines="6" class="w-full" />

            @if ($listening)
                @include('native.partials.listening', ['heard' => $heard])
            @endif

            <native:row class="w-full gap-3">
                <native:button variant="{{ $listening ? 'destructive' : 'primary' }}" size="lg" label="{{ $listening ? 'Stop' : 'Dictate' }}" ios-icon="{{ $listening ? 'stop.fill' : 'mic.fill' }}" android-icon="{{ $listening ? 'stop' : 'mic' }}" @press="toggleDictation" class="flex-1" />
                <native:button variant="secondary" size="lg" label="{{ $speaking ? 'Stop' : 'Read aloud' }}" ios-icon="{{ $speaking ? 'stop.fill' : 'speaker.wave.2.fill' }}" android-icon="{{ $speaking ? 'stop' : 'volume_up' }}" @press="toggleReading" class="flex-1" />
            </native:row>
            <native:button variant="secondary" size="lg" label="Share" ios-icon="square.and.arrow.up" android-icon="share" @press="share" class="w-full" />

            @include('native.partials.status', ['text' => $listening ? '' : $status])
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('note')])
        </native:column>
    </native:scroll-view>
</native:column>
