<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google_Client;
use Illuminate\Support\Facades\Storage;

class GoogleClientProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Google_Client::class , function($app) {
            $client = new Google_Client();
            Storage::disk('local')->put('client_secret.json' , json_encode([
                'web' => config('services.google')
            ]));
            $client->setAuthConfig(Storage::path('client_secret.json'));
            return $client;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
