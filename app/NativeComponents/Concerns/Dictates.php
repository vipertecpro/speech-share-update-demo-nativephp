<?php

namespace App\NativeComponents\Concerns;

use Native\Mobile\Attributes\On;
use PARTek\SpeechToText\Events\PermissionDenied;
use PARTek\SpeechToText\Events\SpeechFailed;
use PARTek\SpeechToText\Events\SpeechPartial;
use PARTek\SpeechToText\Events\SpeechResult;
use PARTek\SpeechToText\Facades\SpeechToText;
use PARTek\TextToSpeech\Facades\TextToSpeech;

/**
 * Speech to Text for a screen: dictate into one of its fields. The words
 * appear in the field while the user is still speaking, after whatever was
 * already there, and stay when they finish.
 */
trait Dictates
{
    public bool $listening = false;

    /** The field being dictated into. */
    public string $dictatingInto = '';

    /** What the field held before dictation started. */
    public string $dictationBase = '';

    /** What the recognizer has heard so far. */
    public string $heard = '';

    /** The text of a field, by name. */
    abstract protected function dictationField(string $field): string;

    /** Replace the text of a field, by name. */
    abstract protected function fillDictationField(string $field, string $text): void;

    /** Language to recognise, as a BCP 47 tag. Null uses the phone's language. */
    protected function dictationLocale(): ?string
    {
        return null;
    }

    /** Whether to show words while the user is still speaking. */
    protected function wantsLiveDictation(): bool
    {
        return true;
    }

    /** Runs once the recognizer has the final words. */
    protected function afterDictation(string $field): void
    {
        //
    }

    public function toggleDictation(string $field = 'text'): void
    {
        if ($this->listening) {
            SpeechToText::stop();

            return;
        }

        TextToSpeech::stop();

        if (property_exists($this, 'speaking')) {
            $this->speaking = false;
        }

        $this->dictatingInto = $field;
        $this->dictationBase = $this->dictationField($field);
        $this->heard = '';
        $this->listening = SpeechToText::listen($this->dictationLocale(), $this->wantsLiveDictation());
        $this->status = $this->listening ? 'Listening… tap the microphone again when you are done.' : 'Dictation could not start.';
        $this->noteEvent('listen()', ['locale' => $this->dictationLocale() ?? 'device', 'partial' => $this->wantsLiveDictation()]);
    }

    public function cancelDictation(): void
    {
        SpeechToText::cancel();

        if ($this->dictatingInto !== '') {
            $this->fillDictationField($this->dictatingInto, $this->dictationBase);
        }

        $this->listening = false;
        $this->heard = '';
        $this->status = 'Dictation cancelled.';
        $this->noteEvent('cancel()');
    }

    #[On(SpeechPartial::class)]
    public function onHeard(string $text): void
    {
        $this->heard = $text;
        $this->fillDictationField($this->dictatingInto, $this->joinDictation($text));
        $this->noteEvent('SpeechPartial', ['text' => $text]);
    }

    #[On(SpeechResult::class)]
    public function onDictated(string $text, bool $isFinal = true): void
    {
        $this->heard = $text;
        $this->fillDictationField($this->dictatingInto, $this->joinDictation($text));
        $this->noteEvent('SpeechResult', ['text' => $text, 'isFinal' => $isFinal]);

        if ($isFinal) {
            $this->listening = false;
            $this->status = trim($text) === '' ? 'Nothing was heard.' : 'Got it.';
            $this->afterDictation($this->dictatingInto);
        }
    }

    #[On(SpeechFailed::class)]
    public function onDictationFailed(string $message): void
    {
        $this->listening = false;
        $this->status = $message;
        $this->noteEvent('SpeechFailed', ['message' => $message]);
    }

    #[On(PermissionDenied::class)]
    public function onMicrophoneDenied(?string $message = null): void
    {
        $this->listening = false;
        $this->status = 'Dictation needs the microphone. You can allow it in Settings.';
        $this->noteEvent('PermissionDenied', ['message' => $message]);
    }

    private function joinDictation(string $text): string
    {
        $base = rtrim($this->dictationBase);

        return trim($text) === '' ? $base : trim($base === '' ? trim($text) : $base.' '.trim($text));
    }
}
