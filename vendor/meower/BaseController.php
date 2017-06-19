<?php

namespace Meower;

use Meower\Core\Database\Database;
use Meower\DI\DIContainer;

abstract class BaseController
{
    /**
     * @var DIContainer
     */
    protected $di;

    /**
     * @var Database
     */
    protected $db;

    /**
     * @var View
     */
    protected $view;

    /**
     * BaseController constructor.
     * @param DIContainer $di
     */
    public function __construct(DIContainer $di)
    {
        $this->di = $di;
        $this->view = $this->di->view;
    }
}
