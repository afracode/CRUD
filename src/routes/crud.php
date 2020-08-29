<?php



Route::group(
    [
        'namespace' => 'Afracode\CRUD\app\Http\Controllers',
        'middleware' => config('crud.middleware'),
        'prefix'     => config('crud.prefix'),
    ],
    function () {
        Route::get('/', function(){
            echo 'Hello from the afracode package!';
        });
    });
