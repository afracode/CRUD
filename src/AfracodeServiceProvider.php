<?php

namespace Afracode\CRUD;

use Afracode\CRUD\app\Controller\Crud\MenuController;
use Afracode\CRUD\App\Controllers\CrudController;
use Afracode\CRUD\App\View\Components\Menu;
use App\Http\Controllers\Crud\UserController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AfracodeServiceProvider extends ServiceProvider
{

    public function register()
    {
        if (!Route::hasMacro('crud')) {
            $this->addRouteMacro();
        }
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
            __DIR__ . '/config/' => config_path('crud'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations'),
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


    private function addRouteMacro()
    {




        Route::macro('crud', function ($name, $controller) {

            if (app()->version() < 8) {



                Route::group(['middleware' => ['role_or_permission:super-admin|' . $name . '-create']], function () use ($name, $controller) {
                    Route::get($name . '/create', $controller . '@create');
                    Route::post($name, $controller . '@store');
                });


                Route::group(['middleware' => ['role_or_permission:super-admin|' . $name . '-update']], function () use ($name, $controller) {
                    Route::get($name . '/{id}/edit', $controller . '@edit');
                    Route::put($name . '/{id}', $controller . '@update');
                });

                Route::group(['middleware' => ['role_or_permission:super-admin|' . $name . '-delete']], function () use ($name, $controller) {
                    Route::delete($name . '/{id}', $controller . '@destroy');
                });


                Route::group(['middleware' => ['role_or_permission:super-admin|' . $name . '-read']], function () use ($name, $controller) {
                    Route::any('/datatable/' . $name, $controller . '@dataTable')->name('datatable');
                    Route::get($name, $controller . '@index');
                    Route::get($name . '/{id}', $controller . '@show');
                });

            } else {


                Route::group(['middleware' => ['role_or_permission:super-admin|' . $name . '-create']], function () use ($name, $controller) {
                    Route::get($name . '/create', [$controller, 'create']);
                    Route::post($name, [$controller, 'store']);
                });

                Route::group(['middleware' => ['role_or_permission:super-admin|' . $name . '-update']], function () use ($name, $controller) {
                    Route::get($name . '/{id}/edit', [$controller, 'edit']);
                    Route::put($name . '/{id}', [$controller, 'update']);
                });

                Route::group(['middleware' => ['role_or_permission:super-admin|' . $name . '-delete']], function () use ($name, $controller) {
                    Route::delete($name . '/{id}', [$controller, 'destroy']);
                });

                Route::group(['middleware' => ['role_or_permission:super-admin|' . $name . '-read']], function () use ($name, $controller) {
                    Route::any('/datatable/' . $name, [$controller, 'dataTable'])->name('datatable');
                    Route::any($name, [$controller, 'index']);
                    Route::any($name . '/{id}', [$controller, 'show']);
                });
            }
        });
    }


}
