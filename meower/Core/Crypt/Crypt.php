<?php

namespace Meower\Core\Crypt;

use Meower\Core\Session;

class Crypt
{
    public static function generateToken()
    {
        $token = bin2hex(random_bytes(32));
        Session::put('csrf_token', $token);
        return $token;
    }
}