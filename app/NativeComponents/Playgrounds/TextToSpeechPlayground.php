<?php

namespace App\NativeComponents\Playgrounds;

use App\NativeComponents\Concerns\ReadsAloud;
use Illuminate\View\View;
use PARTek\TextToSpeech\Facades\TextToSpeech;

/**
 * Text to Speech with every option: voice language, rate and pitch, and
 * pause, resume, stop and isSpeaking().
 */
class TextToSpeechPlayground extends Playground
{
    use ReadsAloud;

    /**
     * Voices to try: BCP 47 tag => label. '' uses the phone's voice.
     *
     * @var array<string, string>
     */
    public const LOCALES = ['' => 'Phone', 'en-US' => 'US', 'en-GB' => 'UK', 'en-IN' => 'India', 'en-AU' => 'Australia'];

    public string $text = 'The quick brown fox jumps over the lazy dog. Change the voice, rate and pitch, then press Speak to hear the difference.';

    public string $locale = '';

    /** Send the rate and pitch below; off leaves both to the phone. */
    public bool $customVoice = false;

    /** On the plugin's scale: 0.1 to 1.0, where 0.5 is normal. */
    public float $rate = 0.5;

    /** 0.5 to 2.0, where 1.0 is normal. */
    public float $pitch = 1.0;

    public function speak(): void
    {
        $this->readAloud(
            'playground',
            $this->text,
            $this->locale !== '' ? $this->locale : null,
            $this->customVoice ? round($this->rate, 2) : null,
            $this->customVoice ? round($this->pitch, 2) : null,
        );
    }

    public function chooseLocale(string $locale): void
    {
        $this->locale = array_key_exists($locale, self::LOCALES) ? $locale : '';
    }

    public function checkSpeaking(): void
    {
        $speaking = TextToSpeech::isSpeaking();

        $this->status = $speaking ? 'isSpeaking() says yes.' : 'isSpeaking() says no.';
        $this->noteEvent('isSpeaking()', ['result' => $speaking]);
    }

    public function resetVoice(): void
    {
        $this->customVoice = false;
        $this->rate = 0.5;
        $this->pitch = 1.0;
        $this->locale = '';
    }

    public function render(): View
    {
        return view('native.playgrounds.text-to-speech');
    }
}
