<?php

class ChangePetStatusControllerInput extends ChangePetStatusUseCaseInput
{
  public UserEntityType $user;
}

class ChangePetStatusController
extends Controller
implements ControllerInterface
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
