<?php

class SearchPetsController
extends Controller
implements ControllerInterface
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
