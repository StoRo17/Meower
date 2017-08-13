<?php

namespace Meower\Services\Request;

use Meower\Core\Http\Request;
use Meower\Services\AbstractProvider;

class RequestProvider extends AbstractProvider
{
    /**
     * @param $serviceName
     */
    public function init($serviceName)
    {
        $this->di->$serviceName = new Request();
    }
}