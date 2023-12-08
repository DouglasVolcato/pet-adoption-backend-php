<?php

namespace PetAdoption\infra\adapters;

use PetAdoption\domain\protocols\utils\PasswordHashCheckerInterface;
use PetAdoption\domain\protocols\utils\PasswordHasherInterface;

class BcryptAdapter implements PasswordHasherInterface, PasswordHashCheckerInterface
{
    private $hashSalt;

    public function __construct($hashSalt)
    {
        $this->hashSalt = $hashSalt;
    }

    public function hash($value): string
    {
        return password_hash($value, PASSWORD_BCRYPT, ['cost' => $this->hashSalt]);
    }

    public function validate($value, $hashedValue): bool
    {
        return password_verify($value, $hashedValue);
    }
}
