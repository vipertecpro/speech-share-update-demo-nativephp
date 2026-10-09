<?php

namespace App\NativeComponents;

use App\NativeComponents\Concerns\Dictates;
use App\NativeComponents\Concerns\ReadsAloud;
use App\NativeComponents\Concerns\Shares;
use App\Support\Assistant as Replies;
use Illuminate\View\View;

/**
 * An AI chat screen with the plugins where people expect them: a microphone
 * in the composer (Speech to Text), a speaker on every reply (Text to
 * Speech) and a share button on every reply (Native Share). Voice mode
 * sends what you said as soon as you stop and reads the reply aloud.
 */
class Assistant extends DemoScreen
{
    use Dictates;
    use ReadsAloud;
    use Shares;

    /** @var list<array{role: 'user'|'assistant', text: string}> */
    public array $messages = [];

    public string $draft = '';

    /** Hands-free: send when dictation ends and read every reply aloud. */
    public bool $voiceMode = false;

    public function send(): void
    {
        $prompt = trim($this->draft);

        if ($prompt === '') {
            return;
        }

        $this->draft = '';
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
            ? 'Voice mode on: speak, and the reply is read aloud.'
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
        $this->draft = $text;
    }

    protected function afterDictation(string $field): void
    {
        if ($this->voiceMode) {
            $this->send();
        }
    }

    public function render(): View
    {
        return view('native.assistant');
    }
}
