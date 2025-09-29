<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '[model-name]s'
], function () {
    Route::get('/', '[ModelName]Controller@index')->name('[modelName]s.index');
    Route::get('/{id}', '[ModelName]Controller@show')->name('[modelName]s.show');
    Route::put('/{id}', '[ModelName]Controller@update')->name('[modelName]s.update');
    Route::post('/', '[ModelName]Controller@store')->name('[modelName]s.store');
    Route::patch('/{id}', '[ModelName]Controller@restore')->name('[modelName]s.restore');
    Route::delete('/{id}', '[ModelName]Controller@delete')->name('[modelName]s.delete');
});
