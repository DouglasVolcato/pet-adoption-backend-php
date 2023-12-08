<?php

namespace PetAdoption\main\factories\controllers\login;

use PetAdoption\domain\usecases\LoginUseCase;
use PetAdoption\infra\adapters\BcryptAdapter;
use PetAdoption\infra\adapters\JwtAdapter;
use PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\presentation\controllers\LoginController;

use function PetAdoption\main\factories\decorators\makeDbTransactionControllerDecoratorFactory;

function makeLoginControllerFactory(): ControllerInterface
{
    $getUserByEmailRepository = new UserMySQLRepository();
    $passwordHashChecker = new BcryptAdapter(10);
    $tokenGenerator = new JwtAdapter();
    $loginService = new LoginUseCase(
        $getUserByEmailRepository,
        $passwordHashChecker,
        $tokenGenerator
    );
    $controller = new LoginController($loginService);
    return makeDbTransactionControllerDecoratorFactory($controller);
}
