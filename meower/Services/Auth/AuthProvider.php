<?php

namespace Meower\Services\Auth;

use Meower\Core\Auth\Auth;
use Meower\Services\AbstractProvider;

class AuthProvider extends AbstractProvider
{

    /**
     * @param $serviceName
     */
    function init($serviceName)
    {
        $this->di->$serviceName = new Auth();
    }
}