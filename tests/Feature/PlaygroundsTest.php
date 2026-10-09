<?php

use App\NativeComponents\Playgrounds\SharePlayground;
use App\NativeComponents\Playgrounds\SpeechToTextPlayground;
use App\NativeComponents\Playgrounds\TextToSpeechPlayground;
use Illuminate\Support\Facades\File;
use Native\Mobile\Testing\Native;

beforeEach(function () {
    $storage = sys_get_temp_dir().'/plugin-playgrounds-'.uniqid();
    File::ensureDirectoryExists("{$storage}/app");
    app()->useStoragePath($storage);
});

it('listens in the chosen language and logs every event', function () {
    Native::fakeBridge()->withSpeechRecognition();

    Native::test(SpeechToTextPlayground::class)
        ->call('chooseLocale', 'hi-IN')
        ->set('partial', false)
        ->tap('Listen')
        ->assertListeningForSpeech('hi-IN', false)
        ->assertSee('listen()')
        ->hearSpeech('namaste')
        ->assertSee('SpeechResult')
        ->assertSee('namaste');
});

it('speaks with the chosen voice, rate and pitch and logs the controls', function () {
    Native::fakeBridge()->withTextToSpeech();

    Native::test(TextToSpeechPlayground::class)
        ->set('text', 'Hello there')
        ->set('customVoice', true)
        ->set('rate', 0.8)
        ->set('pitch', 1.5)
        ->call('chooseLocale', 'en-GB')
        ->tap('Speak')
        ->assertSpoke('Hello there', 'en-GB')
        ->assertSee('rate: 0.8')
        ->startSpeaking()
        ->assertSee('SpeechStarted')
        ->tap('Pause')
        ->assertSee('pause()')
        ->tap('isSpeaking()')
        ->assertSee('isSpeaking() says no.');
});

it('leaves the voice, rate and pitch to the phone unless you set them', function () {
    Native::fakeBridge()->withTextToSpeech();

    Native::test(TextToSpeechPlayground::class)
        ->assertSee('Using the phone’s own voice, speed and pitch.')
        ->tap('Speak');

    expect(Native::fakeBridge()->callsTo('TextToSpeech.Speak')[0]['params'])->toBe(['text' => (new TextToSpeechPlayground)->text]);
});

it('shares a link, text and a file, and reports the app', function () {
    Native::test(SharePlayground::class, platform: 'android')
        ->tap('Share a link')
        ->assertUrlShared('https://parqore.com', 'PARqore plugins')
        ->completeShare('com.whatsapp')
        ->assertSee('ShareCompleted')
        ->assertSee('Shared with WhatsApp.')
        ->set('straightToWhatsApp', true)
        ->tap('Share text')
        ->assertShareTargeted('com.whatsapp')
        ->tap('Share a file')
        ->assertFilesShared();
});
