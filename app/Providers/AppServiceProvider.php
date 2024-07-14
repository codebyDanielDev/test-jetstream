<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Notifications\ChannelManager;
use App\Channels\BaileysWhatsAppChannel;

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
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->app->make(ChannelManager::class)->extend('whatsapp', function ($app) {
            return new BaileysWhatsAppChannel();
        });
    }

}
