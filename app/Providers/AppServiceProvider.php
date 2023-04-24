<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use lbuchs\WebAuthn\WebAuthn;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(WebAuthn::class, function (Application $app) {

            return new WebAuthn(
                'WebAuthn Laravel',
                $app->get('request')->getHost(),
                ['none']
            );
        });
    }

}
