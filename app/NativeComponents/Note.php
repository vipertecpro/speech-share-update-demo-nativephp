<?php

namespace App\NativeComponents;

use App\Support\NoteBook;
use Illuminate\View\View;
use Native\Mobile\Attributes\On;
use Partek\NativeShare\Events\ShareCompleted;
use Partek\NativeShare\Facades\NativeShare;
use PARTek\SpeechToText\Events\PermissionDenied;
use PARTek\SpeechToText\Events\SpeechFailed as DictationFailed;
use PARTek\SpeechToText\Events\SpeechPartial;
use PARTek\SpeechToText\Events\SpeechResult;
use PARTek\SpeechToText\Facades\SpeechToText;
use PARTek\TextToSpeech\Events\SpeechFailed as ReadingFailed;
use PARTek\TextToSpeech\Events\SpeechFinished;
use PARTek\TextToSpeech\Events\SpeechStarted;
use PARTek\TextToSpeech\Facades\TextToSpeech;

/**
 * One note: dictate it (Speech to Text), hear it read back (Text to Speech)
 * and send it anywhere (Native Share). Every change is saved as you go.
 */
class Note extends DemoScreen
{
    public ?string $noteId = null;

    public string $body = '';

    /** What the recognizer has heard so far, shown while dictating. */
    public string $heard = '';

    public bool $listening = false;

    public bool $speaking = false;

    public ?string $shareId = null;

    public function mount(): void
    {
        $id = $this->param('id');
        $note = is_string($id) && $id !== 'new' ? NoteBook::find($id) : null;

        $this->noteId = $note['id'] ?? null;
        $this->body = $note['body'] ?? '';
    }

    public function updatedBody(): void
    {
        $this->noteId = NoteBook::save($this->noteId, $this->body) ?? $this->noteId;
    }

    // Speech to Text: tap to dictate, tap again to stop and keep the words.

    public function toggleDictation(): void
    {
        if ($this->listening) {
            SpeechToText::stop();

            return;
        }

        TextToSpeech::stop();
        $this->speaking = false;
        $this->heard = '';
        $this->listening = SpeechToText::listen();
        $this->status = $this->listening ? 'Listening… tap the microphone again when you are done.' : 'Dictation could not start.';
    }

    public function cancelDictation(): void
    {
        SpeechToText::cancel();
        $this->listening = false;
        $this->heard = '';
        $this->status = 'Dictation cancelled.';
    }

    #[On(SpeechPartial::class)]
    public function onHeard(string $text): void
    {
        $this->heard = $text;
    }

    #[On(SpeechResult::class)]
    public function onDictated(string $text, bool $isFinal): void
    {
        if (trim($text) !== '') {
            $this->body = trim($this->body) === '' ? trim($text) : rtrim($this->body).' '.trim($text);
            $this->updatedBody();
        }

        if ($isFinal) {
            $this->listening = false;
            $this->heard = '';
            $this->status = 'Added what you said to the note.';
        }
    }

    #[On(DictationFailed::class)]
    public function onDictationFailed(string $message): void
    {
        $this->listening = false;
        $this->heard = '';
        $this->status = $message;
    }

    #[On(PermissionDenied::class)]
    public function onMicrophoneDenied(?string $message = null): void
    {
        $this->listening = false;
        $this->status = 'Dictation needs the microphone. You can allow it in Settings.';
    }

    // Text to Speech: read the note aloud in the phone's own voice.

    public function toggleReading(): void
    {
        if ($this->speaking) {
            TextToSpeech::stop();
            $this->speaking = false;
            $this->status = 'Stopped reading.';

            return;
        }

        if (trim($this->body) === '') {
            $this->status = 'Write or dictate something first.';

            return;
        }

        $this->speaking = TextToSpeech::speak($this->body);
    }

    #[On(SpeechStarted::class)]
    public function onReadingStarted(?string $text = null): void
    {
        $this->speaking = true;
        $this->status = 'Reading your note aloud.';
    }

    #[On(SpeechFinished::class)]
    public function onReadingFinished(?string $text = null): void
    {
        $this->speaking = false;
        $this->status = 'Finished reading.';
    }

    #[On(ReadingFailed::class)]
    public function onReadingFailed(string $message, ?string $text = null): void
    {
        $this->speaking = false;
        $this->status = $message;
    }

    // Native Share: the phone's share sheet, and which app the note went to.

    public function share(): void
    {
        if (trim($this->body) === '') {
            $this->status = 'Write or dictate something first.';

            return;
        }

        $this->shareId = NativeShare::share(NoteBook::title($this->body), $this->body);
    }

    #[On(ShareCompleted::class)]
    public function onShared(string $id, bool $completed, ?string $app = null): void
    {
        if ($id !== $this->shareId) {
            return;
        }

        $this->status = $completed
            ? 'Shared with '.$this->appName($app).'.'
            : 'Sharing cancelled.';
    }

    public function delete(): void
    {
        TextToSpeech::stop();

        if ($this->noteId !== null) {
            NoteBook::delete($this->noteId);
        }

        $this->back();
    }

    /**
     * A readable name for the app the note was shared with, from the package
     * name (Android) or activity type (iOS) the plugin reports.
     */
    public function appName(?string $app): string
    {
        $known = [
            'com.whatsapp' => 'WhatsApp', 'net.whatsapp.WhatsApp.ShareExtension' => 'WhatsApp',
            'com.google.android.gm' => 'Gmail', 'com.apple.UIKit.activity.Mail' => 'Mail',
            'com.apple.UIKit.activity.Message' => 'Messages', 'com.google.android.apps.messaging' => 'Messages',
            'com.google.android.keep' => 'Keep', 'com.apple.mobilenotes.SharingExtension' => 'Notes',
            'com.slack' => 'Slack', 'org.telegram.messenger' => 'Telegram',
        ];

        return $known[$app ?? ''] ?? ($app !== null ? $app : 'an app');
    }

    public function render(): View
    {
        return view('native.note');
    }
}
