<?php

namespace App\Support;

/**
 * The demo's own switches, kept in storage so they survive restarts.
 */
class DemoSettings
{
    /**
     * Whether to pretend this build is too old, to show the Force Update gate.
     */
    public static function simulatesOldBuild(): bool
    {
        return (bool) (self::read()['simulate_old_build'] ?? false);
    }

    public static function setSimulatesOldBuild(bool $on): void
    {
        file_put_contents(self::path(), json_encode([...self::read(), 'simulate_old_build' => $on]), LOCK_EX);
    }

    /**
     * @return array<string, mixed>
     */
    private static function read(): array
    {
        $settings = is_file(self::path()) ? json_decode((string) file_get_contents(self::path()), true) : [];

        return is_array($settings) ? $settings : [];
    }

    private static function path(): string
    {
        return storage_path('app/demo-settings.json');
    }
}
