<?php

namespace App\NativeComponents\Concerns;

use Native\Mobile\Attributes\On;
use Partek\NativeShare\Events\ShareCompleted;
use Partek\NativeShare\Facades\NativeShare;

/**
 * Native Share for a screen: text, a link or files through the phone's
 * share sheet, and which app the user picked.
 */
trait Shares
{
    /** The id of the share sheet this screen opened last. */
    public ?string $shareId = null;

    /** Runs when the share sheet closes. */
    protected function afterSharing(bool $completed, ?string $app): void
    {
        //
    }

    /** @param  array{exclude?: list<string>, target?: string}  $options */
    protected function shareText(string $title, string $text, array $options = []): void
    {
        $this->shareId = NativeShare::share($title, $text, $options);
        $this->noteEvent('share()', ['id' => $this->shareId]);
    }

    /** @param  array{title?: string, text?: string, image?: string, exclude?: list<string>, target?: string}  $options */
    protected function shareLink(string $url, array $options = []): void
    {
        $this->shareId = NativeShare::shareUrl($url, $options);
        $this->noteEvent('shareUrl()', ['id' => $this->shareId]);
    }

    /**
     * @param  list<string>  $paths
     * @param  array{title?: string, text?: string, exclude?: list<string>, target?: string}  $options
     */
    protected function shareFilePaths(array $paths, array $options = []): void
    {
        $this->shareId = NativeShare::shareFiles($paths, $options);
        $this->noteEvent('shareFiles()', ['id' => $this->shareId, 'files' => count($paths)]);
    }

    #[On(ShareCompleted::class)]
    public function onShared(string $id, bool $completed, ?string $app = null): void
    {
        if ($id !== $this->shareId) {
            return;
        }

        $this->status = $completed ? 'Shared with '.$this->appName($app).'.' : 'Sharing cancelled.';
        $this->noteEvent('ShareCompleted', ['completed' => $completed, 'app' => $app]);
        $this->afterSharing($completed, $app);
    }

    /**
     * A readable name for the app the user picked, from the package name
     * (Android) or activity type (iOS) the plugin reports.
     */
    public function appName(?string $app): string
    {
        $known = [
            'com.whatsapp' => 'WhatsApp', 'net.whatsapp.WhatsApp.ShareExtension' => 'WhatsApp',
            'com.google.android.gm' => 'Gmail', 'com.apple.UIKit.activity.Mail' => 'Mail',
            'com.apple.UIKit.activity.Message' => 'Messages', 'com.google.android.apps.messaging' => 'Messages',
            'com.apple.UIKit.activity.CopyToPasteboard' => 'Copy', 'com.apple.UIKit.activity.AirDrop' => 'AirDrop',
            'com.google.android.keep' => 'Keep', 'com.apple.mobilenotes.SharingExtension' => 'Notes',
            'com.google.android.apps.docs' => 'Drive', 'com.slack' => 'Slack', 'org.telegram.messenger' => 'Telegram',
        ];

        return $known[$app ?? ''] ?? ($app !== null && $app !== '' ? $app : 'an app');
    }
}
