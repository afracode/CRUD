<?php

namespace Afracode\CRUD;

use Afracode\CRUD\app\Controller\Crud\MenuController;
use Afracode\CRUD\App\Controllers\CrudController;
use Afracode\CRUD\Overrides\ResourceRegistrar;
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

        $this->app->make(MenuController::class);


        $this->mergeConfigFrom(__DIR__ . '/config/crud.php', 'crud');

        include __DIR__ . '/routes/crud.php';

        $this->app->make(CrudController::class);

        $registrar = new ResourceRegistrar($this->app['router']);

        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function () use ($registrar) {
            return $registrar;
        });

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'crud');




        require_once __DIR__ . '/helpers.php';



        $this->publishFiles();

    }


    public function publishFiles()
    {

        $this->publishes( [__DIR__ . '/config' => config_path()] , 'config');
    }
}
