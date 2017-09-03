<?php

namespace Ferrisbane\Laraformer;

use Illuminate\Support\ServiceProvider;

class Laravel5ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Set the directory to load views from
        $this->loadViewsFrom(__DIR__ . '/../views', 'laraformer');

        // Set the files to publish
        $this->publishes([
            __DIR__ . '/../config/laraformer.php' => config_path('laraformer.php'),
            __DIR__ . '/../database/migrations/' => base_path('database/migrations')
        ], 'laraformer');

        $this->mergeConfigFrom(__DIR__ . '/../config/laraformer.php', 'laraformer');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('Ferrisbane\Laraformer\Laraformer', 'Laraformer');
    }

    /**
     * Get the package config.
     *
     * @return array
     */
    protected function getConfig()
    {
        return config('laraformer');
    }
}
