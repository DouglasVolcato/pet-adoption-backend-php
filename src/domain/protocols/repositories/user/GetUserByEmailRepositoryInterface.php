<?php

namespace PetAdoption\domain\protocols\repositories\user;

use PetAdoption\domain\protocols\entities\UserEntityType;

interface GetUserByEmailRepositoryInterface
{
    public function getByEmail(string $email): UserEntityType | null;
}
