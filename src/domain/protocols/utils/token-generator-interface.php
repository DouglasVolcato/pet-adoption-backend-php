<?php

namespace PetAdoption\domain\protocols\utils;

interface TokenGeneratorInterface
{
    public function generateToken(object $content, string $secret): string;
}
