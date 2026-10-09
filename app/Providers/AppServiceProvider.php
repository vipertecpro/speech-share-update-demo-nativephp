<?php

namespace App\Providers;

use App\Support\DemoSettings;
use Illuminate\Support\ServiceProvider;
use PARTek\ForceUpdate\Facades\ForceUpdate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Configure Force Update once, at boot, as the plugin asks. This build is
     * 1.0.0; the demo's "Pretend this build is too old" switch raises the
     * minimum to 1.1.0, so the update screen shows on the next launch. In a
     * real app the minimum would come from your server or config, and the
     * store links would be your own listings.
     */
    public function boot(): void
    {
        ForceUpdate::configure([
            'minimum' => DemoSettings::simulatesOldBuild() ? '1.1.0' : '1.0.0',
            'latest' => '1.1.0',
            'ios_url' => 'https://apps.apple.com/',
            'android_url' => 'https://play.google.com/store/apps/details?id='.config('nativephp.app_id', 'com.example.plugindemos'),
            'mode' => 'hard',
        ]);
    }
}
