<?php

namespace Meower\Core\Http;


class Request
{
    /**
     * @var array
     */
    public $get = [];

    /**
     * @var array
     */
    public $post = [];

    /**
     * @var array
     */
    public $request = [];

    /**
     * @var array
     */
    public $cookie = [];

    /**
     * @var array
     */
    public $session = [];

    /**
     * @var array
     */
    public $files = [];

    /**
     * @var array
     */
    public $server = [];

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
        $this->cookie = $_COOKIE;
        $this->session = $_SESSION;
        $this->files = $_FILES;
        $this->server = $_SERVER;

        unset($_GET, $_POST, $_REQUEST, $_COOKIE, $_SESSION, $_FILES, $_SERVER);
    }
}