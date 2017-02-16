<?php

namespace Meower;

class Database
{
    private $config;

    private $pdo;

    private $opt = [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
    ];

    public function __construct()
    {
        $this->config = require_once APP_PATH . '/config/database.php';
        $dsn = "{$this->config['driver']}:host={$this->config['host']};dbname={$this->config['database']};charset={$this->config['charset']}";
        $this->pdo = new \PDO($dsn, $this->config['username'], $this->config['password'], $this->opt);
    }
}
