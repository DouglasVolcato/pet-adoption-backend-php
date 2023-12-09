<?php

namespace PetAdoption\infra\adapters;

use PetAdoption\domain\protocols\utils\PasswordHashCheckerInterface;
use PetAdoption\domain\protocols\utils\PasswordHasherInterface;

class BcryptAdapter implements PasswordHasherInterface, PasswordHashCheckerInterface
{
    public function hash(string $value): string
    {
        return $this->passwordHash($value, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    public function validate(string $value, string $hashedValue): bool
    {
        return $this->passwordVerify($value, $hashedValue);
    }

    public function passwordHash(string $value, string|int|null $algo, array $options): string
    {
        return password_hash($value, $algo, $options);
    }

    private function passwordVerify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
