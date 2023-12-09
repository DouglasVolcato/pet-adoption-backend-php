<?php

namespace PetAdoption\infra\adapters;

use PetAdoption\domain\protocols\utils\TokenDecrypterInterface;
use PetAdoption\domain\protocols\utils\TokenGeneratorInterface;
use Firebase\JWT\JWT;

class JwtAdapter implements TokenGeneratorInterface, TokenDecrypterInterface
{
    public function generateToken(object $content, string $secret): string
    {
        $token = JWT::encode(
            (array)$content,
            $secret,
            'HS256'
        );

        return $token;
    }

    public function decryptToken(string $token, string $secret): object|null
    {
        $decoded = JWT::decode($token, $secret, (object)['algorithms' => ['HS256']]);
        return $decoded;
    }
}
