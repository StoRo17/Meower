<?php

use Meower\Core\Http\Route;


Route::get('/', 'HomeController@index')->home();

Route::get('/posts/{id}', 'HomeController@show')->middleware('auth');

Route::post('/abs', 'BaseController@action');
Route::delete('/posts/{id}', 'PostController@destroy');

Route::group(['prefix' => '/admin', 'middleware' => 'admin'], function () {
    Route::get('/profile', 'AdminController@index');
});
