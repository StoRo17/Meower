<?php

use Meower\View;
use Meower\Http\Response;

if (! function_exists('response')) {

    function response($body = null)
    {
        return new Response($body);
    }
}

if (! function_exists('view')) {

    function view($template, $args = [])
    {
        return View::make($template, $args);
    }
}
