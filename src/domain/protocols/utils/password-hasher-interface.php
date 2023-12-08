<?php

namespace PetAdoption\domain\protocols\utils;

interface PasswordHasherInterface
{
    public function hash(string $value): string;
}
