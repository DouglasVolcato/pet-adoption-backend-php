<?php

namespace PetAdoption\main\factories\controllers\user;

use PetAdoption\domain\usecases\CreateUserUseCase;
use PetAdoption\infra\adapters\BcryptAdapter;
use PetAdoption\infra\adapters\UuidAdapter;
use PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\presentation\controllers\CreateUserController;

use function PetAdoption\main\factories\decorators\makeDbTransactionControllerDecoratorFactory;

function makeCreateUserControllerFactory(): ControllerInterface
{
    $userRepository = new UserMySQLRepository();
    $idGenerator = new UuidAdapter();
    $passwordHasher = new BcryptAdapter(10);
    $createUserService = new CreateUserUseCase(
        $userRepository,
        $userRepository,
        $idGenerator,
        $passwordHasher
    );
    $controller = new CreateUserController($createUserService);
    return makeDbTransactionControllerDecoratorFactory($controller);
}
