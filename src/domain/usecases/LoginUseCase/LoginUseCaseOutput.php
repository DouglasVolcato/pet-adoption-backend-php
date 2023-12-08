<?php
namespace PetAdoption\domain\usecases\LoginUseCase;

use PetAdoption\domain\protocols\entities\UserEntityType;

class LoginUseCaseOutput
{
    public UserEntityType $user;
    public string $token;
}