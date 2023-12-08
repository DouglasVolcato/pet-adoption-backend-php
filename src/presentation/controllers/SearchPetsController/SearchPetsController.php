<?php
namespace PetAdoption\presentation\controllers\SearchPetsController;

use PetAdoption\domain\usecases\SearchPetsUseCase\SearchPetsUseCase;
use PetAdoption\domain\usecases\SearchPetsUseCase\SearchPetsUseCaseInput;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\main\protocols\controllers\ControllerOutputType;
use PetAdoption\presentation\controllers\Controller\Controller;
use PetAdoption\presentation\protocols\validators\ValidatorInterface;
use PetAdoption\validation\builders\ValidatorBuilder;
use PetAdoption\validation\composites\ValidatorComposite;
use PetAdoption\validation\protocols\enums\FieldTypeEnum;

use function PetAdoption\presentation\helpers\responses\ok;

class SearchPetsController extends Controller implements ControllerInterface
{
    private SearchPetsUseCase $searchPetsService;

    public function __construct(SearchPetsUseCase $searchPetsService)
    {
        parent::__construct();
        $this->searchPetsService = $searchPetsService;
    }

    protected function perform(
        SearchPetsUseCaseInput $request
    ): ControllerOutputType {
        $foundPets = $this->searchPetsService->execute($request);
        return ok($foundPets);
    }

    protected function getValidation(): ValidatorInterface
    {
        return new ValidatorComposite([
            (new ValidatorBuilder())->of("limit")->isRequired(),
            (new ValidatorBuilder())->of("limit")->isType(FieldTypeEnum::NUMBER),
            (new ValidatorBuilder())->of("offset")->isRequired(),
            (new ValidatorBuilder())->of("offset")->isType(FieldTypeEnum::NUMBER),
        ]);
    }
}
