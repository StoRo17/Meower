<?php

namespace Meower\Services;

use Meower\DI\DIContainer;

abstract class AbstractProvider
{
    /**
     * @var DIContainer
     */
    protected $di;

    /**
     * AbstractProvider constructor.
     * @param DIContainer $di
     */
    public function __construct(DIContainer $di)
    {
        $this->di = $di;
    }

    /**
     * @param $serviceName
     */
    abstract function init($serviceName);
}