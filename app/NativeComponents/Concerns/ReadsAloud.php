<?php

namespace App\NativeComponents\Concerns;

use Native\Mobile\Attributes\On;
use Native\Mobile\Platform;
use PARTek\TextToSpeech\Events\SpeechFailed;
use PARTek\TextToSpeech\Events\SpeechFinished;
use PARTek\TextToSpeech\Events\SpeechStarted;
use PARTek\TextToSpeech\Facades\TextToSpeech;

/**
 * Text to Speech for a screen: read something aloud in the phone's own
 * voice, with pause, resume and stop. Each reading has a key, so a screen
 * with many things to read knows which one is playing.
 *
 * iOS pauses mid-sentence. Android's speech engine has no pause, so there
 * pausing stops the sentence and resuming plays it again from its start.
 */
trait ReadsAloud
{
    public bool $speaking = false;

    public bool $paused = false;

    /** Which thing is being read, or '' when nothing is. */
    public string $readingKey = '';

    /** The last speak() call, so Android can resume by speaking it again. @var array{text?: string, locale?: ?string, rate?: ?float, pitch?: ?float} */
    public array $lastSpeech = [];

    /** Runs when a reading reaches its end on its own (not when stopped). */
    protected function afterReading(string $key): void
    {
        //
    }

    public function readAloud(string $key, string $text, ?string $locale = null, ?float $rate = null, ?float $pitch = null): void
    {
        if (trim($text) === '') {
            $this->status = 'There is nothing to read yet.';

            return;
        }

        $this->readingKey = $key;
        $this->paused = false;
        $this->lastSpeech = ['text' => $text, 'locale' => $locale, 'rate' => $rate, 'pitch' => $pitch];
        $this->speaking = TextToSpeech::speak($text, $locale, $rate, $pitch);
        $this->noteEvent('speak()', array_filter(['locale' => $locale, 'rate' => $rate, 'pitch' => $pitch], fn ($value) => $value !== null));

        if (! $this->speaking) {
            $this->readingKey = '';
            $this->status = 'Reading aloud could not start.';
        }
    }

    public function stopReading(): void
    {
        TextToSpeech::stop();
        $this->speaking = false;
        $this->paused = false;
        $this->readingKey = '';
        $this->status = '';
        $this->noteEvent('stop()');
    }

    public function pauseReading(): void
    {
        if (Platform::isAndroid()) {
            TextToSpeech::stop();
            $this->paused = true;
            $this->status = 'Paused. Resume starts this part again.';
            $this->noteEvent('stop()', ['for' => 'pause on Android']);

            return;
        }

        if (TextToSpeech::pause()) {
            $this->paused = true;
            $this->status = 'Paused.';
        }

        $this->noteEvent('pause()');
    }

    public function resumeReading(): void
    {
        if (Platform::isAndroid()) {
            $this->readAloud($this->readingKey, $this->lastSpeech['text'] ?? '', $this->lastSpeech['locale'] ?? null, $this->lastSpeech['rate'] ?? null, $this->lastSpeech['pitch'] ?? null);

            return;
        }

        if (TextToSpeech::resume()) {
            $this->paused = false;
            $this->status = 'Reading aloud.';
        }

        $this->noteEvent('resume()');
    }

    #[On(SpeechStarted::class)]
    public function onReadingStarted(?string $text = null): void
    {
        $this->speaking = true;
        $this->status = 'Reading aloud.';
        $this->noteEvent('SpeechStarted');
    }

    #[On(SpeechFinished::class)]
    public function onReadingFinished(?string $text = null): void
    {
        if ($this->paused) {
            $this->noteEvent('SpeechFinished', ['while' => 'paused']);

            return;
        }

        $key = $this->readingKey;

        $this->speaking = false;
        $this->paused = false;
        $this->readingKey = '';
        $this->status = 'Finished reading.';
        $this->noteEvent('SpeechFinished');

        if ($key !== '') {
            $this->afterReading($key);
        }
    }

    #[On(SpeechFailed::class)]
    public function onReadingFailed(string $message, ?string $text = null): void
    {
        $this->speaking = false;
        $this->paused = false;
        $this->readingKey = '';
        $this->status = $message;
        $this->noteEvent('SpeechFailed', ['message' => $message]);
    }

    public function isReading(string $key): bool
    {
        return $this->speaking && $this->readingKey === $key;
    }
}
