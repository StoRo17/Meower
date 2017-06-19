<?php

namespace Meower\Exceptions\DI;

class ServiceDoesNotExistException extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code);
    }
}