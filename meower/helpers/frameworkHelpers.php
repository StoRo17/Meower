<?php

use Meower\Core\Crypt\Crypt;

function trimBothSlashes($string)
{
    return ltrim(rtrim($string, '/'), '/');
}

function dd($var)
{
    var_dump($var);
    exit;
}

function csrf_field()
{
    return '<input type="hidden" name="_token" value=' . Crypt::generateToken() . '>';
}

function csrf_token()
{
    echo Crypt::generateToken();
}