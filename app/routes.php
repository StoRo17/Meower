<?php

use Core\Http\Route;


Route::get('/', function () {

})->home();
Route::get('/posts/{id}', 'PostController@show')->middleware('auth');

Route::post('/abs', 'Controller@action');
Route::delete('/posts/{id}', 'PostController@destroy');

Route::group(['prefix' => '/admin', 'middleware' => 'admin'], function () {
    Route::get('/profile', 'AdminController@index');
});