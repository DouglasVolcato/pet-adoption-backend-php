<?php
namespace PetAdoption\presentation\controllers\IndexPetsController;

use PetAdoption\domain\protocols\entities\UserEntityType;

class IndexPetsControllerInput
{
    public UserEntityType $user;
}