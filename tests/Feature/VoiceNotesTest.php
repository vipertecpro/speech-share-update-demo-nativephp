<?php

use App\NativeComponents\Home;
use App\NativeComponents\Note;
use App\NativeComponents\Notes;
use App\NativeComponents\Playgrounds\ForceUpdatePlayground;
use App\NativeComponents\UpdateRequired;
use App\Support\DemoSettings;
use App\Support\NoteBook;
use Illuminate\Support\Facades\File;
use Native\Mobile\Testing\Native;

beforeEach(function () {
    // Each test gets its own empty storage for notes and settings.
    $storage = sys_get_temp_dir().'/voice-notes-'.uniqid();
    File::ensureDirectoryExists("{$storage}/app");
    app()->useStoragePath($storage);
});

it('dictates a note, saves it and shows it in the list', function () {
    Native::fakeBridge()->withSpeechRecognition();

    Native::test(Note::class, params: ['id' => 'new'])
        ->tap('Dictate')
        ->assertListeningForSpeech()
        ->hearPartialSpeech('Buy milk')
        ->assertSee('Buy milk')
        ->hearSpeech('Buy milk and eggs')
        ->assertSet('listening', false)
        ->assertSee('Added what you said to the note.');

    expect(NoteBook::all())->toHaveCount(1)
        ->and(NoteBook::all()[0]['body'])->toBe('Buy milk and eggs');

    Native::test(Notes::class)->assertSee('Buy milk and eggs');
});

it('adds dictation to the end of what is already written', function () {
    Native::fakeBridge()->withSpeechRecognition();
    $id = NoteBook::save(null, 'Shopping list:');

    Native::test(Note::class, params: ['id' => $id])
        ->tap('Dictate')
        ->hearSpeech('bread and butter');

    expect(NoteBook::find($id)['body'])->toBe('Shopping list: bread and butter');
});

it('explains when the microphone is not allowed', function () {
    Native::fakeBridge()->withSpeechRecognition();

    Native::test(Note::class, params: ['id' => 'new'])
        ->tap('Dictate')
        ->denySpeechPermission()
        ->assertSet('listening', false)
        ->assertSee('Dictation needs the microphone. You can allow it in Settings.');
});

it('reads the note aloud and stops on request', function () {
    Native::fakeBridge()->withTextToSpeech();
    $id = NoteBook::save(null, 'Call the bakery at five.');

    Native::test(Note::class, params: ['id' => $id])
        ->tap('Read aloud')
        ->assertSpoke('Call the bakery at five.')
        ->startSpeaking()
        ->assertSee('Reading your note aloud.')
        ->finishSpeaking()
        ->assertSee('Finished reading.');
});

it('shares the note and says which app it went to', function () {
    $id = NoteBook::save(null, "Weekend plans\nHike on Saturday.");

    Native::test(Note::class, params: ['id' => $id])
        ->tap('Share')
        ->assertTextShared('Weekend plans', "Weekend plans\nHike on Saturday.")
        ->completeShare('com.whatsapp')
        ->assertSee('Shared with WhatsApp.');
});

it('asks for something to read or share before an empty note does anything', function () {
    Native::fakeBridge()->withTextToSpeech();

    Native::test(Note::class, params: ['id' => 'new'])
        ->tap('Read aloud')
        ->assertNothingSpoken()
        ->tap('Share')
        ->assertTextNotShared()
        ->assertSee('Write or dictate something first.');
});

it('shows the update screen instead of the notes when this build is too old', function () {
    DemoSettings::setSimulatesOldBuild(true);
    $this->refreshApplication();
    Native::fakeBridge()->withInstalledAppVersion('1.0.0')->withAppStoreOpening();

    Native::test(UpdateRequired::class, platform: 'ios')
        ->assertSee('Update required')
        ->tap('Update now')
        ->assertAppStoreOpened('https://apps.apple.com/');
});

it('opens the demos on a current build and offers the newer version', function () {
    Native::fakeBridge()->withInstalledAppVersion('1.0.0')->withAppStoreOpening();

    Native::test(Home::class, platform: 'android')
        ->assertSee('AI Assistant')
        ->assertSee('Version 1.1.0 is available')
        ->tap('update')
        ->assertAppStoreOpened('https://play.google.com/store/apps/details?id='.config('nativephp.app_id', 'com.example.plugindemos'));
});

it('shows no update banner on the latest version', function () {
    Native::fakeBridge()->withInstalledAppVersion('1.1.0');

    Native::test(Home::class)
        ->assertSee('AI Assistant')
        ->assertDontSee('is available');
});

it('starts with an empty notes list', function () {
    Native::test(Notes::class)->assertSee('Say it, keep it, share it');
});

it('remembers the old-build switch for the next launch', function () {
    Native::test(ForceUpdatePlayground::class)
        ->assertSee('status()')
        ->set('simulateOldBuild', true)
        ->assertSee('Close and reopen the app to see the update screen.');

    expect(DemoSettings::simulatesOldBuild())->toBeTrue();
});
