<?php

use Core\Http\Response;

if (! function_exists('response')) {

    function response($body = null)
    {
        return new Response($body);
    }
}