<?php

namespace App\Http\Controllers;

use Meower\BaseController;
use Meower\DI\DIContainer;

class Controller extends BaseController
{
    /**
     * Controller constructor.
     * @param DIContainer $di
     */
    public function __construct(DIContainer $di)
    {
        parent::__construct($di);
    }
}