<?php

namespace PetAdoption\main\factories\controllers\pet;

use PetAdoption\domain\usecases\ChangePetStatusUseCase;
use PetAdoption\infra\databases\mysql\repositories\PetMySQLRepository;
use PetAdoption\main\protocols\controllers\ControllerInterface;
use PetAdoption\presentation\controllers\ChangePetStatusController;

use function PetAdoption\main\factories\decorators\makeDbTransactionControllerDecoratorFactory;

function makeChangePetStatusControllerFactory(): ControllerInterface
{
    $updatePetStatusRepository = new PetMySQLRepository();
    $changePetStatusService = new ChangePetStatusUseCase(
        $updatePetStatusRepository
    );
    $controller = new ChangePetStatusController($changePetStatusService);
    return makeDbTransactionControllerDecoratorFactory($controller);
}
