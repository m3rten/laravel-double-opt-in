<?php

namespace M3rten\DoubleOptIn;

use Illuminate\Support\ServiceProvider;

class DoubleOptInServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        #include __DIR__ . '/routes.php';
        $this->loadViewsFrom(__DIR__.'/views', 'doubleoptin');

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/doubleoptin'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/lang', 'doubleoptin');

        $this->publishes([
            __DIR__.'/lang' => base_path('resources/lang/vendor/doubleoptin'),
        ]);

        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
