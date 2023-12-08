<?php

namespace PetAdoption\presentation\controllers;

use Error;
use PetAdoption\domain\usecases\CreateUserUseCase;
use PetAdoption\domain\usecases\CreateUserUseCaseInput;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\main\protocols\controllers\ControllerOutputType;
use PetAdoption\presentation\protocols\validators\ValidatorInterface;
use PetAdoption\validation\builders\ValidatorBuilder;
use PetAdoption\validation\composites\ValidatorComposite;
use PetAdoption\validation\protocols\enums\FieldTypeEnum;

use function PetAdoption\presentation\helpers\responses\badRequest;
use function PetAdoption\presentation\helpers\responses\ok;

class CreateUserController extends Controller implements ControllerInterface
{
    private CreateUserUseCase $createUserService;

    public function __construct(CreateUserUseCase $createUserService)
    {
        parent::__construct();
        $this->createUserService = $createUserService;
    }

    protected function perform(
        CreateUserUseCaseInput $request
    ): ControllerOutputType {
        $createdUser =  $this->createUserService->execute($request);
        if ($createdUser instanceof Error) {
            return badRequest($createdUser);
        }
        return ok((object)[
        'id' => $createdUser->id,
        'name' => $createdUser->name,
        'email' => $createdUser->email,
        'admin' => $createdUser->admin,
        ]);
    }

    protected function getValidation(): ValidatorInterface
    {
        return new ValidatorComposite([
        (new ValidatorBuilder())->of("name")->isRequired(),
        (new ValidatorBuilder())->of("name")->isType(FieldTypeEnum::STRING),
        (new ValidatorBuilder())->of("email")->isRequired(),
        (new ValidatorBuilder())->of("email")->isType(FieldTypeEnum::STRING),
        (new ValidatorBuilder())->of("email")->isEmail(),
        (new ValidatorBuilder())->of("password")->isRequired(),
        (new ValidatorBuilder())->of("password")->isType(FieldTypeEnum::STRING),
        (new ValidatorBuilder())->of("password")->isMinLength(6),
        ]);
    }
}
