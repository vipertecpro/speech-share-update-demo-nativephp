{{-- A form filled in by voice. See App\NativeComponents\FieldReport. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Field Report" subtitle="Site inspection" back>
        <native:top-bar-action id="clear" label="Clear form" ios-icon="trash" android-icon="delete" @tap="clearForm" />
    </native:top-bar>

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:row class="w-full items-center gap-3 rounded-2xl bg-theme-surface-variant px-4 py-3">
                <native:icon ios="mic.circle.fill" android="mic" :size="20" class="text-theme-primary" />
                <native:text class="flex-1 text-sm text-theme-on-surface">Tap a microphone and describe what you see. Your words go into that field.</native:text>
            </native:row>

            <native:column class="w-full gap-4 rounded-3xl bg-theme-surface border border-theme-outline-variant p-4">
                @foreach (\App\NativeComponents\FieldReport::FIELDS as $field => $label)
                    <native:column key="field-{{ $field }}" class="w-full gap-2">
                        <native:row class="w-full items-center gap-3">
                            <native:outlined-text-input label="{{ $label }}" native:model.debounce.300ms="{{ $field }}" multiline min-lines="{{ $field === 'site' ? 1 : 3 }}" class="flex-1" />
                            <native:button ref="mic-{{ $field }}" variant="{{ $listening && $dictatingInto === $field ? 'destructive' : 'secondary' }}" a11y-label="{{ $listening && $dictatingInto === $field ? 'Stop dictation' : 'Dictate '.$label }}" ios-icon="{{ $listening && $dictatingInto === $field ? 'stop.fill' : 'mic.fill' }}" android-icon="{{ $listening && $dictatingInto === $field ? 'stop' : 'mic' }}" :disabled="$listening && $dictatingInto !== $field" @press="toggleDictation('{{ $field }}')" />
                        </native:row>
                        @if ($listening && $dictatingInto === $field)
                            @include('native.partials.listening', ['heard' => $heard])
                        @endif
                    </native:column>
                @endforeach
            </native:column>

            <native:row class="w-full gap-3">
                <native:button variant="secondary" size="lg" label="{{ $this->isReading('report') ? 'Stop' : 'Read back' }}" ios-icon="{{ $this->isReading('report') ? 'stop.fill' : 'speaker.wave.2.fill' }}" android-icon="{{ $this->isReading('report') ? 'stop' : 'volume_up' }}" @press="readBack" class="flex-1" />
                <native:button variant="primary" size="lg" label="Send report" ios-icon="paperplane.fill" android-icon="send" @press="shareReport" class="flex-1" />
            </native:row>

            @include('native.partials.status', ['text' => $listening ? '' : $status])
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('report')])
        </native:column>
    </native:scroll-view>
</native:column>
