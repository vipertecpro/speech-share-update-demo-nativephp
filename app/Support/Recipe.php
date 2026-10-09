<?php

namespace App\Support;

/**
 * The recipe in the cook mode demo.
 */
class Recipe
{
    public const NAME = 'Lemon garlic pasta';

    public const SERVES = 'Serves 2 · 20 minutes';

    /** @var list<string> */
    public const INGREDIENTS = [
        '200 g spaghetti',
        '3 tablespoons olive oil',
        '4 cloves garlic, thinly sliced',
        '1 lemon, zest and juice',
        'A pinch of chilli flakes',
        'A handful of parsley, chopped',
        '40 g grated parmesan',
    ];

    /** @var list<string> */
    public const STEPS = [
        'Bring a large pot of salted water to the boil and cook the spaghetti until just tender.',
        'While it cooks, warm the olive oil in a wide pan over a low heat and add the garlic.',
        'Let the garlic turn pale gold, about two minutes, then add the chilli flakes and lemon zest.',
        'Before draining the pasta, keep half a cup of the cooking water.',
        'Toss the spaghetti in the pan with the lemon juice and a splash of the pasta water until glossy.',
        'Take it off the heat, stir in the parmesan and parsley, and serve straight away.',
    ];

    public static function shoppingList(): string
    {
        return self::NAME."\n".implode("\n", array_map(fn (string $item): string => '• '.$item, self::INGREDIENTS));
    }
}
