<?php



Route::group(
    [
        'namespace' => 'Afracode\CRUD\app\Http\Controllers',
        'middleware' => config('crud.base.middleware'),
        'prefix'     => config('crud.base.prefix'),
    ],
    function () {
        Route::get('/', function(){
            echo 'Hello from the afracode package!';
        });
    });



Route::get('uploads/{file}', function ($file) {
    return Storage::disk('public')->response('/uploads/'.$file);
});


Route::post('crud/storeMedia', 'Afracode\CRUD\App\Controllers\CrudController@storeMedia')->name('crud.storeMedia');
Route::get('crud/deleteMedia/{fileName}', 'Afracode\CRUD\App\Controllers\CrudController@deleteMedia')->name('crud.deleteMedia');
