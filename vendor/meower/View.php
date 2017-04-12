<?php

namespace Meower;

use Meower\Http\Response;
use Twig_Environment;
use Twig_Loader_Filesystem;

class View
{
    public static function make($template, $args = [])
    {
        $loader = new Twig_Loader_Filesystem(VIEWS_PATH);
        // TODO: add cache maybe
        $twig = new Twig_Environment($loader);

        $body = $twig->render($template . '.html', $args);

        return new Response($body);
    }
}
