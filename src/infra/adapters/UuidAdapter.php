<?php

namespace PetAdoption\infra\adapters;

use PetAdoption\domain\protocols\utils\IdGeneratorInterface;
use Ramsey\Uuid\Uuid;

class UuidAdapter implements IdGeneratorInterface
{
    public function generateId(): string
    {
        return Uuid::uuid4()->toString();
    }
}
