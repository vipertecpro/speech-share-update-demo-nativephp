<?php

use App\NativeComponents\ArticleReader;
use App\NativeComponents\Assistant;
use App\NativeComponents\CookMode;
use App\NativeComponents\FieldReport;
use App\NativeComponents\Home;
use App\NativeComponents\LanguagePractice;
use App\NativeComponents\Note;
use App\NativeComponents\Playgrounds\ForceUpdatePlayground;
use App\NativeComponents\Playgrounds\SharePlayground;
use App\NativeComponents\Playgrounds\SpeechToTextPlayground;
use App\NativeComponents\Playgrounds\TextToSpeechPlayground;
use App\Support\DemoNotes;
use Illuminate\Support\Facades\File;
use Native\Mobile\Testing\Native;

beforeEach(function () {
    $storage = sys_get_temp_dir().'/plugin-notes-'.uniqid();
    File::ensureDirectoryExists("{$storage}/app");
    app()->useStoragePath($storage);
});

it('says what comes from the plugins and what the demo adds', function (string $screen, string $note, array $params = []) {
    Native::test($screen, params: $params)
        ->assertSee('From the plugins')
        ->assertSee(DemoNotes::for($note)['plugins'])
        ->assertSee('Built in this demo')
        ->assertSee(DemoNotes::for($note)['demo']);
})->with([
    'home' => [Home::class, 'home'],
    'assistant' => [Assistant::class, 'assistant'],
    'note' => [Note::class, 'note', ['id' => 'new']],
    'cook mode' => [CookMode::class, 'cook'],
    'language practice' => [LanguagePractice::class, 'language'],
    'field report' => [FieldReport::class, 'report'],
    'article reader' => [ArticleReader::class, 'reader'],
    'speech to text playground' => [SpeechToTextPlayground::class, 'play-stt'],
    'text to speech playground' => [TextToSpeechPlayground::class, 'play-tts'],
    'share playground' => [SharePlayground::class, 'play-share'],
    'force update playground' => [ForceUpdatePlayground::class, 'play-update'],
]);

it('keeps the chat clear once a conversation starts', function () {
    Native::test(Assistant::class)
        ->tap('Plan a weekend in Goa')
        ->assertDontSee('Built in this demo');
});
