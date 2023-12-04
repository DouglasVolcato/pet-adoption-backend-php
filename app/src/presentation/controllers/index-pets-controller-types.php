<?php

class IndexPetsControllerInput
{
  public  UserEntityType $user;
}


class IndexPetsController
extends Controller
implements ControllerInterface
{
  private IndexPetsUseCase $indexPetsService;

  public function __construct(IndexPetsUseCase $indexPetsService)
  {
    parent::__construct();
    $this->indexPetsService = $indexPetsService;
  }

  protected function  perform(
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
