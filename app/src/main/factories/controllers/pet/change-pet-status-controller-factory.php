<?php

function makeChangePetStatusControllerFactory(): ControllerInterface
{
  $updatePetStatusRepository = new PetMySQLRepository();
  $changePetStatusService = new ChangePetStatusUseCase(
    $updatePetStatusRepository
  );
  $controller = new ChangePetStatusController($changePetStatusService);
  return makeDbTransactionControllerDecoratorFactory($controller);
}
