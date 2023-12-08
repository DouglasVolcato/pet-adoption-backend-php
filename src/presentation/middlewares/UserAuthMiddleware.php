<?php

namespace PetAdoption\presentation\middlewares;

use Error;
use PetAdoption\domain\usecases\GetUserByTokenUserUseCase\GetUserByTokenUserUseCase;
use PetAdoption\main\protocols\middlewares\MiddlewareInterface;
use PetAdoption\presentation\helpers\errors\UnauthorizedError;
use PetAdoption\presentation\protocols\validators\ValidatorInterface;
use PetAdoption\validation\builders\ValidatorBuilder;
use PetAdoption\validation\composites\ValidatorComposite;

class UserAuthMiddleware extends Middleware implements MiddlewareInterface
{
    private GetUserByTokenUserUseCase $getUserByTokenService;

    public function __construct(GetUserByTokenUserUseCase $getUserByTokenService)
    {
        parent::__construct();
        $this->getUserByTokenService = $getUserByTokenService;
    }

    protected function perform(object $request): object
    {
        $authorizationSplit = explode(" ", $request->authorization);
        if (!$authorizationSplit || $authorizationSplit[0] !== "Bearer") {
            return new UnauthorizedError();
        }
        $foundUser = $this->getUserByTokenService->execute((object)['token' => $authorizationSplit[1]]);
        if (!$foundUser || $foundUser instanceof Error) {
            return new UnauthorizedError();
        }
        return (object)['user' => $foundUser];
    }

    protected function getValidation(): ValidatorInterface
    {
        return new ValidatorComposite([
            (new ValidatorBuilder())->of("authorization")->isRequired(),
        ]);
    }
}
