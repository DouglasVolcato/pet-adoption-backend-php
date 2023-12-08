<?php

namespace PetAdoption\domain\protocols\utils;

interface PasswordHashCheckerInterface
{
    public function validate(string $value, string $hashedValue): bool;
}
