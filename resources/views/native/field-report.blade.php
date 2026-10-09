{{-- A form filled in by voice. See App\NativeComponents\FieldReport. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Field Report" subtitle="Site inspection" back>
        <native:top-bar-action id="clear" label="Clear form" ios-icon="trash" android-icon="delete" @tap="clearForm" />
    </native:top-bar>

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-4 px-4 pt-2 pb-8">
            <native:text class="text-sm text-theme-on-surface-variant">Tap a microphone and describe what you see. Your words go into that field.</native:text>

            @foreach (\App\NativeComponents\FieldReport::FIELDS as $field => $label)
                <native:column key="field-{{ $field }}" class="w-full gap-2">
                    <native:row class="w-full items-center gap-2">
                        <native:outlined-text-input label="{{ $label }}" native:model.debounce.300ms="{{ $field }}" multiline min-lines="{{ $field === 'site' ? 1 : 3 }}" class="flex-1" />
                        <native:button ref="mic-{{ $field }}" variant="{{ $listening && $dictatingInto === $field ? 'destructive' : 'secondary' }}" a11y-label="{{ $listening && $dictatingInto === $field ? 'Stop dictation' : 'Dictate '.$label }}" ios-icon="{{ $listening && $dictatingInto === $field ? 'stop.fill' : 'mic.fill' }}" android-icon="{{ $listening && $dictatingInto === $field ? 'stop' : 'mic' }}" :disabled="$listening && $dictatingInto !== $field" @press="toggleDictation('{{ $field }}')" />
                    </native:row>
                    @if ($listening && $dictatingInto === $field)
                        <native:row class="w-full items-center gap-2 px-1">
                            <native:icon ios="waveform" android="graphic_eq" :size="14" class="text-theme-primary" />
                            <native:text class="flex-1 text-xs font-bold text-theme-primary">Listening…</native:text>
                            <native:button variant="ghost" size="sm" label="Cancel" @press="cancelDictation" />
                        </native:row>
                    @endif
                </native:column>
            @endforeach

            <native:row class="w-full gap-2 pt-2">
                <native:button variant="secondary" label="{{ $this->isReading('report') ? 'Stop' : 'Read back' }}" ios-icon="{{ $this->isReading('report') ? 'stop.fill' : 'speaker.wave.2.fill' }}" android-icon="{{ $this->isReading('report') ? 'stop' : 'volume_up' }}" @press="readBack" class="flex-1" />
                <native:button variant="primary" label="Send report" ios-icon="doc.text" android-icon="description" @press="shareReport" class="flex-1" />
            </native:row>

            @if ($status !== '' && ! $listening)
                <native:text class="text-sm text-center text-theme-on-surface-variant">{{ $status }}</native:text>
            @endif
            @include('native.partials.how-it-works', ['howItWorks' => \App\Support\DemoNotes::for('report')])
        </native:column>
    </native:scroll-view>
</native:column>
