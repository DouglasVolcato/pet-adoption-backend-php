<?php

namespace PetAdoption\main\factories\middlewares\auth;

use PetAdoption\domain\usecases\GetUserByTokenUserUseCase;
use PetAdoption\infra\adapters\JwtAdapter;
use PetAdoption\infra\databases\mysql\repositories\UserMySQLRepository;
use PetAdoption\presentation\middlewares\UserAuthMiddleware;

function makeUserAuthMiddleware(): UserAuthMiddleware
{
    $tokenDecrypter = new JwtAdapter();
    $getUserByIdRepository = new UserMySQLRepository();
    $getUserByTokenService = new GetUserByTokenUserUseCase(
        $tokenDecrypter,
        $getUserByIdRepository
    );
    return new UserAuthMiddleware($getUserByTokenService);
}
