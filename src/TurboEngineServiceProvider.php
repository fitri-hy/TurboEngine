<?php
namespace TurboEngine;

use Illuminate\Support\ServiceProvider;
use TurboEngine\Core\Engine;

class TurboEngineServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/turboengine.php', 'turboengine');

        $this->app->singleton('turboengine', function ($app) {
            return new Engine($app['config']['turboengine']);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/turboengine.php' => config_path('turboengine.php'),
        ], 'config');
    }
}
