<?php

use Meower\Http\Route;


Route::get('/', function () {
    return 'Hello World!';
})->home();

Route::get('/posts/{id}', 'HomeController@show')->middleware('auth');

Route::post('/abs', 'Controller@action');
Route::delete('/posts/{id}', 'PostController@destroy');

Route::group(['prefix' => '/admin', 'middleware' => 'admin'], function () {
    Route::get('/profile', 'AdminController@index');
});
