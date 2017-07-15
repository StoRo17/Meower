<?php

return [
    'request' => \Meower\Services\Request\RequestProvider::class,
    'response' => \Meower\Services\Response\ResponseProvider::class,
    'session' => \Meower\Services\Session\SessionProvider::class,
    'db' => \Meower\Services\Database\DatabaseProvider::class,
    'view' => \Meower\Services\View\ViewProvider::class,
];
