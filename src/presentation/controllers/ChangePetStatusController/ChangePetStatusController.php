<?php
namespace PetAdoption\presentation\controllers\ChangePetStatusController;

use Error;
use PetAdoption\domain\usecases\ChangePetStatusUseCase\ChangePetStatusUseCase;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\main\protocols\controllers\ControllerOutputType;
use PetAdoption\presentation\controllers\Controller\Controller;
use PetAdoption\presentation\protocols\validators\ValidatorInterface;
use PetAdoption\validation\builders\ValidatorBuilder;
use PetAdoption\validation\composites\ValidatorComposite;
use PetAdoption\validation\protocols\enums\FieldTypeEnum;

use function PetAdoption\presentation\helpers\responses\badRequest;
use function PetAdoption\presentation\helpers\responses\ok;
use function PetAdoption\presentation\helpers\responses\unauthorized;

class ChangePetStatusController extends Controller implements ControllerInterface
{
    private ChangePetStatusUseCase $changePetStatusService;

    public function __construct(ChangePetStatusUseCase $changePetStatusService)
    {
        parent::__construct();
        $this->changePetStatusService = $changePetStatusService;
    }

    protected function perform(
        ChangePetStatusControllerInput $request
    ): ControllerOutputType {
        if (!$request->user->admin) {
            return unauthorized();
        }
        $updatedPet =  $this->changePetStatusService->execute((object)[
        'petId' => $request->petId,
        'newStatus' => $request->newStatus,
        ]);
        if ($updatedPet instanceof Error) {
            return badRequest($updatedPet);
        }
        return ok($updatedPet);
    }

    protected function getValidation(): ValidatorInterface
    {
        return new ValidatorComposite([
        (new ValidatorBuilder())->of("petId")->isRequired(),
        (new ValidatorBuilder())->of("petId")->isType(FieldTypeEnum::STRING),
        (new ValidatorBuilder())->of("newStatus")->isRequired(),
        (new ValidatorBuilder())->of("newStatus")->isType(FieldTypeEnum::STRING),
        ]);
    }
}
