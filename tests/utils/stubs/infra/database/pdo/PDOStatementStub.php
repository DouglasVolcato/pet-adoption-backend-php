<?php

namespace PetAdoptionTest\utils\stubs\infra\database\pdo;

use PDOStatement;
use PDO;

class PDOStatementStub extends PDOStatement
{
    public function execute($params = []): bool
    {
        return true;
    }

    public function fetch(
        int $mode = PDO::FETCH_DEFAULT,
        int $cursorOrientation = PDO::FETCH_ORI_NEXT,
        int $cursorOffset = 0
    ): mixed {
        return null;
    }
}
