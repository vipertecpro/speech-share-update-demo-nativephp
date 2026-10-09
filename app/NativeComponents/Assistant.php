<?php

namespace App\NativeComponents;

use App\NativeComponents\Concerns\Dictates;
use App\NativeComponents\Concerns\ReadsAloud;
use App\NativeComponents\Concerns\Shares;
use App\Support\Assistant as Replies;
use Illuminate\View\View;
use PARTek\SpeechToText\Facades\SpeechToText;

/**
 * An AI chat screen with the plugins where people expect them: a microphone
 * in the composer (Speech to Text), a speaker on every reply (Text to
 * Speech) and a share button on every reply (Native Share).
 *
 * The microphone is hold-to-talk, like a voice message: hold it while you
 * speak, and the message sends when you let go. Voice mode also reads every
 * reply aloud.
 */
class Assistant extends DemoScreen
{
    use Dictates;
    use ReadsAloud;
    use Shares;

    /** @var list<array{role: 'user'|'assistant', text: string}> */
    public array $messages = [];

    public string $draft = '';

    /** Read every reply aloud. */
    public bool $voiceMode = false;

    /** When the microphone was pressed, in milliseconds, or null when it isn't held. */
    public ?int $holdStartedAt = null;

    /** Send the message as soon as the final words arrive. */
    public bool $sendWhenHeard = false;

    /** Presses shorter than this are taps, not holds. */
    public const HOLD_MS = 400;

    /** How fast dictated words type out, in milliseconds per character. */
    public const TYPE_MS = 22;

    /** How many characters were on screen when the latest words arrived. */
    public int $revealFrom = 0;

    /** When the latest words arrived, in milliseconds. */
    public ?int $revealStartedAt = null;

    /**
     * The message box text as it types out: the recognizer sends words in
     * bursts, and each burst is revealed a character at a time.
     */
    public function composerText(): string
    {
        return mb_substr($this->draft, 0, $this->shownLength());
    }

    public function isRevealing(): bool
    {
        return $this->shownLength() < mb_strlen($this->draft);
    }

    private function shownLength(): int
    {
        if ($this->revealStartedAt === null) {
            return mb_strlen($this->draft);
        }

        $typed = intdiv(max(0, (int) now()->getPreciseTimestamp(3) - $this->revealStartedAt), self::TYPE_MS);

        return min(mb_strlen($this->draft), $this->revealFrom + $typed);
    }

    public function startHold(): void
    {
        if ($this->listening) {
            return;
        }

        $this->holdStartedAt = (int) now()->getPreciseTimestamp(3);
        $this->sendWhenHeard = false;
        $this->toggleDictation('draft');
    }

    public function endHold(): void
    {
        $heldFor = $this->holdStartedAt === null ? 0 : (int) now()->getPreciseTimestamp(3) - $this->holdStartedAt;
        $this->holdStartedAt = null;

        if ($heldFor < self::HOLD_MS) {
            if ($this->listening) {
                $this->cancelDictation();
            }
            $this->status = 'Hold the microphone while you speak.';

            return;
        }

        if ($this->listening) {
            // The final words arrive in onDictated, which sends them.
            $this->sendWhenHeard = true;
            SpeechToText::stop();

            return;
        }

        // The phone already finished listening while the button was held.
        $this->send();
    }

    public function send(): void
    {
        $prompt = trim($this->draft);

        if ($prompt === '') {
            return;
        }

        $this->draft = '';
        $this->revealStartedAt = null;
        $this->ask($prompt);
    }

    public function ask(string $prompt): void
    {
        $reply = Replies::reply($prompt);

        $this->messages[] = ['role' => 'user', 'text' => $prompt];
        $this->messages[] = ['role' => 'assistant', 'text' => $reply];
        $this->status = '';

        if ($this->voiceMode) {
            $this->readAloud('reply-'.(count($this->messages) - 1), $reply);
        }
    }

    public function toggleVoiceMode(): void
    {
        $this->voiceMode = ! $this->voiceMode;
        $this->status = $this->voiceMode
            ? 'Voice mode on: every reply is read aloud.'
            : 'Voice mode off.';

        if (! $this->voiceMode && $this->speaking) {
            $this->stopReading();
        }
    }

    public function toggleReply(int $index): void
    {
        if ($this->isReading('reply-'.$index)) {
            $this->stopReading();

            return;
        }

        $this->readAloud('reply-'.$index, $this->messages[$index]['text'] ?? '');
    }

    public function shareReply(int $index): void
    {
        $this->shareText('Assistant reply', $this->messages[$index]['text'] ?? '');
    }

    public function shareChat(): void
    {
        $transcript = collect($this->messages)
            ->map(fn (array $message): string => ($message['role'] === 'user' ? 'You: ' : 'Assistant: ').$message['text'])
            ->implode("\n\n");

        $this->shareText('Chat with the assistant', $transcript);
    }

    public function newChat(): void
    {
        $this->stopReading();
        $this->messages = [];
        $this->draft = '';
        $this->status = '';
    }

    /**
     * A message's lines, for display. Native text doesn't keep line breaks,
     * so each line gets its own text element.
     *
     * @return list<string>
     */
    public function lines(string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R+/', $text) ?: []), fn (string $line): bool => $line !== ''));
    }

    protected function dictationField(string $field): string
    {
        return $this->draft;
    }

    protected function fillDictationField(string $field, string $text): void
    {
        // Keep what's already typed out, and type the rest from there.
        $shown = mb_strlen($this->composerText());
        $this->revealFrom = min($shown, mb_strlen($text));
        $this->revealStartedAt = (int) now()->getPreciseTimestamp(3);
        $this->draft = $text;
    }

    protected function afterDictation(string $field): void
    {
        if ($this->sendWhenHeard) {
            $this->sendWhenHeard = false;
            $this->send();
        }
    }

    public function render(): View
    {
        return view('native.assistant');
    }
}
