<?php

namespace App\NativeComponents\Playgrounds;

use App\Support\DemoSettings;
use Illuminate\View\View;
use PARTek\ForceUpdate\Facades\ForceUpdate;

/**
 * Force Update's live status, the store link, and a switch that makes the
 * next launch see this build as too old.
 */
class ForceUpdatePlayground extends Playground
{
    /** @var array{current?: string, minimum?: string, latest?: ?string, outdated?: bool, storeUrl?: ?string, mode?: string} */
    public array $versionStatus = [];

    public bool $simulateOldBuild = false;

    public function mount(): void
    {
        $this->refresh();
        $this->simulateOldBuild = DemoSettings::simulatesOldBuild();
    }

    public function refresh(): void
    {
        $this->versionStatus = ForceUpdate::status();
        $this->noteEvent('status()', ['outdated' => $this->versionStatus['outdated'] ?? false]);
    }

    public function openStore(): void
    {
        $opened = ForceUpdate::openStore();

        $this->status = $opened ? 'Opened the store listing.' : 'The store listing could not be opened.';
        $this->noteEvent('openStore()', ['result' => $opened]);
    }

    public function updatedSimulateOldBuild(): void
    {
        DemoSettings::setSimulatesOldBuild($this->simulateOldBuild);
        $this->status = $this->simulateOldBuild
            ? 'Close and reopen the app to see the update screen.'
            : 'The next launch uses the real minimum again.';
    }

    public function render(): View
    {
        return view('native.playgrounds.force-update');
    }
}
