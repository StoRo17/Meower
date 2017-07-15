<?php

namespace Meower;

use Meower\Core\Database\Database;
use Meower\Core\Http\Request;
use Meower\Core\Http\Response;
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
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * BaseController constructor.
     *
     * @param DIContainer $di
     */
    public function __construct(DIContainer $di)
    {
        $this->di = $di;
        $this->view = $this->di->view;
        $this->request = $this->di->request;
        $this->response = $this->di->response;
    }
}
