{{--
    A tappable card that opens a screen. Expects $route, $title, $text, $ios and $android (icon names);
    optional $chips (list of labels, shown as one line), $chipsMono, $chipsIos, $chipsAndroid and $key.
--}}
<native:pressable key="{{ $key ?? 'nav-'.$route }}" @navigate="$route" a11y-label="{{ $title }}" class="w-full rounded-3xl bg-theme-surface border border-theme-outline-variant p-4">
    <native:row class="w-full items-center gap-4">
        <native:column class="w-[52] h-[52] rounded-2xl items-center justify-center bg-theme-primary/15">
            <native:icon :ios="$ios" :android="$android" :size="26" class="text-theme-primary" />
        </native:column>
        <native:column class="flex-1 gap-1">
            <native:text class="text-base font-semibold text-theme-on-surface">{{ $title }}</native:text>
            <native:text class="text-sm text-theme-on-surface-variant">{{ $text }}</native:text>
            @if (! empty($chips))
                <native:row class="w-full items-center gap-2 pt-1">
                    <native:icon :ios="$chipsIos ?? 'puzzlepiece.extension.fill'" :android="$chipsAndroid ?? 'extension'" :size="12" class="text-theme-primary" />
                    <native:text class="flex-1 text-xs font-semibold {{ ($chipsMono ?? false) ? 'font-mono ' : '' }}text-theme-on-surface-variant">{{ implode(' · ', $chips) }}</native:text>
                </native:row>
            @endif
        </native:column>
        <native:icon ios="chevron.right" android="chevron_right" :size="18" class="text-theme-on-surface-variant" />
    </native:row>
</native:pressable>
