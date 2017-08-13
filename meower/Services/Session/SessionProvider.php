<?php

namespace Meower\Services\Session;

use Meower\Core\Session;
use Meower\Services\AbstractProvider;

class SessionProvider extends AbstractProvider
{
    /**
     * @param $serviceName
     */
    function init($serviceName)
    {
        $this->di->$serviceName = new Session();
    }
}