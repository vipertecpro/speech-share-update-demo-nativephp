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
        <native:column class="w-full gap-4 px-4 pt-2 pb-6">
            @if (count($messages) === 0)
                <native:column class="w-full items-center gap-3 px-4 pt-8 pb-2">
                    <native:column class="w-[72] h-[72] rounded-full items-center justify-center bg-theme-primary">
                        <native:icon ios="sparkles" android="auto_awesome" :size="34" class="text-theme-on-primary" />
                    </native:column>
                    <native:text class="text-2xl font-bold text-center text-theme-on-surface">What can I help with?</native:text>
                    <native:text class="text-sm text-center text-theme-on-surface-variant">Type, or hold the microphone and talk. It sends when you let go, and the headphones read every reply aloud.</native:text>
                </native:column>

                @include('native.partials.section-label', ['text' => 'TRY ASKING'])
                @foreach (array_keys(\App\Support\Assistant::SUGGESTIONS) as $suggestion)
                    <native:pressable key="suggestion-{{ $loop->index }}" @press="ask({{ var_export($suggestion, true) }})" a11y-label="{{ $suggestion }}" class="w-full rounded-2xl bg-theme-surface border border-theme-outline-variant px-4 py-3">
                        <native:row class="w-full items-center gap-3">
                            <native:icon ios="text.bubble.fill" android="chat_bubble" :size="18" class="text-theme-primary" />
                            <native:text class="flex-1 text-sm font-semibold text-theme-on-surface">{{ $suggestion }}</native:text>
                            <native:icon ios="arrow.up.right" android="north_east" :size="14" class="text-theme-on-surface-variant" />
                        </native:row>
                    </native:pressable>
                @endforeach

                @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('assistant')])
            @endif

            @foreach ($messages as $index => $message)
                @if ($message['role'] === 'user')
                    <native:row key="message-{{ $index }}" class="w-full justify-end pl-12">
                        <native:column class="rounded-3xl bg-theme-primary px-4 py-3">
                            <native:text class="text-base text-theme-on-primary">{{ $message['text'] }}</native:text>
                        </native:column>
                    </native:row>
                @else
                    <native:row key="message-{{ $index }}" class="w-full gap-3 pr-4">
                        <native:column class="w-[32] h-[32] rounded-full items-center justify-center bg-theme-primary/15">
                            <native:icon ios="sparkles" android="auto_awesome" :size="16" class="text-theme-primary" />
                        </native:column>
                        <native:column class="flex-1 gap-2">
                            <native:column class="w-full gap-2 rounded-3xl bg-theme-surface border border-theme-outline-variant px-4 py-3">
                                @foreach ($this->lines($message['text']) as $line)
                                    <native:text key="line-{{ $index }}-{{ $loop->index }}" class="text-base text-theme-on-surface">{{ $line }}</native:text>
                                @endforeach
                            </native:column>
                            <native:row class="gap-2">
                                <native:button ref="read-{{ $index }}" variant="ghost" size="sm" label="{{ $this->isReading('reply-'.$index) ? 'Stop' : 'Read aloud' }}" ios-icon="{{ $this->isReading('reply-'.$index) ? 'stop.fill' : 'speaker.wave.2.fill' }}" android-icon="{{ $this->isReading('reply-'.$index) ? 'stop' : 'volume_up' }}" @press="toggleReply({{ $index }})" />
                                <native:button ref="share-{{ $index }}" variant="ghost" size="sm" label="Share" ios-icon="square.and.arrow.up" android-icon="share" @press="shareReply({{ $index }})" />
                            </native:row>
                        </native:column>
                    </native:row>
                @endif
            @endforeach
        </native:column>
    </native:scroll-view>

    <native:column class="w-full gap-2 px-4 pt-2 pb-6 bg-theme-background">
        @if ($listening)
            @include('native.partials.listening', ['heard' => $heard, 'hint' => $holdStartedAt !== null ? 'Let go to send' : null])
        @elseif ($status !== '')
            <native:text class="text-xs text-center text-theme-on-surface-variant">{{ $status }}</native:text>
        @endif

        <native:row key="composer" class="w-full items-center gap-2 rounded-full bg-theme-surface border border-theme-outline-variant pl-5 pr-2 py-2">
            <native:bare-text-input placeholder="Message" native:model.debounce.300ms="draft" multiline max-lines="5" class="flex-1 text-base" />
            <native:pressable key="hold-to-talk" @tapDown="startHold" @tapUp="endHold" a11y-label="Hold to talk" class="w-[44] h-[44] rounded-full items-center justify-center {{ $listening ? 'bg-theme-destructive' : 'bg-theme-primary/15' }}">
                <native:icon ios="mic.fill" android="mic" :size="22" class="{{ $listening ? 'text-theme-on-destructive' : 'text-theme-primary' }}" />
            </native:pressable>
            <native:button ref="send" variant="primary" size="sm" a11y-label="Send" ios-icon="arrow.up" android-icon="arrow_upward" :disabled="trim($draft) === ''" @press="send" />
        </native:row>
    </native:column>
</native:column>
