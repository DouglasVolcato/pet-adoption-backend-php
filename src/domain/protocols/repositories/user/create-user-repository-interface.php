<?php

namespace PetAdoption\domain\protocols\repositories\user;

use PetAdoption\domain\protocols\entities\UserEntityType;

interface CreateUserRepositoryInterface
{
    public function create(UserEntityType $userEntity): UserEntityType;
}
