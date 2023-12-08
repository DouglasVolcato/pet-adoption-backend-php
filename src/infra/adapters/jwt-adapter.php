<?php

namespace PetAdoption\infra\adapters;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use PetAdoption\domain\protocols\utils\TokenDecrypterInterface;
use PetAdoption\domain\protocols\utils\TokenGeneratorInterface;

class JwtAdapter implements TokenGeneratorInterface, TokenDecrypterInterface
{
    public function generateToken($content, $secret): string
    {
        $builder = new Builder();
        $token = $builder
        ->withClaim('your_custom_claim', $content)
        ->getToken(new Sha256(), new Key($secret));

        return $token->__toString();
    }

    public function decryptToken($token, $secret): object|null
    {
        try {
            $parsedToken = (new Parser())->parse((string)$token);
            $data = new ValidationData();

            if ($parsedToken->validate($data) && $parsedToken->verify(new Sha256(), $secret)) {
                return $parsedToken->getClaim('your_custom_claim');
            }

            return null;
        } catch (\Exception $exception) {
            return null;
        }
    }
}
