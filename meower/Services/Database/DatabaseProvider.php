<?php

namespace Meower\Services\Database;

use Meower\Core\Database\Database;
use Meower\Services\AbstractProvider;

class DatabaseProvider extends AbstractProvider
{
    /**
     * @param $serviceName
     */
    public function init($serviceName)
    {
        $this->di->$serviceName = new Database('users');
    }
}