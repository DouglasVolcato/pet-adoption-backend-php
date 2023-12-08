<?php
namespace PetAdoption\domain\usecases\CreateUserUseCase;

class CreateUserUseCaseInput
{
    public string $name;
    public string $email;
    public string $password;
}