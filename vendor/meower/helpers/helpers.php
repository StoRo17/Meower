<?php

use Meower\View;
use Meower\Http\Response;

if (!function_exists('response')) {

    function response($body = null, $status = 200)
    {
        return new Response($body, $status);
    }
}

if (!function_exists('view')) {

    function view($template, $args = [])
    {
        return View::make($template, $args);
    }
}
