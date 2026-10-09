<?php

namespace App\NativeComponents\Playgrounds;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

/**
 * The Playgrounds tab: one screen per plugin.
 */
class PlaygroundsIndex extends NativeComponent
{
    /**
     * One playground per plugin, with what it lets you try.
     *
     * @var list<array{route: string, name: string, does: string, ios: string, android: string, tries: list<string>}>
     */
    public const PLAYGROUNDS = [
        ['route' => '/play/speech-to-text', 'name' => 'Speech to Text', 'does' => 'Turn speech into text, with live results.', 'ios' => 'mic.fill', 'android' => 'mic', 'tries' => ['listen()', 'stop()', 'cancel()']],
        ['route' => '/play/text-to-speech', 'name' => 'Text to Speech', 'does' => 'Read text aloud with any voice, rate and pitch.', 'ios' => 'speaker.wave.2.fill', 'android' => 'volume_up', 'tries' => ['speak()', 'pause()', 'isSpeaking()']],
        ['route' => '/play/share', 'name' => 'Native Share', 'does' => 'Share text, links and files, and see where they went.', 'ios' => 'square.and.arrow.up', 'android' => 'share', 'tries' => ['share()', 'shareUrl()', 'shareFiles()']],
        ['route' => '/play/force-update', 'name' => 'Force Update', 'does' => 'Check versions and send people to the store.', 'ios' => 'arrow.down.app.fill', 'android' => 'system_update', 'tries' => ['status()', 'openStore()']],
    ];

    public function render(): View
    {
        return view('native.playgrounds.index');
    }
}
