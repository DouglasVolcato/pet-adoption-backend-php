<?php

namespace PetAdoption\presentation\controllers;

use Error;
use PetAdoption\domain\usecases\LoginUseCase;
use PetAdoption\domain\usecases\LoginUseCaseInput;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\main\protocols\controllers\ControllerOutputType;
use PetAdoption\presentation\protocols\validators\ValidatorInterface;
use PetAdoption\validation\builders\ValidatorBuilder;
use PetAdoption\validation\composites\ValidatorComposite;
use PetAdoption\validation\protocols\enums\FieldTypeEnum;

use function PetAdoption\presentation\helpers\responses\badRequest;
use function PetAdoption\presentation\helpers\responses\ok;

class LoginController extends Controller implements ControllerInterface
{
    private LoginUseCase $loginService;

    public function __construct(LoginUseCase $loginService)
    {
        parent::__construct();
        $this->loginService = $loginService;
    }

    protected function perform(
        LoginUseCaseInput $request
    ): ControllerOutputType {
        $output =  $this->loginService->execute($request);
        if ($output instanceof Error) {
            return badRequest($output);
        }
        return ok((object)[
            'token' => $output->token,
            'user' => (object)[
                'id' => $output->user->id,
                'name' => $output->user->name,
                'email' => $output->user->email,
                'admin' => $output->user->admin,
            ]
        ]);
    }

    protected function getValidation(): ValidatorInterface
    {
        return new ValidatorComposite([
            (new ValidatorBuilder())->of("email")->isRequired(),
            (new ValidatorBuilder())->of("email")->isType(FieldTypeEnum::STRING),
            (new ValidatorBuilder())->of("password")->isRequired(),
            (new ValidatorBuilder())->of("password")->isType(FieldTypeEnum::STRING),
        ]);
    }
}
