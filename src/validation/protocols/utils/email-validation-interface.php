<?php

namespace PetAdoption\validation\protocols\utils;

interface EmailValidationInterface
{
    public function isEmail(string $value): bool;
}
