<?php

namespace Meower\DI;

use Meower\Exceptions\DI\ServiceAlreadyExistException;
use Meower\Exceptions\DI\ServiceDoesNotExistException;

class DIContainer
{
    /**
     * @var array
     */
    private $container = [];

    /**
     * @param $name
     * @return mixed
     * @throws ServiceDoesNotExistException
     */
    public function __get($name)
    {
        if (!$this->isServiceExists($name)) {
            throw new ServiceDoesNotExistException("This service ({$name}) does not exist in container");
        }

        return $this->container[$name];
    }

    /**
     * @param $name
     * @param $value
     * @throws ServiceAlreadyExistException
     */
    public function __set($name, $value)
    {
        if ($this->isServiceExists($name)) {
            throw new ServiceAlreadyExistException("This service ({$name}) already in container");
        }

        $this->container[$name] = $value;
    }

    private function isServiceExists($name)
    {
        return array_key_exists($name, $this->container);
    }
}