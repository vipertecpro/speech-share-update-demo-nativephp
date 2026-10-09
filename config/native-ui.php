<?php

/**
 * Native UI — Theme Tokens
 *
 * Published via `php artisan vendor:publish --tag=native-ui-config`.
 * Edit to customize your app's visual identity in one place.
 *
 * For dynamic per-tenant theming, use Native\Mobile\UI\Theme::merge([...])
 * from a service provider. Runtime merges deep-merge on top of these values.
 *
 * Decision log: /docs/NATIVE-UI-REWRITE-PLAN.md (D — theme layer)
 */

return [

    /*
    |---------------------------------------------------------------------------
    | Theme
    |---------------------------------------------------------------------------
    |
    | Color tokens (open-ended map), 4 radii, 4 font sizes, font family.
    |
    | "on-X" means "color of content placed ON a surface of color X"
    |   — i.e., text/icons on that background.
    |
    | The token map is OPEN-ENDED: add any key your design needs (e.g. a
    | `warning` pair) to both blocks and `bg-theme-warning` /
    | `text-theme-on-warning` / `border-theme-warning` resolve immediately.
    | Theme classes also accept opacity modifiers — `bg-theme-primary/15`
    | is the tonal-fill idiom (the alpha applies to the dark companion
    | too). In PHP (layout chrome builders, dynamic styling) read tokens
    | with the appearance-aware `theme()` helper: `theme('primary')`.
    |
    | Color tokens accept:
    |   - CSS hex: '#B91C1C', '#F00', or with alpha '#8B5CF680' (#RRGGBBAA)
    |   - Tailwind palette names: 'red-300', 'orange-800'
    |   - Opacity modifiers on either: 'red-300/20', '#8B5CF6/50'
    |
    | Dark mode is auto-derived from `light` when `dark` is not set. To opt
    | into explicit dark tokens, fill out the `dark` block.
    |
    | The default pairs meet WCAG AA (4.5:1) — if you customize, keep each
    | `on-*` color at 4.5:1 contrast against its background token.
    |
    */

    'theme' => [

        // The vipertecpro.com palette. Light: warm off-white pages, white
        // cards and a deep coral that reads on white. Dark: charcoal pages,
        // slightly lighter cards and the brand coral. Sage (success) and
        // amber (accent) are the site's secondary colours.
        'light' => [
            'primary' => '#B8411A',
            'on-primary' => '#FFFFFF',

            'secondary' => '#E9E7E4',
            'on-secondary' => '#1C1D21',

            'surface' => '#FFFFFF',
            'on-surface' => '#1C1D21',
            'background' => '#F4F3F1',
            'on-background' => '#1C1D21',

            'surface-variant' => '#EBE9E6',
            'on-surface-variant' => '#5B5C63',

            'outline' => '#CFCCC7',
            'outline-variant' => '#E4E1DD',

            'destructive' => '#C42B2B',
            'on-destructive' => '#FFFFFF',

            'success' => '#2F7A48',
            'on-success' => '#FFFFFF',

            'accent' => '#A35F12',
            'on-accent' => '#FFFFFF',
        ],

        'dark' => [
            'primary' => '#F26A3D',
            'on-primary' => '#18191C',

            'secondary' => '#38393E',
            'on-secondary' => '#F4F4F5',

            'surface' => '#2C2D31',
            'on-surface' => '#F4F4F5',
            'background' => '#222327',
            'on-background' => '#F4F4F5',

            'surface-variant' => '#36373C',
            'on-surface-variant' => '#A6A6AD',

            'outline' => '#4A4B52',
            'outline-variant' => '#38393E',

            'destructive' => '#F47B6B',
            'on-destructive' => '#18191C',

            'success' => '#7CC08D',
            'on-success' => '#0E2415',

            'accent' => '#F0B06A',
            'on-accent' => '#18191C',
        ],

        'radius-sm' => 4,
        'radius-md' => 8,
        'radius-lg' => 16,
        'radius-full' => 9999,

        // Font size scale (points / sp).
        'font-sm' => 14,
        'font-md' => 16,
        'font-lg' => 20,
        'font-xl' => 24,

    ],

    /*
    |---------------------------------------------------------------------------
    | Fonts
    |---------------------------------------------------------------------------
    |
    | Semantic names for bundled fonts (resources/fonts/ file tokens, minus
    | the extension). Use an alias anywhere a font token works — the `font`
    | attribute (`font="accent"`), chrome ->font() builders, or the layout
    | $font property. The `default` alias is the app-wide default font:
    | 'System' resolves to the platform face (San Francisco on iOS, Roboto
    | on Android); set a bundled token to apply it everywhere. Download one
    | with `php artisan native:font Inter --default`. Per-element `font`
    | attributes and font-serif / font-mono classes still win over the default.
    |
    |   'fonts' => [
    |       'default' => 'Inter-Regular',
    |       'accent'  => 'DynaPuff-Regular',
    |   ],
    |
    */

    'fonts' => [
        'default' => 'System',
    ],

];
