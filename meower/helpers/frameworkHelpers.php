<?php

function trimBothSlashes($string)
{
    return ltrim(rtrim($string, '/'), '/');
}

function dd($var)
{
    var_dump($var);
    exit;
}