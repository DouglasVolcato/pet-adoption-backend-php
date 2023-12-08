<?php
namespace PetAdoption\presentation\controllers\IndexPetsController;

use PetAdoption\domain\usecases\IndexPetsUseCase\IndexPetsUseCase;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\main\protocols\controllers\ControllerOutputType;
use PetAdoption\presentation\controllers\Controller\Controller;
use PetAdoption\presentation\controllers\IndexPetsController\IndexPetsControllerInput;
use PetAdoption\presentation\protocols\validators\ValidatorInterface;
use PetAdoption\validation\composites\ValidatorComposite;
use stdClass;

use function PetAdoption\presentation\helpers\responses\ok;
use function PetAdoption\presentation\helpers\responses\unauthorized;

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
