<?php

namespace App\NativeComponents\Playgrounds;

use App\NativeComponents\Concerns\Shares;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

/**
 * Native Share with every method: text, a link and a file, plus sending
 * straight to one app on Android. The log shows which app the user picked.
 */
class SharePlayground extends Playground
{
    use Shares;

    /** On Android, skip the sheet and open WhatsApp directly. */
    public bool $straightToWhatsApp = false;

    public function shareSomeText(): void
    {
        $this->shareText('A note from the playground', 'Shared from the Native Share playground.', $this->options());
    }

    public function shareSomeLink(): void
    {
        $this->shareLink('https://parqore.com', [...$this->options(), 'title' => 'PARqore plugins', 'text' => 'Plugins for NativePHP Mobile:']);
    }

    public function shareSomeFile(): void
    {
        $path = storage_path('app/playground/hello.txt');
        File::ensureDirectoryExists(dirname($path));
        File::put($path, "Hello from the Native Share playground.\nCreated ".now()->toDayDateTimeString()."\n");

        $this->shareFilePaths([$path], [...$this->options(), 'title' => 'hello.txt']);
    }

    /** @return array{target?: string} */
    private function options(): array
    {
        return $this->straightToWhatsApp ? ['target' => 'com.whatsapp'] : [];
    }

    public function render(): View
    {
        return view('native.playgrounds.share');
    }
}
