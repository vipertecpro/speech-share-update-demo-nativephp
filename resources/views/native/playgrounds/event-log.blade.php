{{-- The playground's event log, newest first. See App\NativeComponents\Playgrounds\Playground. --}}
<native:column class="w-full gap-2 rounded-3xl bg-theme-surface border border-theme-outline-variant p-4">
    <native:row class="w-full items-center">
        <native:text class="flex-1 text-base font-bold text-theme-on-surface">Calls and events</native:text>
        @if (count($events) > 0)
            <native:button variant="ghost" size="sm" label="Clear" @press="clearLog" />
        @endif
    </native:row>
    @forelse ($events as $index => $entry)
        <native:column key="event-{{ $index }}-{{ $entry['time'] }}" class="w-full gap-1 pt-1">
            <native:row class="w-full gap-2">
                <native:text class="text-xs font-mono text-theme-on-surface-variant">{{ $entry['time'] }}</native:text>
                <native:text class="flex-1 text-sm font-mono font-bold {{ str_ends_with($entry['event'], ')') ? 'text-theme-on-surface' : 'text-theme-primary' }}">{{ $entry['event'] }}</native:text>
            </native:row>
            @if ($entry['detail'] !== '')
                <native:text class="text-xs font-mono text-theme-on-surface-variant">{{ $entry['detail'] }}</native:text>
            @endif
        </native:column>
    @empty
        <native:text class="text-sm text-theme-on-surface-variant">Your calls appear here in plain text, and the plugin's events are highlighted.</native:text>
    @endforelse
</native:column>
