<?php

namespace App\NativeComponents;

use App\NativeComponents\Concerns\ReadsAloud;
use App\NativeComponents\Concerns\Shares;
use App\Support\Article;
use Illuminate\View\View;

/**
 * Listen to an article. Text to Speech reads it a paragraph at a time and
 * highlights the one playing, with pause, resume and a choice of speed.
 * Native Share sends the article's link.
 */
class ArticleReader extends DemoScreen
{
    use ReadsAloud;
    use Shares;

    /**
     * Speeds on the plugin's rate scale (0.1 to 1.0). Null leaves the speed
     * to the phone.
     *
     * @var list<array{label: string, rate: ?float}>
     */
    public const SPEEDS = [
        ['label' => '0.75×', 'rate' => 0.4],
        ['label' => '1×', 'rate' => null],
        ['label' => '1.25×', 'rate' => 0.58],
    ];

    /** The chosen speed, or null for the phone's own. */
    public ?float $rate = null;

    /** The paragraph being read, or null when stopped. */
    public ?int $paragraph = null;

    public function play(): void
    {
        $this->readParagraph($this->paragraph ?? 0);
    }

    public function stopListening(): void
    {
        $this->stopReading();
        $this->paragraph = null;
        $this->status = 'Stopped.';
    }

    public function playFrom(int $paragraph): void
    {
        $this->readParagraph($paragraph);
    }

    public function chooseSpeed(int $speed): void
    {
        $this->rate = self::SPEEDS[$speed]['rate'] ?? null;

        if ($this->speaking && $this->paragraph !== null) {
            $this->readParagraph($this->paragraph);
        }
    }

    public function shareArticle(): void
    {
        $this->shareLink(Article::URL, ['title' => Article::TITLE, 'text' => 'Worth a listen:']);
    }

    protected function afterReading(string $key): void
    {
        $next = ($this->paragraph ?? -1) + 1;

        if ($next < count(Article::PARAGRAPHS)) {
            $this->readParagraph($next);

            return;
        }

        $this->paragraph = null;
        $this->status = 'You reached the end.';
    }

    private function readParagraph(int $paragraph): void
    {
        $this->paragraph = $paragraph;
        $text = ($paragraph === 0 ? Article::TITLE.'. ' : '').Article::PARAGRAPHS[$paragraph];

        $this->readAloud('paragraph-'.$paragraph, $text, rate: $this->rate);
    }

    public function render(): View
    {
        return view('native.article-reader');
    }
}
