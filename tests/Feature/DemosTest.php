<?php

use App\NativeComponents\ArticleReader;
use App\NativeComponents\Assistant;
use App\NativeComponents\CookMode;
use App\NativeComponents\FieldReport;
use App\NativeComponents\LanguagePractice;
use App\Support\Article;
use App\Support\Assistant as Replies;
use App\Support\Phrases;
use App\Support\Recipe;
use Illuminate\Support\Facades\File;
use Native\Mobile\Testing\Native;

beforeEach(function () {
    $storage = sys_get_temp_dir().'/plugin-demos-'.uniqid();
    File::ensureDirectoryExists("{$storage}/app");
    app()->useStoragePath($storage);

    Native::fakeBridge()->withSpeechRecognition()->withTextToSpeech();
});

it('listens while the microphone is held and sends what you said when you let go', function () {
    $reply = Replies::SUGGESTIONS['Plan a weekend in Goa'];

    $screen = Native::test(Assistant::class)
        ->pressDown('startHold')
        ->assertListeningForSpeech()
        ->assertSee('Let go to send')
        ->hearPartialSpeech('Plan a weekend')
        ->assertSet('draft', 'Plan a weekend');

    $this->travel(2)->seconds();

    $screen->pressUp('endHold')
        ->assertSpeechListeningStopped()
        ->hearSpeech('plan a weekend in Goa')
        ->assertSet('draft', '')
        ->assertSee('Fontainhas')
        ->assertNothingSpoken()
        ->tap('read-1')
        ->assertSpoke($reply)
        ->tap('share-1')
        ->assertTextShared('Assistant reply', $reply)
        ->completeShare('com.whatsapp')
        ->assertSee('Shared with WhatsApp.');
});

it('types dictated words out a character at a time', function () {
    $this->freezeTime();

    $screen = Native::test(Assistant::class)
        ->pressDown('startHold')
        ->hearPartialSpeech('Plan a weekend in Goa');

    expect($screen->instance()->composerText())->toBe('');

    $this->travel(Assistant::TYPE_MS * 4)->milliseconds();
    expect($screen->instance()->composerText())->toBe('Plan');

    $this->travel(1)->seconds();
    expect($screen->instance()->composerText())->toBe('Plan a weekend in Goa');
});

it('sends straight away when the phone finished listening before you let go', function () {
    $screen = Native::test(Assistant::class)
        ->pressDown('startHold')
        ->hearSpeech('write a polite follow up email');

    $this->travel(5)->seconds();

    $screen->pressUp('endHold')
        ->assertSee('Following up on our conversation');
});

it('treats a quick tap on the microphone as a hint, not a message', function () {
    Native::test(Assistant::class)
        ->pressDown('startHold')
        ->pressUp('endHold')
        ->assertSpeechListeningCancelled()
        ->assertSet('listening', false)
        ->assertSet('messages', [])
        ->assertSee('Hold the microphone while you speak.');
});

it('reads every reply aloud in voice mode', function () {
    $screen = Native::test(Assistant::class)
        ->call('toggleVoiceMode')
        ->pressDown('startHold');

    $this->travel(2)->seconds();

    $screen->pressUp('endHold')
        ->hearSpeech('Explain recursion like I’m five')
        ->assertSee('standing in a line')
        ->assertSpoke(Replies::SUGGESTIONS['Explain recursion like I’m five']);
});

it('answers a suggestion and shares the whole chat', function () {
    Native::test(Assistant::class)
        ->tap('Write a polite follow-up email')
        ->assertSee('Following up on our conversation')
        ->call('shareChat')
        ->assertTextShared('Chat with the assistant');
});

it('reads every recipe step in turn until you stop', function () {
    Native::test(CookMode::class)
        ->tap('Read every step')
        ->assertSpoke('Step 1. '.Recipe::STEPS[0])
        ->startSpeaking()
        ->finishSpeaking()
        ->assertSet('step', 1)
        ->assertSpoke('Step 2. '.Recipe::STEPS[1])
        ->tap('Pause')
        ->assertSet('paused', true)
        ->tap('Resume')
        ->assertSet('paused', false)
        ->tap('Stop')
        ->assertSpeakingStopped()
        ->assertSet('handsFree', false)
        ->finishSpeaking()
        ->assertSet('step', 1);
});

