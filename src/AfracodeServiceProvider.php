<?php

namespace Afracode\CRUD;

use Afracode\CRUD\app\Controller\Crud\MenuController;
use Afracode\CRUD\App\Controllers\CrudController;
use Afracode\CRUD\App\View\Components\Menu;
use Afracode\CRUD\Overrides\ResourceRegistrar;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AfracodeServiceProvider extends ServiceProvider
{

    public function register()
    {
        if (! Route::hasMacro('crud')) {
            $this->addCrudRoute();
        }
    }

    public function addCrudRoute()
    {
        $registrar = new ResourceRegistrar($this->app['router']);

        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function () use ($registrar) {
            return $registrar;
        });
    }


    public function boot(\Illuminate\Routing\Router $router)
    {

        $this->publishFiles();

        $this->configFiles();

        $this->routeFiles();

        $this->controllerFiles();

        $this->helperFiles();

        $this->viewFiles();

        $this->componentFiles();
    }


    public function publishFiles()
    {

        $this->publishes([
            __DIR__.'/config/' => config_path('crud'),
        ], 'config');

        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations'),
        ], 'migration');
    }


    public function configFiles()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/crud/base.php', 'crud');
    }


    public function routeFiles()
    {
        include __DIR__ . '/routes/crud.php';

    }


    public function controllerFiles()
    {
        $this->app->make(MenuController::class);
        $this->app->make(CrudController::class);
    }


    public function helperFiles()
    {
        require_once __DIR__ . '/helpers.php';
    }


    public function viewFiles()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'crud');
    }


    public function componentFiles()
    {
        $this->loadViewComponentsAs('crud', [
            Menu::class,
        ]);
    }


}
