{{-- The notes list. See App\NativeComponents\Notes. --}}
<native:column class="w-full h-full bg-theme-background">
    <native:top-bar title="Voice Notes" subtitle="{{ count($notes) === 1 ? '1 note' : count($notes).' notes' }}" back />

    <native:scroll-view class="w-full flex-1">
        <native:column class="w-full gap-3 px-4 pt-2 pb-28">
            @forelse ($notes as $note)
                <native:pressable key="note-{{ $note['id'] }}" @navigate="'/note/' . $note['id']" a11y-label="{{ $this->title($note['body']) }}" class="w-full rounded-3xl bg-theme-surface border border-theme-outline-variant p-4">
                    <native:column class="w-full gap-1">
                        <native:text class="text-base font-bold text-theme-on-surface">{{ $this->title($note['body']) }}</native:text>
                        <native:text class="text-sm text-theme-on-surface-variant">{{ $this->preview($note['body']) }}</native:text>
                        <native:text class="text-xs text-theme-on-surface-variant">{{ $this->when($note['updated_at']) }}</native:text>
                    </native:column>
                </native:pressable>
            @empty
                <native:column class="w-full items-center gap-3 rounded-3xl bg-theme-surface border border-theme-outline-variant px-6 py-10">
                    <native:column class="w-[64] h-[64] rounded-full items-center justify-center bg-theme-primary/15">
                        <native:icon ios="mic.fill" android="mic" :size="30" class="text-theme-primary" />
                    </native:column>
                    <native:text class="text-lg font-bold text-center text-theme-on-surface">Say it, keep it, share it</native:text>
                    <native:text class="text-sm text-center text-theme-on-surface-variant">Dictate a note, hear it read back, and send it to anyone.</native:text>
                </native:column>
            @endforelse
        </native:column>
    </native:scroll-view>

    <native:column class="w-full px-4 pb-6 pt-2 bg-theme-background">
        <native:button variant="primary" size="lg" label="New note" ios-icon="square.and.pencil" android-icon="edit" @press="navigate('/note/new')" class="w-full" />
    </native:column>
</native:column>
