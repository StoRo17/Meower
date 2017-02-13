<?php

use Core\Http\Router;

$router = new Router();

$router->get('/', 'HomeController@index');
$router->get('/news', 'NewsController@index');
$router->get('/news/{id}/', 'HomeController@show');
