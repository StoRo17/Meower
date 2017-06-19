<?php

namespace Meower\Services\View;


use Meower\Services\AbstractProvider;
use Meower\View;

class ViewProvider extends AbstractProvider
{
    /**
     * @param $serviceName
     */
    function init($serviceName)
    {
        $this->di->$serviceName = new View();
    }
}