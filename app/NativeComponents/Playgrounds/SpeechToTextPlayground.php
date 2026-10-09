<?php

namespace App\NativeComponents\Playgrounds;

use App\NativeComponents\Concerns\Dictates;
use Illuminate\View\View;
use PARTek\SpeechToText\Facades\SpeechToText;

/**
 * Speech to Text with every option: the language to recognise, whether to
 * show words while the user is speaking, and stop versus cancel.
 */
class SpeechToTextPlayground extends Playground
{
    use Dictates;

    /**
     * Languages to try: BCP 47 tag => label. '' uses the phone's language.
     *
     * @var array<string, string>
     */
    public const LOCALES = ['' => 'Phone', 'en-US' => 'English (US)', 'en-GB' => 'English (UK)', 'hi-IN' => 'Hindi', 'es-ES' => 'Spanish'];

    public string $locale = '';

    public bool $partial = true;

    public string $transcript = '';

    public function chooseLocale(string $locale): void
    {
        $this->locale = array_key_exists($locale, self::LOCALES) ? $locale : '';
    }

    public function stopDictation(): void
    {
        SpeechToText::stop();
        $this->noteEvent('stop()');
    }

    protected function dictationLocale(): ?string
    {
        return $this->locale !== '' ? $this->locale : null;
    }

    protected function wantsLiveDictation(): bool
    {
        return $this->partial;
    }

    protected function dictationField(string $field): string
    {
        return '';
    }

    protected function fillDictationField(string $field, string $text): void
    {
        $this->transcript = $text;
    }

    public function render(): View
    {
        return view('native.playgrounds.speech-to-text');
    }
}
