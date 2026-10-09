<?php

namespace App\Support;

/**
 * What each screen gets from the plugins and what the demo builds on top,
 * shown at the bottom of the screen so nobody mistakes one for the other.
 */
class DemoNotes
{
    /**
     * Screen => what the plugins do there, and what this demo adds.
     *
     * @var array<string, array{plugins: string, demo: string}>
     */
    public const SCREENS = [
        'home' => [
            'plugins' => 'Force Update reports this build’s version against the minimum and latest you configure, and opens the store listing.',
            'demo' => 'The “update available” banner, which compares this build with the latest version.',
        ],
        'assistant' => [
            'plugins' => 'Speech to Text fills the message box as you speak. Text to Speech reads replies aloud. Native Share sends a reply or the whole chat.',
            'demo' => 'The chat, its sample replies (there is no AI model in this demo), hold-to-talk, which sends your message when you let go of the microphone, and voice mode reading replies aloud.',
        ],
        'note' => [
            'plugins' => 'Speech to Text turns speech into the note’s text. Text to Speech reads it back. Native Share sends it and reports the app that was picked.',
            'demo' => 'Saving notes, adding new dictation to the end, and names like “Gmail” worked out from the app id the plugin reports.',
        ],
        'cook' => [
            'plugins' => 'Text to Speech reads each step and reports when it finishes. Native Share sends the shopping list.',
            'demo' => 'Moving to the next step when one finishes. Android has no pause, so there Pause stops and Resume reads the step again.',
        ],
        'language' => [
            'plugins' => 'Text to Speech says the phrase in a voice for its language. Speech to Text listens in that language and returns the words it heard.',
            'demo' => 'The % match, which compares the words the phone heard with the phrase. It doesn’t judge pronunciation.',
        ],
        'report' => [
            'plugins' => 'Speech to Text fills each field. Text to Speech reads the report back. Native Share sends the file.',
            'demo' => 'The form, writing the report to a text file, and putting a field back when you cancel dictation.',
        ],
        'reader' => [
            'plugins' => 'Text to Speech reads at the speed you choose and reports when each part finishes. Native Share sends the link.',
            'demo' => 'Reading one paragraph at a time and highlighting it. Android has no pause, so there Resume reads the paragraph again.',
        ],
        'play-stt' => [
            'plugins' => 'Everything you can set here, and every event in the log.',
            'demo' => 'The log itself.',
        ],
        'play-tts' => [
            'plugins' => 'Voices, rate, pitch, stop, isSpeaking() and, on iOS, pause and resume.',
            'demo' => 'The log. Android has no pause, so there Pause stops and Resume speaks the text again.',
        ],
        'play-share' => [
            'plugins' => 'Sharing text, links and files, sending straight to one app on Android, and reporting the app that was picked.',
            'demo' => 'The log, the sample file, and names like “Gmail” worked out from the app id.',
        ],
        'play-update' => [
            'plugins' => 'status(), the store link and opening it.',
            'demo' => 'The log, and the “too old” switch, which changes the minimum version this demo configures.',
        ],
    ];

    /** @return array{plugins: string, demo: string} */
    public static function for(string $screen): array
    {
        return self::SCREENS[$screen];
    }
}
