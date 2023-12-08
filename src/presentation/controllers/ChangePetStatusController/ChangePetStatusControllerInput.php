<?php
namespace PetAdoption\presentation\controllers\ChangePetStatusController;

use PetAdoption\domain\protocols\entities\UserEntityType;
use PetAdoption\domain\usecases\ChangePetStatusUseCase\ChangePetStatusUseCaseInput;

class ChangePetStatusControllerInput extends ChangePetStatusUseCaseInput
{
    public UserEntityType $user;
}