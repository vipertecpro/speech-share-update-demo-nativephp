<?php

namespace App\NativeComponents;

use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;
use PARTek\ForceUpdate\Facades\ForceUpdate;

/**
 * Shown instead of the app when Force Update says this build is below the
 * minimum version. The button opens this platform's store listing.
 */
class UpdateRequired extends NativeComponent
{
    /** @var array{current: string, minimum: string, latest: ?string, outdated: bool, storeUrl: ?string, mode: string} */
    public array $status = [];

    public string $message = '';

    public function mount(): void
    {
        $this->status = ForceUpdate::status();
    }

    public function openStore(): void
    {
        $this->message = ForceUpdate::openStore() ? '' : 'There is no store listing for this platform yet.';
    }

    public function render(): View
    {
        return view('native.update-required');
    }
}