it('pauses on Android by stopping, and resumes by reading the step again', function () {
    Native::test(CookMode::class, platform: 'android')
        ->tap('Read every step')
        ->startSpeaking()
        ->tap('Pause')
        ->assertSpeakingStopped()
        ->assertSet('paused', true)
        ->finishSpeaking()
        ->assertSet('step', 0)
        ->assertSet('paused', true)
        ->tap('Resume')
        ->assertSet('paused', false)
        ->assertSet('step', 0)
        ->finishSpeaking()
        ->assertSet('step', 1);

    expect(Native::fakeBridge()->callsTo('TextToSpeech.Speak'))->toHaveCount(3);
});

it('stops reading aloud when you leave the screen', function () {
    $screen = Native::test(CookMode::class)->tap('Read every step');

    $screen->instance()->unmount();

    $screen->assertSpeakingStopped();
});

it('shares the shopping list', function () {
    Native::test(CookMode::class)
        ->call('shareShoppingList')
        ->assertTextShared('Shopping list', Recipe::shoppingList());
});

it('speaks the phrase in its language and scores what you say back', function () {
    Native::test(LanguagePractice::class)
        ->tap('Listen')
        ->assertSpoke('Buenos días', 'es-ES')
        ->tap('Say it')
        ->assertListeningForSpeech('es-ES')
        ->hearSpeech('buenos dias')
        ->assertSet('score', 100)
        ->assertSee('The phone recognised the phrase.')
        ->call('shareProgress')
        ->assertTextShared('My Spanish practice', 'I practised 1 Spanish phrase today. Best score: 100%.')
        ->call('chooseLanguage', 'fr-FR')
        ->assertSee('Bonjour')
        ->assertSet('score', null);
});

it('ignores accents, case and punctuation when scoring', function () {
    expect(Phrases::score('¿Dónde está la estación?', 'donde esta la estacion'))->toBe(100)
        ->and(Phrases::score('Buenos días', 'adios'))->toBeLessThan(60)
        ->and(Phrases::score('Buenos días', ''))->toBe(0);
});

it('dictates into the field you pick and sends the report as a file', function () {
    $screen = Native::test(FieldReport::class)
        ->set('site', 'Block A')
        ->tap('mic-found')
        ->assertListeningForSpeech()
        ->hearPartialSpeech('Crack in')
        ->assertSet('found', 'Crack in')
        ->hearSpeech('Crack in the north wall')
        ->assertSet('found', 'Crack in the north wall')
        ->assertSet('site', 'Block A')
        ->tap('Read back')
        ->assertSpoke('Site or location: Block A. What you found: Crack in the north wall. Action needed: not filled in.')
        ->tap('Send report')
        ->assertFilesShared();

    $report = collect(File::files(storage_path('app/reports')))->first();

    expect(File::get($report->getPathname()))->toContain('Crack in the north wall');
});

it('puts a field back the way it was when dictation is cancelled', function () {
    Native::test(FieldReport::class)
        ->set('action', 'Call the builder')
        ->tap('mic-action')
        ->hearPartialSpeech('and the')
        ->assertSet('action', 'Call the builder and the')
        ->call('cancelDictation')
        ->assertSet('action', 'Call the builder');
});

it('reads the article a paragraph at a time and shares its link', function () {
    Native::test(ArticleReader::class)
        ->tap('play')
        ->assertSpoke(Article::TITLE.'. '.Article::PARAGRAPHS[0])
        ->finishSpeaking()
        ->assertSet('paragraph', 1)
        ->assertSpoke(Article::PARAGRAPHS[1])
        ->tap('Pause')
        ->assertSet('paused', true)
        ->tap('Resume')
        ->assertSet('paused', false)
        ->call('chooseSpeed', 2)
        ->assertSet('rate', 0.58)
        ->call('stopListening')
        ->assertSet('paragraph', null)
        ->call('shareArticle')
        ->assertUrlShared(Article::URL, Article::TITLE);

    $firstCall = Native::fakeBridge()->callsTo('TextToSpeech.Speak')[0]['params'];
    expect($firstCall)->not->toHaveKeys(['rate', 'pitch', 'locale']);
});
