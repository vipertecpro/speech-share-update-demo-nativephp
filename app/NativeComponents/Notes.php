<?php

namespace App\NativeComponents;

use App\Support\NoteBook;
use Carbon\CarbonImmutable;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/**
 * The notes list.
 */
class Notes extends NativeComponent
{
    /** @var list<array{id: string, body: string, created_at: string, updated_at: string}> */
    public array $notes = [];

    public function mount(): void
    {
        $this->load();
    }

    public function onResume(): void
    {
        $this->load();
    }

    public function load(): void
    {
        $this->notes = NoteBook::all();
    }

    public function title(string $body): string
    {
        return NoteBook::title($body);
    }

    public function preview(string $body): string
    {
        $rest = trim(substr(trim($body), strlen(strtok(trim($body), "\n") ?: '')));

        return $rest === '' ? 'No more text' : str($rest)->squish()->limit(90)->toString();
    }

    public function when(string $iso): string
    {
        return CarbonImmutable::parse($iso)->diffForHumans();
    }

    public function render(): View
    {
        return view('native.notes');
    }
}
