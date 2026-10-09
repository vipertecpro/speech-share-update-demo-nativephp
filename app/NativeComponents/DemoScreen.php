<?php

namespace App\NativeComponents;

use Native\Mobile\Edge\NativeComponent;
use PARTek\SpeechToText\Facades\SpeechToText;
use PARTek\TextToSpeech\Facades\TextToSpeech;

/**
 * A demo screen: a short status line about the last thing that happened,
 * and a hook the plugin traits call for every event they receive. The
 * playgrounds use the hook to show an event log. Leaving the screen stops
 * any reading or listening it started.
 */
abstract class DemoScreen extends NativeComponent
{
    /** A short line about the last thing that happened. */
    public string $status = '';

    /**
     * Called for every plugin event the screen receives.
     *
     * @param  array<string, mixed>  $payload
     */
    protected function noteEvent(string $event, array $payload = []): void
    {
        //
    }

    public function unmount(): void
    {
        if (($this->speaking ?? false) === true) {
            TextToSpeech::stop();
        }

        if (($this->listening ?? false) === true) {
            SpeechToText::cancel();
        }

        parent::unmount();
    }
}
