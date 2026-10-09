<?php

namespace App\NativeComponents\Playgrounds;

use App\NativeComponents\DemoScreen;

/**
 * A plugin playground: every call the screen makes and every event it gets
 * back, newest first, so developers can see exactly what the plugin does.
 */
abstract class Playground extends DemoScreen
{
    /** @var list<array{time: string, event: string, detail: string}> */
    public array $events = [];

    /** When the first entry in the log happened, as a Unix time. */
    public ?float $logStartedAt = null;

    public function clearLog(): void
    {
        $this->events = [];
        $this->logStartedAt = null;
    }

    protected function noteEvent(string $event, array $payload = []): void
    {
        $detail = collect($payload)
            ->map(fn (mixed $value, string $key): string => $key.': '.match (true) {
                is_bool($value) => $value ? 'true' : 'false',
                $value === null => 'null',
                is_string($value) => '"'.str($value)->limit(60).'"',
                default => (string) $value,
            })
            ->implode(' · ');

        $now = microtime(true);
        $this->logStartedAt ??= $now;

        array_unshift($this->events, ['time' => sprintf('+%.2fs', $now - $this->logStartedAt), 'event' => $event, 'detail' => $detail]);
        $this->events = array_slice($this->events, 0, 12);
    }
}
