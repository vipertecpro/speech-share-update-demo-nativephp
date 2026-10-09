{{-- An AI chat with dictation, read-aloud and share. See App\NativeComponents\Assistant. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="AI Assistant" subtitle="Sample replies" back>
        <native:top-bar-action id="voice" label="{{ $voiceMode ? 'Voice mode on' : 'Voice mode off' }}" ios-icon="{{ $voiceMode ? 'headphones.circle.fill' : 'headphones' }}" android-icon="{{ $voiceMode ? 'headset_mic' : 'headphones' }}" @tap="toggleVoiceMode" />
        @if (count($messages) > 0)
            <native:top-bar-action id="share-chat" label="Share chat" ios-icon="square.and.arrow.up" android-icon="share" @tap="shareChat" />
            <native:top-bar-action id="new-chat" label="New chat" ios-icon="square.and.pencil" android-icon="edit_square" @tap="newChat" />
        @endif
    </native:top-bar>

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-3 px-4 pt-2 pb-6">
            @if (count($messages) === 0)
                <native:column class="w-full items-center gap-3 px-6 pt-10 pb-4">
                    <native:column class="w-[64] h-[64] rounded-full items-center justify-center bg-theme-primary/15">
                        <native:icon ios="sparkles" android="auto_awesome" :size="30" class="text-theme-primary" />
                    </native:column>
                    <native:text class="text-xl font-bold text-center text-theme-on-surface">What can I help with?</native:text>
                    <native:text class="text-sm text-center text-theme-on-surface-variant">Type, or tap the microphone and talk. Turn on voice mode with the headphones for a hands-free chat.</native:text>
                </native:column>

                @foreach (array_keys(\App\Support\Assistant::SUGGESTIONS) as $suggestion)
                    <native:pressable key="suggestion-{{ $loop->index }}" @press="ask({{ var_export($suggestion, true) }})" a11y-label="{{ $suggestion }}" class="w-full rounded-2xl bg-theme-surface border border-theme-outline-variant px-4 py-3">
                        <native:row class="w-full items-center gap-3">
                            <native:icon ios="text.bubble" android="chat_bubble_outline" :size="18" class="text-theme-primary" />
                            <native:text class="flex-1 text-sm text-theme-on-surface">{{ $suggestion }}</native:text>
                        </native:row>
                    </native:pressable>
                @endforeach
            @endif

            @foreach ($messages as $index => $message)
                @if ($message['role'] === 'user')
                    <native:row key="message-{{ $index }}" class="w-full justify-end pl-12">
                        <native:column class="rounded-3xl bg-theme-primary px-4 py-3">
                            <native:text class="text-base text-theme-on-primary">{{ $message['text'] }}</native:text>
                        </native:column>
                    </native:row>
                @else
                    <native:column key="message-{{ $index }}" class="w-full gap-1 pr-6">
                        <native:column class="w-full gap-2 rounded-3xl bg-theme-surface border border-theme-outline-variant px-4 py-3">
                            @foreach ($this->lines($message['text']) as $line)
                                <native:text key="line-{{ $index }}-{{ $loop->index }}" class="text-base text-theme-on-surface">{{ $line }}</native:text>
                            @endforeach
                        </native:column>
                        <native:row class="gap-4">
                            <native:button ref="read-{{ $index }}" variant="ghost" size="sm" label="{{ $this->isReading('reply-'.$index) ? 'Stop' : 'Read aloud' }}" ios-icon="{{ $this->isReading('reply-'.$index) ? 'stop.fill' : 'speaker.wave.2' }}" android-icon="{{ $this->isReading('reply-'.$index) ? 'stop' : 'volume_up' }}" @press="toggleReply({{ $index }})" />
                            <native:button ref="share-{{ $index }}" variant="ghost" size="sm" label="Share" ios-icon="square.and.arrow.up" android-icon="share" @press="shareReply({{ $index }})" />
                        </native:row>
                    </native:column>
                @endif
            @endforeach
            @if (count($messages) === 0)
                @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('assistant')])
            @endif
        </native:column>
    </native:scroll-view>

    <native:column class="w-full gap-2 px-4 pt-2 pb-6 bg-theme-background">
        @if ($listening)
            <native:row class="w-full items-center gap-2 px-2">
                <native:icon ios="waveform" android="graphic_eq" :size="16" class="text-theme-primary" />
                <native:text class="flex-1 text-sm font-bold text-theme-primary">Listening…</native:text>
                <native:button variant="ghost" size="sm" label="Cancel" @press="cancelDictation" />
            </native:row>
        @elseif ($status !== '')
            <native:text class="text-xs text-center text-theme-on-surface-variant">{{ $status }}</native:text>
        @endif

        <native:row class="w-full items-center gap-2 rounded-3xl bg-theme-surface border border-theme-outline-variant pl-4 pr-2 py-2">
            <native:bare-text-input placeholder="Message" native:model.debounce.300ms="draft" multiline max-lines="5" class="flex-1 text-base" />
            <native:button ref="mic" variant="{{ $listening ? 'destructive' : 'ghost' }}" size="sm" a11y-label="{{ $listening ? 'Stop dictation' : 'Dictate' }}" ios-icon="{{ $listening ? 'stop.fill' : 'mic.fill' }}" android-icon="{{ $listening ? 'stop' : 'mic' }}" @press="toggleDictation('draft')" />
            <native:button ref="send" variant="primary" size="sm" a11y-label="Send" ios-icon="arrow.up" android-icon="arrow_upward" :disabled="trim($draft) === ''" @press="send" />
        </native:row>
    </native:column>
</native:column>
