<?php

namespace PetAdoption\domain\protocols\repositories\user;

use PetAdoption\domain\protocols\entities\UserEntityType;

interface GetUserByIdRepositoryInterface
{
    public function getById(string $id): UserEntityType | null;
}
