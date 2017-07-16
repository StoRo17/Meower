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

    protected function render($template, $args = [])
    {
        return $this->view->render($template, $args);
    }

    protected function view($template, $args = [])
    {
        return $this->response->body($this->render($template, $args));
    }
}