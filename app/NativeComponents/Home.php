<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use PARTek\ForceUpdate\Facades\ForceUpdate;

/**
 * The demo menu. Before anything else it asks Force Update whether this
 * build is still allowed, and hands over to the update screen if not. A
 * build that is allowed but behind the latest version gets a soft banner.
 */
class Home extends DemoScreen
{
    /**
     * The example apps, with the plugins each one uses.
     *
     * @var list<array{route: string, name: string, does: string, ios: string, android: string, uses: list<string>}>
     */
    public const DEMOS = [
        ['route' => '/assistant', 'name' => 'AI Assistant', 'does' => 'Talk to the message box, hear replies, share answers.', 'ios' => 'sparkles', 'android' => 'auto_awesome', 'uses' => ['Speech to Text', 'Text to Speech', 'Share']],
        ['route' => '/notes', 'name' => 'Voice Notes', 'does' => 'Dictate notes, play them back, send them anywhere.', 'ios' => 'note.text', 'android' => 'sticky_note_2', 'uses' => ['Speech to Text', 'Text to Speech', 'Share']],
        ['route' => '/cook', 'name' => 'Cook Mode', 'does' => 'Recipe steps read aloud, hands-free.', 'ios' => 'fork.knife', 'android' => 'restaurant', 'uses' => ['Text to Speech', 'Share']],
        ['route' => '/language', 'name' => 'Language Practice', 'does' => 'Hear a phrase, say it back, see how well it was recognised.', 'ios' => 'character.bubble', 'android' => 'translate', 'uses' => ['Text to Speech', 'Speech to Text', 'Share']],
        ['route' => '/report', 'name' => 'Field Report', 'does' => 'Fill a form by voice and send it as a file.', 'ios' => 'clipboard', 'android' => 'assignment', 'uses' => ['Speech to Text', 'Text to Speech', 'Share']],
        ['route' => '/reader', 'name' => 'Article Reader', 'does' => 'Listen to an article and share the link.', 'ios' => 'newspaper', 'android' => 'article', 'uses' => ['Text to Speech', 'Share']],
    ];

    /** The newer version to offer, or null when this build is current. */
    public ?string $newerVersion = null;

    public function mount(): void
    {
        if (ForceUpdate::isOutdated()) {
            $this->replace('/update');

            return;
        }

        $status = ForceUpdate::status();
        $latest = $status['latest'] ?? null;

        $this->newerVersion = is_string($latest) && version_compare($status['current'], $latest, '<') ? $latest : null;
    }

    public function update(): void
    {
        ForceUpdate::openStore();
    }

    public function render(): View
    {
        return view('native.home');
    }
}
