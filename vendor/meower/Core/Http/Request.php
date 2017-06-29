<?php

namespace Meower\Core\Http;


class Request
{
    /**
     * $_GET wrapper.
     * @var array
     */
    public $get = [];

    /**
     * $_POST wrapper.
     * @var array
     */
    public $post = [];

    /**
     * $_REQUEST wrapper.
     * @var array
     */
    public $request = [];

    /**
     * $_COOKIE wrapper.
     * @var array
     */
    public $cookie = [];

    /**
     * $_SESSION wrapper.
     * @var array
     */
    public $session = [];

    /**
     * $_FILES wrapper.
     * @var array
     */
    public $files = [];

    /**
     * $_SERVER wrapper.
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
        $this->session = $_SESSION ?? null;
        $this->files = $_FILES;
        $this->server = $_SERVER;
        // Add this later maybe
//        unset($_GET, $_POST, $_REQUEST, $_COOKIE, $_SESSION, $_FILES, $_SERVER);
    }
}