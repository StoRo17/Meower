<?php

return [
    'auth' => \Meower\Services\Auth\AuthProvider::class,
    'request' => \Meower\Services\Request\RequestProvider::class,
    'response' => \Meower\Services\Response\ResponseProvider::class,
    'db' => \Meower\Services\Database\DatabaseProvider::class,
    'view' => \Meower\Services\View\ViewProvider::class,
];
