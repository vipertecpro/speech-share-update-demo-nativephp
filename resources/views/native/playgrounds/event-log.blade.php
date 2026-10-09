{{-- The playground's event log, newest first. See App\NativeComponents\Playgrounds\Playground. --}}
<native:column class="w-full gap-3 rounded-3xl bg-theme-surface border border-theme-outline-variant p-5">
    <native:row class="w-full items-center gap-2">
        <native:icon ios="list.bullet.rectangle" android="receipt_long" :size="18" class="text-theme-primary" />
        <native:text class="flex-1 text-xs font-semibold text-theme-on-surface-variant">CALLS AND EVENTS</native:text>
        @if (count($events) > 0)
            <native:button variant="ghost" size="sm" label="Clear" ios-icon="trash" android-icon="delete" @press="clearLog" />
        @endif
    </native:row>
    @forelse ($events as $index => $entry)
        @php $isCall = str_ends_with($entry['event'], ')'); @endphp
        <native:row key="event-{{ $index }}-{{ $entry['time'] }}" class="w-full gap-3">
            <native:column class="w-[28] h-[28] rounded-full items-center justify-center {{ $isCall ? 'bg-theme-surface-variant' : 'bg-theme-primary/15' }}">
                <native:icon ios="{{ $isCall ? 'arrow.up.right' : 'bolt.fill' }}" android="{{ $isCall ? 'north_east' : 'bolt' }}" :size="14" class="{{ $isCall ? 'text-theme-on-surface-variant' : 'text-theme-primary' }}" />
            </native:column>
            <native:column class="flex-1 gap-[2]">
                <native:row class="w-full items-center gap-2">
                    <native:text class="flex-1 text-sm font-mono font-semibold {{ $isCall ? 'text-theme-on-surface' : 'text-theme-primary' }}">{{ $entry['event'] }}</native:text>
                    <native:text class="text-xs font-mono text-theme-on-surface-variant">{{ $entry['time'] }}</native:text>
                </native:row>
                @if ($entry['detail'] !== '')
                    <native:text class="text-xs font-mono text-theme-on-surface-variant">{{ $entry['detail'] }}</native:text>
                @endif
            </native:column>
        </native:row>
    @empty
        <native:text class="text-sm text-theme-on-surface-variant">Calls your app makes show with an arrow, and the plugin's events with a bolt.</native:text>
    @endforelse
</native:column>
