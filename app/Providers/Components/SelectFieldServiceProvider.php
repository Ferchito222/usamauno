<?php

namespace Sibas\Providers\Components;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Sibas\Components\SelectFieldBuilder;

class SelectFieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('selectField', function() {
            return new SelectFieldBuilder();
        });
    }
}
