<?php

namespace Afracode\CRUD;

use Illuminate\Support\ServiceProvider;

class AfracodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/crud.php', 'crud');
        include __DIR__ . '/routes/crud.php';
    }
}
