<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Notifications\ChannelManager;
use App\Channels\BaileysWhatsAppChannel;
use App\Services\GptService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GptService::class, function ($app) {
            return new GptService();
        });
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
