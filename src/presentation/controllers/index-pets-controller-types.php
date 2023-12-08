<?php

namespace PetAdoption\presentation\controllers;

use PetAdoption\domain\protocols\entities\UserEntityType;
use PetAdoption\domain\usecases\IndexPetsUseCase;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\main\protocols\controllers\ControllerOutputType;
use PetAdoption\presentation\protocols\validators\ValidatorInterface;
use PetAdoption\validation\composites\ValidatorComposite;
use stdClass;

use function PetAdoption\presentation\helpers\responses\ok;
use function PetAdoption\presentation\helpers\responses\unauthorized;

class IndexPetsControllerInput
{
    public UserEntityType $user;
}

class IndexPetsController extends Controller implements ControllerInterface
{
    private IndexPetsUseCase $indexPetsService;

    public function __construct(IndexPetsUseCase $indexPetsService)
    {
        parent::__construct();
        $this->indexPetsService = $indexPetsService;
    }

    protected function perform(
        IndexPetsControllerInput $request
    ): ControllerOutputType {
        if (!$request->user->admin) {
            return unauthorized();
        }
        $this->indexPetsService->execute();
        return ok(new stdClass());
    }

    protected function getValidation(): ValidatorInterface
    {
        return new ValidatorComposite([]);
    }
}
