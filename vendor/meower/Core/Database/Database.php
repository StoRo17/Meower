<?php

namespace Meower\Core\Database;

class Database
{
    /**
     * @var array
     */
    private $config;
    /**
     * @var \PDO
     */
    private $pdo;
    /**
     * @var string
     */
    private $table;

    /**
     * @var array
     */
    private $opt = [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
    ];

    /**
     * @var array
     */
    private $data = [
        'select' => '*',
        'where' => []
    ];

    /**
     * Database constructor.
     * @param $table
     */
    public function __construct($table)
    {
//        $this->config = require_once APP_PATH . '/config/database.php';
//        $dsn = "{$this->config['driver']}:host={$this->config['host']};dbname={$this->config['database']};charset={$this->config['charset']}";
//        $this->pdo = new \PDO($dsn, $this->config['username'], $this->config['password'], $this->opt);
        $this->table = $table;
    }

    /**
     * Wrapper over SELECT in query
     * @param array ...$arguments
     * @return $this
     */
    public function select(...$arguments)
    {
        if (! empty($arguments)) {
            $this->data['select'] = implode(', ', $arguments);
        }

        return $this;
    }

    /**
     * Wrapper over WHERE in query
     * @param $key
     * @param $value
     * @return $this
     */
    public function where($key, $value)
    {
        $this->data['where'] = array_merge($this->data['where'], [$key => $value]);
        return $this;
    }

    /**
     * Compiles all parts in SQL query
     * @return string
     */
    public function toSql()
    {
        $sqlParts = '';
        $sqlParts[] = "SELECT {$this->data['select']} FROM {$this->table}";
        if ($this->data['where']) {
            $whereData = $this->buildWhere();
            $sqlParts[] = "WHERE $whereData";
        }

        return implode(' ', $sqlParts);
    }

    /**
     * Compiles WHERE statements
     * @return string
     */
    private function buildWhere()
    {
        return implode(' AND ', array_map(function ($key, $value) {
            $quotedValue = $this->pdo->quote($value);
            return "$key = $quotedValue";
        }, array_keys($this->data['where']), $this->data['where']));
    }

    /**
     * Executes the users query
     * @param $sql
     * @return array
     */
    public function query($sql)
    {
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute();
        if ($result !== false) {
            return $result->fetchAll();
        }

        return [];
    }

    /**
     * Executes the query
     * @param $sql
     * @return bool
     */
    public function execute($sql)
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute();
    }
}
