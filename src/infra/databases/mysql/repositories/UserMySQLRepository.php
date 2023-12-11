<?php

namespace PetAdoption\infra\databases\mysql\repositories;

use PetAdoption\domain\protocols\repositories\user\GetUserByEmailRepositoryInterface;
use PetAdoption\domain\protocols\repositories\user\GetUserByIdRepositoryInterface;
use PetAdoption\domain\protocols\repositories\user\CreateUserRepositoryInterface;
use PetAdoption\infra\databases\mysql\connection\MySQLConnectorSingleton;
use PetAdoption\domain\protocols\entities\UserEntityType;
use PDO;

class UserMySQLRepository implements
    CreateUserRepositoryInterface,
    GetUserByIdRepositoryInterface,
    GetUserByEmailRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO|null $pdo = null)
    {
        if ($pdo) {
            $this->pdo = $pdo;
        } else {
            $this->pdo = (MySQLConnectorSingleton::getInstance())->getPdo();
        }
    }

    public function create(UserEntityType $userEntity): UserEntityType
    {
        $sql = "INSERT INTO users (email, password, admin) VALUES (:email, :password, :admin)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'email' => $userEntity->email,
            'password' => $userEntity->password,
            'admin' => $userEntity->admin,
        ]);
        return $userEntity;
    }

    public function getByEmail(string $email): ?UserEntityType
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : null;
    }

    public function getById(string $id): ?UserEntityType
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : null;
    }
}
