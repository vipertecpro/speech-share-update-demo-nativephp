<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * The notes, kept in one JSON file in the app's storage. NativePHP clears
 * Laravel's cache on every update, so the notes never live in the cache.
 */
class NoteBook
{
    /**
     * Every note, the most recently changed first.
     *
     * @return list<array{id: string, body: string, created_at: string, updated_at: string}>
     */
    public static function all(): array
    {
        $notes = self::read();
        usort($notes, fn (array $a, array $b): int => strcmp($b['updated_at'], $a['updated_at']));

        return $notes;
    }

    /**
     * @return array{id: string, body: string, created_at: string, updated_at: string}|null
     */
    public static function find(string $id): ?array
    {
        foreach (self::read() as $note) {
            if ($note['id'] === $id) {
                return $note;
            }
        }

        return null;
    }

    /**
     * Save a note's text and return its id. A new note gets an id the first
     * time it is saved; an empty new note is never stored.
     */
    public static function save(?string $id, string $body): ?string
    {
        $notes = self::read();
        $now = now()->toIso8601String();

        foreach ($notes as $index => $note) {
            if ($note['id'] === $id) {
                $notes[$index]['body'] = $body;
                $notes[$index]['updated_at'] = $now;
                self::write($notes);

                return $id;
            }
        }

        if (trim($body) === '') {
            return null;
        }

        $id = (string) Str::ulid();
        $notes[] = ['id' => $id, 'body' => $body, 'created_at' => $now, 'updated_at' => $now];
        self::write($notes);

        return $id;
    }

    public static function delete(string $id): void
    {
        self::write(array_values(array_filter(self::read(), fn (array $note): bool => $note['id'] !== $id)));
    }

    /**
     * The first line of a note, for lists and the share sheet's title.
     */
    public static function title(string $body): string
    {
        $line = trim(strtok(trim($body), "\n") ?: '');

        return $line === '' ? 'Untitled note' : Str::limit($line, 60);
    }

    /**
     * @return list<array{id: string, body: string, created_at: string, updated_at: string}>
     */
    private static function read(): array
    {
        $path = self::path();
        $notes = is_file($path) ? json_decode((string) file_get_contents($path), true) : [];

        return is_array($notes) ? array_values($notes) : [];
    }

    /**
     * @param  list<array{id: string, body: string, created_at: string, updated_at: string}>  $notes
     */
    private static function write(array $notes): void
    {
        file_put_contents(self::path(), json_encode(array_values($notes), JSON_PRETTY_PRINT), LOCK_EX);
    }

    private static function path(): string
    {
        return storage_path('app/notes.json');
    }
}
