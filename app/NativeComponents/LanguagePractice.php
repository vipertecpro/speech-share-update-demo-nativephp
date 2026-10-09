<?php

namespace App\NativeComponents;

use App\NativeComponents\Concerns\Dictates;
use App\NativeComponents\Concerns\ReadsAloud;
use App\NativeComponents\Concerns\Shares;
use App\Support\Phrases;
use Illuminate\View\View;

/**
 * Pronunciation practice. Text to Speech says the phrase in a voice for
 * that language (and slowly, on request), Speech to Text listens in the
 * same language, and the screen scores how close you were. Native Share
 * sends your progress.
 */
class LanguagePractice extends DemoScreen
{
    use Dictates;
    use ReadsAloud;
    use Shares;

    public string $language = 'es-ES';

    public int $phrase = 0;

    /** What the recognizer heard for this phrase. */
    public string $attempt = '';

    public ?int $score = null;

    /** Best score per phrase, by "language:index". @var array<string, int> */
    public array $best = [];

    public function chooseLanguage(string $language): void
    {
        if (! isset(Phrases::LANGUAGES[$language])) {
            return;
        }

        $this->stopReading();
        $this->language = $language;
        $this->phrase = 0;
        $this->clearAttempt();
    }

    public function listen(): void
    {
        $this->readAloud('phrase', $this->current()['say'], $this->language);
    }

    public function listenSlowly(): void
    {
        $this->readAloud('phrase', $this->current()['say'], $this->language, 0.3);
    }

    public function nextPhrase(): void
    {
        $this->stopReading();
        $this->phrase = ($this->phrase + 1) % count(Phrases::LANGUAGES[$this->language]['phrases']);
        $this->clearAttempt();
    }

    public function shareProgress(): void
    {
        $name = Phrases::LANGUAGES[$this->language]['name'];
        $scores = array_filter($this->best, fn (string $key): bool => str_starts_with($key, $this->language.':'), ARRAY_FILTER_USE_KEY);

        $this->shareText(
            "My {$name} practice",
            count($scores) === 0
                ? "I'm practising {$name} pronunciation."
                : 'I practised '.count($scores)." {$name} ".str('phrase')->plural(count($scores)).' today. Best score: '.max($scores).'%.',
        );
    }

    /** @return array{say: string, means: string} */
    public function current(): array
    {
        return Phrases::LANGUAGES[$this->language]['phrases'][$this->phrase];
    }

    protected function dictationLocale(): ?string
    {
        return $this->language;
    }

    protected function dictationField(string $field): string
    {
        return '';
    }

    protected function fillDictationField(string $field, string $text): void
    {
        $this->attempt = $text;
    }

    protected function afterDictation(string $field): void
    {
        $this->score = Phrases::score($this->current()['say'], $this->attempt);
        $this->status = Phrases::verdict($this->score);

        $key = $this->language.':'.$this->phrase;
        $this->best[$key] = max($this->best[$key] ?? 0, $this->score);
    }

    private function clearAttempt(): void
    {
        $this->attempt = '';
        $this->score = null;
        $this->status = '';
    }

    public function render(): View
    {
        return view('native.language-practice');
    }
}
