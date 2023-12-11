<?php

namespace PetAdoptionTest\utils\stubs\infra\database\pdo;

use PDO;
use PDOStatement;

class PDOStub extends PDO
{
    public function __construct()
    {
        $dsn = "mysql:host=" . getenv()['MYSQL_HOST']
            . ";port=3306;dbname="
            . getenv()['MYSQL_DATABASE'];
        $user = getenv()['MYSQL_USER'];
        $pass = getenv()['MYSQL_PASSWORD'];
        parent::__construct($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public function prepare(
        string $query,
        array $options = []
    ): PDOStatement|false {
        return new PDOStatementStub();
    }

    public function exec(string $statement): int|false
    {
        return 1;
    }
}
