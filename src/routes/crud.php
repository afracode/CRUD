<?php



Route::group(
    [
        'namespace' => 'Afranext\Crud\app\Http\Controllers',
        'middleware' => config('crud.base.middleware'),
        'prefix'     => config('crud.base.prefix'),
    ],
    function () {
        Route::get('/', function(){
            echo 'Hello from the afranext package!';
        });
    });



Route::get('uploads/{file}', function ($file) {
    return Storage::disk('public')->response('/uploads/'.$file);
});


Route::post('crud/storeMedia', 'Afranext\Crud\App\Controllers\CrudController@storeMedia')->name('crud.storeMedia');
Route::get('crud/deleteMedia/{fileName}', 'Afranext\Crud\App\Controllers\CrudController@deleteMedia')->name('crud.deleteMedia');
