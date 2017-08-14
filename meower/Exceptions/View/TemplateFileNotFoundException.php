<?php

namespace Meower\Exceptions\View;

class TemplateFileNotFoundException extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code);
    }
}