<?php

namespace PetAdoption\domain\protocols\utils;

interface TokenDecrypterInterface
{
    public function decryptToken(string $token, string $secret): object | null;
}
