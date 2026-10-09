<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Practice phrases for the language demo, and how closely what the user
 * said matches one.
 */
class Phrases
{
    /**
     * Languages by BCP 47 tag, each with a name and its phrases.
     *
     * @var array<string, array{name: string, phrases: list<array{say: string, means: string}>}>
     */
    public const LANGUAGES = [
        'es-ES' => ['name' => 'Spanish', 'phrases' => [
            ['say' => 'Buenos días', 'means' => 'Good morning'],
            ['say' => '¿Dónde está la estación?', 'means' => 'Where is the station?'],
            ['say' => 'Una mesa para dos, por favor', 'means' => 'A table for two, please'],
            ['say' => '¿Cuánto cuesta esto?', 'means' => 'How much does this cost?'],
            ['say' => 'Muchas gracias por tu ayuda', 'means' => 'Thank you very much for your help'],
        ]],
        'fr-FR' => ['name' => 'French', 'phrases' => [
            ['say' => 'Bonjour', 'means' => 'Hello'],
            ['say' => 'Où est la gare ?', 'means' => 'Where is the station?'],
            ['say' => 'Une table pour deux, s’il vous plaît', 'means' => 'A table for two, please'],
            ['say' => 'Combien ça coûte ?', 'means' => 'How much does it cost?'],
            ['say' => 'Merci beaucoup pour votre aide', 'means' => 'Thank you very much for your help'],
        ]],
        'de-DE' => ['name' => 'German', 'phrases' => [
            ['say' => 'Guten Morgen', 'means' => 'Good morning'],
            ['say' => 'Wo ist der Bahnhof?', 'means' => 'Where is the station?'],
            ['say' => 'Einen Tisch für zwei, bitte', 'means' => 'A table for two, please'],
            ['say' => 'Wie viel kostet das?', 'means' => 'How much does this cost?'],
            ['say' => 'Vielen Dank für Ihre Hilfe', 'means' => 'Thank you very much for your help'],
        ]],
    ];

    /**
     * How closely the words the recognizer returned match the phrase, from 0
     * to 100. This compares text, not pronunciation. Case, accents and
     * punctuation don't count.
     */
    public static function score(string $phrase, string $heard): int
    {
        $expected = self::normalise($phrase);
        $actual = self::normalise($heard);

        if ($expected === '' || $actual === '') {
            return 0;
        }

        similar_text($expected, $actual, $percent);

        return (int) round($percent);
    }

    public static function verdict(int $score): string
    {
        return match (true) {
            $score >= 85 => 'The phone recognised the phrase.',
            $score >= 60 => 'Close. Listen once more and try again.',
            default => 'The phone heard something else. Listen slowly, then try again.',
        };
    }

    private static function normalise(string $text): string
    {
        return Str::of(Str::ascii($text))->lower()->replaceMatches('/[^a-z0-9 ]+/', '')->squish()->toString();
    }
}
