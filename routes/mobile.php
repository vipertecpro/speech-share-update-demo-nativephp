<?php

use App\Layouts\TabsLayout;
use App\NativeComponents\ArticleReader;
use App\NativeComponents\Assistant;
use App\NativeComponents\CookMode;
use App\NativeComponents\FieldReport;
use App\NativeComponents\Home;
use App\NativeComponents\LanguagePractice;
use App\NativeComponents\Note;
use App\NativeComponents\Notes;
use App\NativeComponents\Playgrounds\ForceUpdatePlayground;
use App\NativeComponents\Playgrounds\PlaygroundsIndex;
use App\NativeComponents\Playgrounds\SharePlayground;
use App\NativeComponents\Playgrounds\SpeechToTextPlayground;
use App\NativeComponents\Playgrounds\TextToSpeechPlayground;
use App\NativeComponents\UpdateRequired;
use Illuminate\Support\Facades\Route;

Route::nativeGroup(TabsLayout::class, function () {
    Route::native('/', Home::class);
    Route::native('/playgrounds', PlaygroundsIndex::class);
});

Route::native('/update', UpdateRequired::class);

Route::native('/assistant', Assistant::class);
Route::native('/notes', Notes::class);
Route::native('/note/{id}', Note::class);
Route::native('/cook', CookMode::class);
Route::native('/language', LanguagePractice::class);
Route::native('/report', FieldReport::class);
Route::native('/reader', ArticleReader::class);

Route::native('/play/speech-to-text', SpeechToTextPlayground::class);
Route::native('/play/text-to-speech', TextToSpeechPlayground::class);
Route::native('/play/share', SharePlayground::class);
Route::native('/play/force-update', ForceUpdatePlayground::class);
