<?php

namespace App\NativeComponents;

use App\NativeComponents\Concerns\Dictates;
use App\NativeComponents\Concerns\ReadsAloud;
use App\NativeComponents\Concerns\Shares;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

/**
 * A site inspection form filled in by voice. Every field has its own
 * microphone (Speech to Text), the whole report can be read back to check
 * it (Text to Speech), and it is sent as a text file (Native Share).
 */
class FieldReport extends DemoScreen
{
    use Dictates;
    use ReadsAloud;
    use Shares;

    /**
     * The form's fields: name => label.
     *
     * @var array<string, string>
     */
    public const FIELDS = [
        'site' => 'Site or location',
        'found' => 'What you found',
        'action' => 'Action needed',
    ];

    public string $site = '';

    public string $found = '';

    public string $action = '';

    public function readBack(): void
    {
        if ($this->isReading('report')) {
            $this->stopReading();

            return;
        }

        $this->readAloud('report', $this->asText(spoken: true));
    }

    public function shareReport(): void
    {
        if (trim($this->site.$this->found.$this->action) === '') {
            $this->status = 'Fill in at least one field first.';

            return;
        }

        $path = storage_path('app/reports/site-report-'.now()->format('Y-m-d-His').'.txt');
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $this->asText());

        $this->shareFilePaths([$path], ['title' => 'Site report', 'text' => 'Site report for '.($this->site !== '' ? $this->site : 'today')]);
    }

    public function clearForm(): void
    {
        $this->stopReading();
        $this->site = $this->found = $this->action = '';
        $this->status = '';
    }

    public function asText(bool $spoken = false): string
    {
        $lines = $spoken ? [] : ['SITE REPORT', now()->format('j F Y'), ''];

        foreach (self::FIELDS as $field => $label) {
            $value = trim($this->{$field});
            $lines[] = $spoken ? "{$label}: ".($value !== '' ? $value : 'not filled in').'.' : "{$label}:\n".($value !== '' ? $value : '-')."\n";
        }

        return trim(implode($spoken ? ' ' : "\n", $lines));
    }

    protected function dictationField(string $field): string
    {
        return array_key_exists($field, self::FIELDS) ? $this->{$field} : '';
    }

    protected function fillDictationField(string $field, string $text): void
    {
        if (array_key_exists($field, self::FIELDS)) {
            $this->{$field} = $text;
        }
    }

    public function render(): View
    {
        return view('native.field-report');
    }
}
