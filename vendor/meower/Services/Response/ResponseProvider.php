<?php

namespace Meower\Services\Response;

use Meower\Core\Http\Response;
use Meower\Services\AbstractProvider;

class ResponseProvider extends AbstractProvider
{
    /**
     * @param $serviceName
     */
    public function init($serviceName)
    {
        $this->di->$serviceName = new Response();
    }
}