<?php

use PDO;

class MySqlConnectorSingleton implements DatabaseConnectorInterface
{
    private static $instance;
    private $pdo;

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function connect(): void
    {
        $dsn = "mysql:host=" . $_ENV['MYSQL_HOST'] . ";port=3306;dbname=" . $_ENV['MYSQL_DATABASE'];
        $user = $_ENV['MYSQL_USER'];
        $pass = $_ENV['MYSQL_PASSWORD'];

        $this->pdo = new PDO($dsn, $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function disconnect(): void
    {
        $this->pdo = null;
    }

    public function startTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commitTransaction(): void
    {
        $this->pdo->commit();
    }

    public function rollbackTransaction(): void
    {
        $this->pdo->rollBack();
    }
}
