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
     * The real-world demos.
     *
     * @var list<array{route: string, name: string, does: string, ios: string, android: string, uses: list<string>}>
     */
    public const DEMOS = [
        ['route' => '/assistant', 'name' => 'AI Assistant', 'does' => 'Talk to the composer, hear replies, share answers.', 'ios' => 'sparkles', 'android' => 'auto_awesome', 'uses' => ['stt', 'tts', 'share']],
        ['route' => '/notes', 'name' => 'Voice Notes', 'does' => 'Dictate notes, play them back, send them anywhere.', 'ios' => 'note.text', 'android' => 'sticky_note_2', 'uses' => ['stt', 'tts', 'share']],
        ['route' => '/cook', 'name' => 'Cook Mode', 'does' => 'Recipe steps read aloud, hands-free.', 'ios' => 'fork.knife', 'android' => 'restaurant', 'uses' => ['tts', 'share']],
        ['route' => '/language', 'name' => 'Language Practice', 'does' => 'Hear a phrase, say it back, see how well it was recognised.', 'ios' => 'character.bubble', 'android' => 'translate', 'uses' => ['tts', 'stt', 'share']],
        ['route' => '/report', 'name' => 'Field Report', 'does' => 'Fill a form by voice and send it as a file.', 'ios' => 'clipboard', 'android' => 'assignment', 'uses' => ['stt', 'tts', 'share']],
        ['route' => '/reader', 'name' => 'Article Reader', 'does' => 'Listen to an article and share the link.', 'ios' => 'newspaper', 'android' => 'article', 'uses' => ['tts', 'share']],
    ];

    /**
     * One playground per plugin.
     *
     * @var list<array{route: string, name: string, does: string, ios: string, android: string}>
     */
    public const PLAYGROUNDS = [
        ['route' => '/play/speech-to-text', 'name' => 'Speech to Text', 'does' => 'Languages, live results and every event.', 'ios' => 'mic.fill', 'android' => 'mic'],
        ['route' => '/play/text-to-speech', 'name' => 'Text to Speech', 'does' => 'Voices, rate, pitch, pause and resume.', 'ios' => 'speaker.wave.2.fill', 'android' => 'volume_up'],
        ['route' => '/play/share', 'name' => 'Native Share', 'does' => 'Text, links and files, and which app got them.', 'ios' => 'square.and.arrow.up', 'android' => 'share'],
        ['route' => '/play/force-update', 'name' => 'Force Update', 'does' => 'Version checks, hard and soft updates.', 'ios' => 'arrow.down.app.fill', 'android' => 'system_update'],
    ];

    /**
     * Icons for the plugins a demo uses.
     *
     * @var array<string, array{ios: string, android: string}>
     */
    public const PLUGIN_ICONS = [
        'stt' => ['ios' => 'mic.fill', 'android' => 'mic'],
        'tts' => ['ios' => 'speaker.wave.2.fill', 'android' => 'volume_up'],
        'share' => ['ios' => 'square.and.arrow.up', 'android' => 'share'],
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
