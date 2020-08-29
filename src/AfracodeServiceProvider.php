<?php

namespace Afracode\CRUD;

use Afracode\CRUD\app\Overrides\CrudRegistrar;
use Afracode\CRUD\app\Overrides\ResourceRegistrar;
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


    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->mergeConfigFrom(__DIR__ . '/config/crud.php', 'router');
        include __DIR__ . '/routes/crud.php';

        $registrar = new ResourceRegistrar($this->app['router']);

        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function () use ($registrar) {
            return $registrar;
        });
    }
}
