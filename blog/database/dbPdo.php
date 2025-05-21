<?php

namespace App\Database;
use PDO;

class dbPdo
{
    protected static ?self $instance = null;
    protected PDO $conn;

    protected function __construct(array $options)
    {
        $this->conn = new PDO($options['dsn'], $options['username'], $options['password']);
        if (array_key_exists('options', $options)) {
            foreach ($options['options'] as $key => $value) {
                $this->conn->setAttribute($key, $value);
            }
        }
    }

    public static function getInstance(array $options): static
    {
        if (!static::$instance) {
            static::$instance = new static($options);
        }
        return static::$instance;
    }

    public function getConn(): PDO
    {
        return $this->conn;
    }
}